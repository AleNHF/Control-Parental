<?php

namespace App\Http\Controllers\Api;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\BaseController as BaseController;

class TutorController extends BaseController
{
    /**
     * This endpoint is for update profile of user tutor
     */
    public function update(Request $request)
    {      
        $user = User::findOrFail(Auth::user()->id);
        $tutor = Tutor::where(['user_id' => $user->id])->first();

        if ($user == null) {
            return $this->sendError('Unauthorized.', 401);
        }

        $input = $request->all();
        
        if ($user->type == "T") {
            $url = null;

            if ($request->hasFile('profilePhoto')) {
                $folder = "public/profiles";
                //If the user enters, it is to update their profile photo, deleting the one they had
                if ($tutor->profilePhoto != null) {          
                    Storage::delete($tutor->profilePhoto);
                }
                $imagen = $request->file('profilePhoto')->store($folder);   
                $url = Storage::url($imagen);
                $tutor->profilePhoto = $url;
            }

            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->password = bcrypt($input['password']);
            $user->save();

            $tutor->name = $input['name'];
            $tutor->lastname = $input['lastname'];
            $tutor->birthDay = $input['birthDay'];
            $tutor->gender = $input['gender'];
            $tutor->phoneNumber = $input['phoneNumber'];
            $tutor->save();

            $result = [
                'user' => $user, 
                'tutor' => $tutor
            ];

            return $this->sendResponse($result, "You have successfully updates your profile.");
        } else {
            return $this->sendError("Forbidden.", 403);
        }
    }

    /**
     * This endpoint is for get children of a tutor
     */
    public function getChildren()
    {
        $user = User::find(Auth::user()->id);
        $tutor = Tutor::where(['user_id' => $user->id])->first();

        $children = $tutor->children;
        $countChildren = $children->count();

        foreach ($children as $child) {
            $child->profilePhoto = 'https://picsum.photos/200';
        }

        $result = [
            'totalRecords' => $countChildren,
            $children,        
        ];

        if ($children != null) {
            return $this->sendResponse($result, "This is a list of your children");
        } else {
            return $this->sendError('No Content.', 204);
        }
    }
}