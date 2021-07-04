<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerTag;
use App\Models\SystemLog;
use Auth;

class CustomerTagController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = 'Customer Tags';
        $tags = CustomerTag::get();
        return view('customers.tags.index', compact('tags', 'page_title'));
    }

    public function create()
    {
        # code...
        $page_title = 'Create Tag';
        return view("customers.tags.create", compact('page_title'));
    }

    public function edit($id)
    {
        $page_title = 'Edit Tag';
        $tag = CustomerTag::find($id);
        return view('customers.tags.edit', compact('tag', 'page_title'));
    }
    public function save(Request $request)
    {
        if (isset($request->id)) {
            $tag = CustomerTag::find($request->id);
            $isCreate = false;
        } else {
            $tag = new CustomerTag;
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
        $log->category = 'customer_tag';
        $log->model = CustomerTag::class;
        $log->model_id = $tag->id;
        $log->save();

        if ($request->ajax()) {
            return $tag;
        }
        if (isset($request->id)) {
            return redirect(route('customers.tags'))->with('success', "You updated a customer tag successfully!");
        }
        return redirect()->back()->with('success', "You saved a customer tag successfully!");
    }
    public function delete($id)
    {
        $tag = CustomerTag::find($id);
        $tag->delete();
        return redirect()->back()->with('success', "You removed a customer tag successfully!");
    }
}
