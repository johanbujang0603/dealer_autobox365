<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\SystemLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Auth;

class TransactionDocumentController extends Controller
{
    //
    public function upload(Request $request)
    {
        if ($file = $request->file('file')) {

            $path = "documents/transactions";
            $document = new Document();
            $document->original_name = $file->getClientOriginalName();

            $ext = strtolower($file->getClientOriginalExtension());
            $size = $file->getSize();
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $upload_file_name);
            $document->upload_path = $path . '/' . $upload_file_name;
            $document->kinds = 'lead';
            $document->parent_model = Transaction::class;
            // $document->parent_model_id = $id;
            $document->user_id = Auth::user()->id;
            $document->tags = '';
            $document->type = $ext;
            $document->size = $size;
            $document->filesize = $size;
            $document->save();
            return array(
                'status' => 'success',
                'file' => $document
            );
        }
    }
}
