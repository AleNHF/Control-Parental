<?php

namespace App\Http\Controllers\Api;

use App\Models\Calls;
use App\Models\Children;
use App\Models\Contacts;
use App\Models\Content;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;

class ContentController extends BaseController
{
    /**
     * This endpoint is for content list 
     */
    public function index($id) 
    {
        $contents = Content::all();
        
        return $this->sendResponse($contents, "List of contents.");
    }

    /**
     * This endpoint is for get contents of children's tutor
     */
    public function quantity_of_content()
    {
        $user_id = Auth::user()->id;
        $tutor_id = Tutor::where(['user_id' => $user_id])->pluck('id');
        $children_id = Children::whereIn('tutor_id', $tutor_id)->pluck('id');
        $contents = Content::whereIn('children_id', $children_id)->get('url');

        $result = [
            'total_records' => $contents->count('url'),
            'contents' => $contents
        ];

        return $this->sendResponse($result, "List of contents for children's tutor");
    }

    /**
     * This endpoint is for update call
     */
    public function update(Request $request, $id)
    {
        $call = Calls::findOrFail($id);
        
        if (isset($call)) {
            $call->update($request->all());

            return $this->sendResponse($call, "Call updated successfully.");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This endpoint is for show call
     */
    public function show($id)
    {
        $call = Calls::findOrFail($id);

        if (isset($call)) {
            return $this->sendResponse($call, "Contact found");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This endpoint is for delete
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (isset($user)) {
            $call = Calls::findOrFail($id);
            if (isset($call)) {
                $call->delete();
                return $this->sendResponse($call, "Contact has deleted.");
            }

            return $this->sendError("Not Found.", 404);
        }

        return $this->sendError("Unauthorized", 401);
    }
}
