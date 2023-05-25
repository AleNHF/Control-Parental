<?php

namespace App\Http\Controllers\Api;

use App\Models\Children;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;

class ChildrenController extends BaseController
{
    /**
     * This endpoint is for add children into the app tutor
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'birthDay' => 'required|date',
            'gender' => 'required|string|max:1',
            'alias' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        $user = User::find(Auth::user()->id);
        $tutor = Tutor::where(['user_id' => $user->id])->first();

        $kid = Children::create([
            'name' => $input['name'],
            'lastname' => $input['lastname'],
            'alias' => $input['alias'],
            'gender' => $input['gender'],
            'birthDay' => $input['birthDay'],
            'tutor_id' => $tutor->id
        ]);

        return $this->sendResponse($kid, "You have registered your kid successfully.");
    }
}
