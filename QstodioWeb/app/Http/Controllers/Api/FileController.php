<?php

namespace App\Http\Controllers\Api;

use App\Models\Children;
use App\Models\File;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;

class FileController extends BaseController
{
    /**
     * This endpoint is for files list into the app tutor
     */
    public function getFilesXKid($id) 
    {
        $kid = Children::findOrFail($id);
        $tutor = Tutor::findOrFail($kid->tutor_id);

        if (Auth::user()->id == $tutor->user_id) {
            $files = $kid->files;

            return $this->sendResponse($files, "Files list of your kid.");
        } else {
            return $this->sendError("No Content.", 204);
        }       
    }

    /**
     * This endpoint is for store files
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'createDate' => 'required|date',
            'path' => 'required|string|max:500',
            'children_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $file = new File();
        $file->createDate = $request->createDate;
        $file->path = $request->path;
        $file->children_id = $request->children_id;
        $file->save();

        return $this->sendResponse($file, "File saved successfully.");
    }

    /**
     * This endpoint is for update files
     */
    public function update(Request $request, $id)
    {
        $file = File::findOrFail($id);
        
        if (isset($file)) {
            $file->update($request->all());

            return $this->sendResponse($file, "File updated successfully.");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This endpoint is for show file
     */
    public function show($id)
    {
        $file = File::findOrFail($id);

        if (isset($file)) {
            return $this->sendResponse($file, "File found");
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
            $file = File::findOrFail($id);
            if (isset($file)) {
                $file->delete();
                return $this->sendResponse($file, "File has deleted.");
            }

            return $this->sendError("Not Found.", 404);
        }

        return $this->sendError("Unauthorized", 401);
    }
}
