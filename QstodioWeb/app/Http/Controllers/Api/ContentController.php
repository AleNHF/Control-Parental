<?php

namespace App\Http\Controllers\Api;

use App\Models\Calls;
use App\Models\Children;
use App\Models\Content;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;

class ContentController extends BaseController
{
    /**
     * This endpoint is for content list 
     */
    public function index()
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
     * This endpoint is for get content for a child
     */
    public function contentXKid($idkid)
    {
        $content = Content::where([
            'children_id' => $idkid,
            'capture' => false
        ])->get();

        if (isset($content)) {
            return $this->sendResponse($content, "Content for this child.");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This endpoint is for get content for a child
     */
    public function contentCaptureXKid($idkid)
    {
        $content = Content::where([
            'children_id' => $idkid,
            'capture' => true
        ])->get();

        if (isset($content)) {
            return $this->sendResponse($content, "Content for this child.");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This endpoint is for get children's content
     */
    public function contentXChildren()
    {
        $user = Auth::user()->id;

        if ($user != null) {
            $collection = new Collection();

            foreach ($user->tutor->children as $child) {
                $contentList = new Collection();
                $kid = Children::where(['id' => $child->id])->first();
                $contents = Content::where(['children_id' => $kid->id])->get();

                foreach ($contents as $content) {
                    $contentList->push($content);
                }

                $collection->push([
                    'child' => $kid,
                    'contents' => $contentList
                ]);
            }

            return $this->sendResponse($collection, "Children's contents.");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This is for create content
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'children_id' => 'required|numeric|exists:children,id',
            'date' => 'required|string',
            'path' => 'required|string',
            'url' => 'required|string',
            'contentData' => 'required|string',
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $kid = Children::findOrFail($request->children_id);
        $user = User::find($kid->tutor->id);
        $content = Content::create($request->all());

        //event(new NotificationContenidoEvent($user, $contenido));

        return $this->sendResponse($content, "Content was created successfully.");
    }

    /**
     * This is for update content
     */

    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);

        if (isset($content)) {
            $content->update($request->all());

            return $this->sendResponse($content, "Content was updated successfully.");
        }

        return $this->sendError('Not Exists.', 403);
    }

    /**
     * This endpoint is for show call
     */
    public function show($id)
    {
        $content = Content::findOrFail($id);

        if (isset($content)) {
            return $this->sendResponse($content, "Content found");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This endpoint is for delete the content
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (isset($user)) {
            $content = Content::findOrFail($id);
            if (isset($content)) {
                $content->delete();
                return $this->sendResponse($content, "Content has deleted.");
            }

            return $this->sendError("Not Found.", 404);
        }

        return $this->sendError("Unauthorized", 401);
    }
}