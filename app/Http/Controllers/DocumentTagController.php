<?php

namespace App\Http\Controllers;

use App\Models\DocumentTag;
use Illuminate\Http\Request;
use Auth;
use App\Models\SystemLog;

class DocumentTagController extends Controller
{
    //

    public function index()
    {
        # code...
        $page_title = 'Document Tag';
        $tags = DocumentTag::where('user_id', Auth::user()->id)->get();
        return view('documents.tags.index', compact('tags', 'page_title'));
    }

    public function create()
    {
        # code...
        $page_title = 'Create Tag';
        return view("documents.tags.create", compact('page_title'));
    }

    public function edit($id)
    {
        $page_title = 'Edit Tag';
        $tag = DocumentTag::find($id);
        return view('documents.tags.edit', compact('tag', 'page_title'));
    }
    public function delete($id)
    {
        $tag = DocumentTag::find($id);
        $tag->delete();
        // return view('documents.tags.edit', compact('tag'));
        return redirect()->back()->with('success', 'You removed a document successfully!');
    }
    public function save(Request $request)
    {
        if (isset($request->id)) {
            $tag = DocumentTag::find($request->id);
            $isCreate = false;
        } else {
            $tag = new DocumentTag;
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
        $log->category = 'inventory_tag';
        $log->model = DocumentTag::class;
        $log->model_id = $tag->id;
        $log->save();

        if ($request->ajax()) {
            return $tag;
        }
        if (isset($request->id)) {
            return redirect(route('documents.tags'))->with('success', "You updated a inventory tag successfully!");
        }
        return redirect()->back()->with('success', "You saved a inventory tag successfully!");
    }
}
