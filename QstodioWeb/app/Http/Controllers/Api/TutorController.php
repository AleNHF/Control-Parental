<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorController extends Controller
{
    /**
     * This endpoint is for update profile of user tutor
     */
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $tutor = Tutor::findOrFail($user->tutor_id);
        
        if ($user->tipo == "T") {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'birthDay' => 'required|date',
                'gender' => 'required|string|max:1',
                'phoneNumber' => 'required|numeric',
                'email' => 'required|string|email|max:255',
                'password' => 'required|confirmed|string|min:8',
                'profilePhoto' => 'mimes:jpg,jpeg,bmp,png|max:2048|nullable'
            ]);

            $url = null;

            if ($request->hasFile('profilePhoto')) {
                $folder = "public/profiles";
                if ($tutor->profilePhoto != null) {          //si entra es para actualizar su profilePhoto borrando la que tenía, si no tenía entonces no entra
                    Storage::delete($tutor->profilePhoto);
                }
                $imagen = $request->file('profilePhoto')->store($folder);   
                $url = Storage::url($imagen);
                $tutor->profilePhoto = $url;
            }
            $tutor->name = $validateData['name'];
            $tutor->lastname = $validateData['lastname'];
            $tutor->birthDay = $validateData['birthDay'];
            $tutor->gender = $validateData['gender'];
            $tutor->phoneNumber = $validateData['phoneNumber'];
            $tutor->save();
            $user->name = $validateData['name'];
            $user->email = $validateData['email'];
            $user->password = bcrypt($validateData['password']);
            $user->save();

            return response()->json([
                'message' => 'Datos de usuario actualizado exitosamente',
                'data' => ['user' => $user, 'tutor' => $tutor]
            ]);
        } else {
            return response()->json([
                'message' => 'Debe ser un usuario tutor para actualizar los datos'
            ], 404);
        }
    }

    /**
     * This endpoint is for get children of a tutor
     */
    public function getChildren()
    {
        $user = User::find(Auth::user()->id);
        $tutor = Tutor::find($user->tutor_id);

        $children = $tutor->children;
        $countChildren = $children->count();

        foreach ($children as $child) {
            $child->profilePhoto = 'https://picsum.photos/200';
        }

        if ($children != null) {
            return response()->json([
                'message' => 'Listado de hijos',
                'data' => $children,
                'totalRecords' => $countChildren
            ], 200);
        } else {
            return response()->json([
                'message' => 'Este tutor no tiene ningún hijo registrado'
            ], 404);
        }
    }
}