<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadStatus;
use Auth;

class LeadStatusController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = 'Leads Status';
        $statuses  = LeadStatus::where('user_id', Auth::user()->id)->get();
        return view("leads.status.index", compact('statuses', 'page_title'));
    }

    public function edit($id)
    {
        # code...
        $page_title = 'Edit Lead Status';
        $status = LeadStatus::find($id);
        return view('leads.status.edit', compact('status', 'page_title'));
    }
    public function create()
    {
        # code...
        $page_title = 'Leads Status Create';
        return view('Leads.status.create', compact('page_title'));
    }

    public function save(Request $request)
    {
        if (isset($request->id)) {
            $status = LeadStatus::find($request->id);
        } else {
            $status = new LeadStatus;
            $status->user_id = Auth::user()->id;
        }

        $status->status_name = $request->status_name;
        $status->color = $request->color;
        // $status->user_id = 0;
        $status->save();
        if ($request->ajax()) {
            return $status;
        }
        if (isset($request->id)) {
            return redirect(route('leads.status'))->with('success', "You updated a lead status successfully!");
        }
        return redirect()->back()->with('success', "You saved a lead status successfully!");
    }

    public function delete($id)
    {
        $status = LeadStatus::find($id);
        if ($status) {
            $status->delete();
            return redirect()->back()->with('success', "You removed a lead status successfully!");
        } else {
            abort(404);
        }
    }
}
