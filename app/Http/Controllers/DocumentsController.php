<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Location;
use App\Models\SystemLog;
use App\User;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use Storage;
use Response;
use Carbon\Carbon;

class DocumentsController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = 'Documents';
        $documents = Document::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();
        $documents = Document::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->paginate(12);
        
        return view('documents.index', compact('page_title', 'documents'));
        
    }
    public function create()
    {
        # code...
        $page_title = 'Create Document';
        $inventory_query  = Inventory::with([
            'vehicle_details',
            'location_details',
            'equipment_details',
            'trim_details',
            'serie_details',
            'generation_details',
            'user_details',
            'make_details',
            'model_details',
            'location_details',
            'user_details',
            'photo_details'
        ])->where('user_id', Auth::user()->id);
        $customers = Customer::where('user_id', Auth::user()->id)->get();
        $leads = Lead::where('user_id', Auth::user()->id)->where('is_converted', 0)->get();
        $locations = Location::where('user_id', Auth::user()->id)->get();
        $inventories = $inventory_query->get();
        $users = User::where('dealer_id', Auth::user()->id)->get();

        return view('documents.create', compact('page_title', 'inventories', 'customers', 'leads', 'locations', 'users'));
    }

    public function delete($id) {
        $doc = Document::find($id);
        if ($doc) {
            $doc->is_deleted = 1;
            $log = new SystemLog;
            $log->user_id = Auth::user()->id;
            $log->action = 'deleted';
            $log->category = 'documents';
            $log->model = Document::class;
            $log->model_id = $doc->id;
            $log->save();
            $doc->save();
            return redirect()->back()->with('success', 'You removed a listing successfully!');
        } else {
            abort(404);
        }
    }

    public function upload(Request $request)
    {
        if ($file = $request->file('file')) {

            $leads = $request->get('leads');
            $customers = $request->get('customers');
            $inventories = $request->get('inventories');
            $locations = $request->get('locations');
            $users = $request->get('users');
            $description = $request->get('description');

            $path = "documents/";
            $document = new Document();
            $document->original_name = $file->getClientOriginalName();

            $ext = strtolower($file->getClientOriginalExtension());
            $size = $file->getSize();
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $upload_file_name);
            $document->upload_path = $path . '/' . $upload_file_name;
            $document->kinds = '';
            $document->parent_inventories = isset($inventories)? $inventories : null;
            $document->parent_customers = isset($customers)? $customers : null;
            $document->parent_users = isset($users)? $users : null;
            $document->parent_leads = isset($leads)? $leads : null;
            $document->parent_locations = isset($locations)? $locations : null;
            $document->description = isset($description)? $description : null;
            $document->user_id = Auth::user()->id;
            $document->tags = '';
            $document->type = $ext;
            $document->size = $size;
            $document->save();

            $log = new SystemLog();
            $log->user_id = Auth::user()->id;
            $log->action =  'uploaded';
            $log->category = 'documents';
            $log->model = Document::class;
            $log->model_id = $document->id;
            $log->save();
        }
    }
    public function download($id)
    {
        $document = Document::find($id);
        $count_download = $document->count_download;
        $document->count_download = $count_download ? $count_download + 1 : 1;
        $document->save();
        $log = new SystemLog();
        $log->action = 'downloaded';
        $log->category = 'document';
        $log->model = Document::class;
        $log->model_id = $id;
        $log->user_id = Auth::user()->id;
        $log->save();
        return response()->download(($document->upload_path));
    }
}
