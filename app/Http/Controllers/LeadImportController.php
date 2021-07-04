<?php

namespace App\Http\Controllers;

use App\Imports\LeadImport;
use App\Jobs\ImportLeads;
use Illuminate\Http\Request;
use romanzipp\QueueMonitor\Models\Monitor;
use Excel;

class LeadImportController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = "Leads Import";
        $running_import_job = Monitor::ordered()->where('queue', 'leads_import')
            ->whereNotNull('progress')
            ->whereNull('finished_at')->first();
        return view('leads.import', compact('running_import_job', 'page_title'));
    }
    public function uploadImportFile(Request $request)
    {
        $rows = [];
        $preview_rows = array();
        if ($file = $request->file('file')) {
            $path = "leads/imports/excel";
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $upload_file_name);
            $rows = Excel::toArray(new LeadImport, $path . '/' . $upload_file_name);

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
        // return $request->all();
    }
    public function uploadStart(Request $request)
    {
        $userId = Auth::user()->id;

        ImportLeads::dispatch($request->all(), Auth::user()) //->delay(now()->addSeconds(1))
            ->onQueue('inventories_import_' . $userId);
    }
}
