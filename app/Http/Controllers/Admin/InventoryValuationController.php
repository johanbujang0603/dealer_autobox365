<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ImportValuationData;
use Excel;
use Auth;
use App\Imports\InventoryImport;

class InventoryValuationController extends Controller
{
    //
    public function index()
    {
        # code...
        return view('admin.inventories.valuation');
    }

    public function upload()
    {
        return view('admin.inventories.valuation.upload');
    }

    public function uploadExcel(Request $request)
    {
        $rows = [];
        $preview_rows = array();
        if ($file = $request->file('file')) {
            $path = "valuation/imports";
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $upload_file_name);
            $rows = Excel::toArray(new InventoryImport, $path . '/' . $upload_file_name);

            for ($i = 0; $i < 30; $i++) {
                if (isset($rows[0][$i])) {
                    $tempRow = [];
                    foreach ($rows[0][$i] as $key => $value) {
                        $tempRow[] = array('value' => $value);
                    }
                    $preview_rows[] = $tempRow;
                }
            }
        }
        return array('file_path' =>  $path . '/' . $upload_file_name, 'preview_rows' => $preview_rows);
        # code...
    }
    public function start_import(Request $request)
    {
        $userId = Auth::user()->id;

        ImportValuationData::dispatch($request->all()) //->delay(now()->addSeconds(1))
            ->onQueue('valuation_data_import');
    }
}
