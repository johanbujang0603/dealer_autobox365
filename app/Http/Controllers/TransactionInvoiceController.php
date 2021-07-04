<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\TransactionInvoice;
use Illuminate\Http\Request;
use Auth;
use PDF;

class TransactionInvoiceController extends Controller
{
    //

    public function create()
    {
        # code...
        $page_title = 'Transaction Invoice';
        return view('transactions.invoice.create', compact('page_title'));
    }

    public function load_basic_data()
    {
        $customers = Customer::where(function($q) {
            $q->where('user_id', Auth::user()->id)
            ->orWhere('dealer_id', Auth::user()->id);
        })->get()->toArray();
        $currencies = Currency::all()->toArray();
        $user_setting = Auth::user()->settings;
        $cur_symbol = Currency::where('iso_code', config('app.currency'))->first();
        $cur_symbol = array('value' =>$cur_symbol->id, 'label' => $cur_symbol->currency . '(' . $cur_symbol->symbol . ')');
        if ($user_setting) {
            $company_logo = $user_setting->company_logo_src;
        } else {
            $company_logo = asset("global_assets/images/logo_dark.png");
        }
        return $data = array(
            'company_logo' => $company_logo,
            'settings' => $user_setting,
            'customer_list' => $customers,
            'currencies' => $currencies,
            'cur_currency' => $cur_symbol
        );
    }
    public function get_customer_info(Request $request) {
        $customer = Customer::with(['phone_number_details', 'email_details'])->find($request->customer);
        return $customer;
    }

    public function get_price_with_currency(Request $request) {
        $descriptions = $request->descriptions;
        $total = 0;
        $user_setting = Auth::user()->settings;
        $cur_currency = Currency::where('iso_code', config('app.currency'))->first();
        foreach ($descriptions as $desc) {
            $currency = isset($desc['currency']) ? $desc['currency'] : Currency::where('iso_code', config('app.currency'))->first()->id;
            $model_currency = Currency::find($currency);
            $price = isset($desc['price']) ? $desc['price'] : 0;
            $quantity = isset($desc['quantity']) ? $desc['quantity'] : 0;
            $total_price = (float)$price * (int)$quantity;
            $total_price = ((float) $total_price * $model_currency->currency_rate) / (float) $cur_currency->currency_rate;
            $total += $total_price;   
        }
        $total_details_price = $total;
        $total_with_tax = (float)$total + (float)$total * (float)$user_setting->company_tax / 100;
        $tax_price = (float)$total * (float)$user_setting->company_tax / 100;
        $data = array(
            'total_details_price' => number_format($total_details_price, 0, '.', ',') . $cur_currency->symbol,
            'total_with_tax' => number_format($total_with_tax, 0, '.', ',') . $cur_currency->symbol,
            'tax_price' => number_format($tax_price, 0, '.', ',') . $cur_currency->symbol, 
        );
        return $data;
    }

    public function send_invoice(Request $request) {
        $customer = Customer::with(['phone_number_details', 'email_details'])->find($request->customer);
        // print_r($request->all());exit;
        $descriptions = $request->descriptions;
        $new_descriptions = array();
        foreach ($descriptions as $description) {
            $cur_id = $description['currency'];
            $symbol = Currency::find($cur_id)->first()->symbol;
            $new_descriptions[] = array(
                'title' => $description['title'],
                'total' => $description['total'],
                'description' => $description['description'],
                'price' => $description['price'],
                'quantity' => $description['quantity'],
                'currency' => $symbol
            );
        }
        $data = [
            'date' => date("F j, Y", strtotime($request->date)),
            'total_details_price' => $request->total_details_price,
            'company_logo' => isset($request->company_logo) ? $request->company_logo : "",
            'bank_name' => $request->bank_name,
            'bank_country' => $request->bank_country,
            'bank_city' => $request->bank_city,
            'bank_address' => $request->bank_address,
            'bank_iban' => $request->bank_iban,
            'bank_swift' => $request->bank_swift,
            'descriptions' => $new_descriptions,
            'customer' => $customer,
            'signature' => $request->signature,
            'total_with_tax' => $request->total_with_tax,
            'tax_price' => $request->tax_price,
        ];
        $invoice = new TransactionInvoice();
        $invoice->date = strval(date("F j, Y", strtotime($request->date)));
        $invoice->user_id = Auth::user()->id;
        $invoice->bank_name = $request->bank_name;
        $invoice->bank_country = $request->bank_country;
        $invoice->bank_city = $request->bank_city;
        $invoice->bank_iban = $request->bank_iban;
        $invoice->bank_swift = $request->bank_swift;
        $invoice->descriptions = $request->descriptions;
        $invoice->signature = $request->signature;
        $invoice->customer = $request->customer;
        $invoice->save();

        $pdf = \PDF::loadView('pdf_view', $data);
        $path = 'Invoices';
        $pdf->save(public_path($path).'//' . $invoice->id . '.pdf');
        return $invoice->id;
    }
}
