<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\Customer;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Auth;

class CustomerDocumentsController extends Controller
{
    //
    public function upload(Request $request, $id)
    {
        if ($file = $request->file('file')) {

            $path = "documents/customers/$id";
            $document = new Document();
            $document->original_name = $file->getClientOriginalName();

            $ext = strtolower($file->getClientOriginalExtension());
            $size = $file->getSize();
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $upload_file_name);
            $document->upload_path = $path . '/' . $upload_file_name;
            $document->kinds = 'customer';
            $document->parent_customers = $id;
            $document->user_id = Auth::user()->id;
            $document->tags = '';
            $document->type = $ext;
            $document->size = $size;
            $document->save();

            $log = new SystemLog();
            $log->user_id = Auth::user()->id;
            $log->action =  'document_created';
            $log->category = 'customers';
            $log->model = Customer::class;
            $log->model_id = $id;
            $log->save();
        }
    }

    public function load($id)
    {
        $documents = Document::where('parent_customers', "LIKE", "%" . $id . "%")->get();
        $html_str = "";

        foreach ($documents as $document) {


            $html_str .= "<li class=\"flex flex-wrap items-center mt-5\">
                            <div class=\"mr-3\">
                                <i class=\"{$document->icon} icon-2x text-{$document->icon_color}-300 top-0\"></i>
                            </div>

                            <div class=\"\">
                                <div class=\"font-medium\"> $document->original_name </div>
                                <ul
                                    class=\"\">
                                    <li class=\"text-theme-15\">Size: " . formatSizeUnits($document->size) . "</li>
                                    <li class=\"\">By <a href=\"javascript:;\" class=\"text-theme-1\"> {$document->user_details->full_name}</a></li>
                                </ul>
                            </div>

                            <div class=\"ml-5\">
                                <div class=\"list-icons\">
                                    <a href=\"" . asset($document->upload_path) . "\" download class=\"text-theme-4\"><i class=\"icon-download\"></i></a>
                                </div>
                            </div>
                        </li>";
        }
        return $html_str;
    }
}
