<?php

namespace App\Http\Controllers;

use App\Models\LeadNote;
use App\Models\SystemLog;
use App\Models\Lead;
use Illuminate\Http\Request;
use Auth;

class LeadNotesController extends Controller
{
    //
    public function add(Request $request, $id)
    {
        # code...
        $note = new LeadNote();
        $note->lead_id = $id;
        $note->user_id = Auth::user()->id;
        $note->notes = $request->data;
        $note->save();
        $log = new SystemLog();
        $log->user_id = Auth::user()->id;
        $log->action =  'note_created';
        $log->category = 'leads';
        $log->model = Lead::class;
        $log->model_id = $id;
        $log->save();
        return $note;
    }
    public function load($id)
    {
        $notes = LeadNote::where('lead_id', $id)->get();
        $html_str = '';
        foreach ($notes as $note) {
            $html_str .= '<div class="box px-5 py-3 ml-4 flex-1 zoom-in mb-5">
                            <div class="flex items-center">
                                <img src="' . $note->user_details->profile_image_src . '" class="rounded-full mr-5" width="36" height="36" alt="">
                                <div>
                                    <div class="flex items-center">
                                        <a href="#" class="font-medium mr-5">' . ucwords($note->user_details->full_name) . '</a>
                                        <span class="text-xs text-gray-600">' .
                    \Carbon\Carbon::parse($note->created_at)->diffForHumans()
                    . '</span>
                                    </div>

                                    ' . $note->notes . '
                                </div>
                        </div>
                    </div>';
        }
        return $html_str;
    }
}
