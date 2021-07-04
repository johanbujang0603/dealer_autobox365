<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadTag;
use App\Models\SystemLog;
use Auth;

class LeadTagController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = "Lead Tags";
        $tags = LeadTag::where('user_id', Auth::user()->id)->get();
        return view('leads.tags.index', compact('tags', 'page_title'));
    }

    public function create()
    {
        # code...
        $page_title = "Create Lead Tags";
        return view("leads.tags.create", compact('page_title'));
    }

    public function edit($id)
    {
        $page_title = 'Edit Lead Tag';
        $tag = LeadTag::find($id);
        return view('leads.tags.edit', compact('tag', 'page_title'));
    }
    public function save(Request $request)
    {
        if (isset($request->id)) {
            $tag = LeadTag::find($request->id);
            $isCreate = false;
        } else {
            $tag = new LeadTag;
            $tag->user_id = Auth::user()->id;
            $isCreate = true;
        }

        $tag->tag_name = $request->tag_name;
        $tag->color = $request->color;

        $tag->save();
        $log = new SystemLog;
        $log->user_id = Auth::user()->id;
        if ($isCreate) {
            $log->action = 'created';
        } else {
            $log->action = 'updated';
        }
        $log->category = 'lead_tag';
        $log->model = LeadTag::class;
        $log->model_id = $tag->id;
        $log->save();

        if ($request->ajax()) {
            return $tag;
        }
        if (isset($request->id)) {
            return redirect(route('leads.tags'))->with('success', "You updated a lead tag successfully!");
        }
        return redirect()->back()->with('success', "You saved a lead tag successfully!");
    }
}
