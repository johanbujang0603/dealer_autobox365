<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomersController extends Controller
{
    //
    public function status(){
        $statuses = InventoryStatus::all();
        return view('admin.inventories.status', compact('statuses'));
    }
    public function statuscreate(){
        $statuses = InventoryStatus::all();
        return view('admin.inventories.createstatus', compact('statuses'));
    }
    public function editstatus($id){
        $status = InventoryStatus::find($id);
        return view('admin.inventories.editstatus', compact('status'));
    }
    public function deletestatus($id){
        $status = InventoryStatus::find($id);
        if($status){
            $status->delete();
            return redirect()->back()->with('success', "You removed a inventory status successfully!");
        }
        else{
            abort(404);
        }

    }

    public function statussave(Request $request){
        if(isset($request->id)){
            $status = InventoryStatus::find($request->id);
        }
        else{
            $status = new InventoryStatus;
        }

        $status->status_name = $request->status_name;
        $status->color = $request->color;
        $status->user_id = 0;
        $status->save();
        if(isset($request->id)){
            return redirect(route('admin.inventories.status'))->with('success', "You updated a inventory status successfully!");
        }
        return redirect()->back()->with('success', "You saved a inventory status successfully!");
    }


    public function tags(){
        $tags = InventoryTag::all();
        return view('admin.inventories.tags', compact('tags'));
    }

    public function createtags (){
        return view('admin.inventories.createtags');
    }

    public function savetags (Request $request){
        if(isset($request->id)){
            $tag = InventoryTag::find($request->id);
        }
        else{
            $tag = new InventoryTag;
        }

        $tag->tag_name = $request->tag_name;
        $tag->color = $request->color;
        $tag->user_id = 0;
        $tag->save();
        if(isset($request->id)){
            return redirect(route('admin.inventories.tags'))->with('success', "You updated a inventory tag successfully!");
        }
        return redirect()->back()->with('success', "You saved a inventory tag successfully!");
    }

    public function edittags($id){
        $tag = InventoryTag::find($id);
        return view('admin.inventories.edittag', compact('tag'));
    }
}
