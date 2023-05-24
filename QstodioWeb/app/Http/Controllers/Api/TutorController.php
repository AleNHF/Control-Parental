<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function update(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        if ($user->tipo == "T") {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'sexo' => 'required|string|max:1',
                'celular' => 'required|numeric',
                'email' => 'required|string|email|max:255',
                'password' => 'required|confirmed|string|min:8',
                'habilitado' => 'nullable|boolean',
                'foto' => 'mimes:jpg,jpeg,bmp,png|max:2048|nullable'
            ]);
            $url = null;
            $user = User::findOrFail($request->user()->id);
            if ($request->hasFile('foto')) {
                $folder = "public/perfil";
                if ($user->foto != null) { //si entra es para actualizar su foto borrando la que tenía, si no tenía entonces no entra
                    Storage::delete($user->foto);
                }
                $imagen = $request->file('foto')->store($folder); //Storage::disk('local')->put($folder, $request->image, 'public');
                $url = Storage::url($imagen);
                $user->foto = $url;
            }
            $user->name = $validateData['name'];
            $user->apellido = $validateData['apellido'];
            $user->fecha_nacimiento = $validateData['fecha_nacimiento'];
            $user->sexo = $validateData['sexo'];
            $user->celular = $validateData['celular'];
            $user->email = $validateData['email'];
            $user->password = bcrypt($validateData['password']);
            // $user->foto = $url;
            $user->save();

            $tutor = $user->tutor;
            if (isset($request->habilitado)) {
                $tutor->habilitado = $request->habilitado;
            }

            $tutor->save();
            //return $t;
            //token = $user->createToken('auth_token')->plainTextToken;
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
    public function hijosTutor()
    {

        $user = User::find(Auth::user()->id);

        if ($user->tipo == 'T') {

            return response()->json([
                'message' => 'Hijos del tutor',
                'data' => $user->tutor
            ]);
        } else {
            return response()->json([
                'message' => 'Debes ser tutor para ver tus hijos'
            ], 404);
        }
    }
    public function children_turor(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $tutor_id = Tutor::where('user_id', $user->id)->first()->id;
        // return $tutor_id;
        $tokens_hijo = Token::where('id_tutor', $tutor_id)
            ->where('estado', 1)
            ->pluck('id_hijo');

        $hijos = Hijo::whereIn('id', $tokens_hijo)->get();
        $CantBoys = $hijos->count();
        foreach ($hijos as $hijo) {
            $hijo->image = 'https://picsum.photos/200';
        }
        return response()->json([
            'message' => 'Hijos del tutor',
            'data' => $hijos,
            'cantChildren' => $CantBoys
        ]);
        // return $hijos;
        // if ($user->tipo == 'T') {
        //     $h = new Collection();
        //     foreach ($user->tutor->hijos as $hijo) {
        //         $hijo = Hijo::all()->find($hijo->id);
        //         $hijo->user;
        //         $h->push($hijo);
        //     }
        //     return response()->json([
        //         'message' => 'Hijos del tutor',
        //         'data' => $h
        //     ]);
        // } else {
        //     return response()->json([
        //         'message' => 'Debes ser tutor para ver tus hijos'
        //     ], 404);
        // }
    }
    public function destroy()
    {
        $user = User::all()->find(Auth::user()->id);
        if ($user->tipo == "T") {
            $user->delete();
            return response()->json([
                'message' => "Se eliminó su exitosamente",
                'data' => $user
            ]);
        } else {

            return response()->json([
                'message' => "Debe ser un usuario Tutor para eliminar"
            ]);
        }
    }
    public function update_perfil(Request $request)
    {
        // return $request;
        $rules = [
            'name' => 'required',
            'lastName' => 'required',
            'cellPhone' => 'required|numeric',
            'email' => 'required|email',
        ];
        $messages = [
            'name.required' => 'El nombre es requerido',
            'lastName.required' => 'El apellido es requerido.',
            'cellPhone.required' => 'El celular es requerido.',
            'cellPhone.numeric' => 'El celular debe ser de tipo numérico.',
            'email.required' => 'El email es requerido.',
            'email.email' => 'Se requiere un Correo Electronico.',
        ];
        $this->validate($request, $rules, $messages);

        $user = User::findOrFail($request->user()->id);
        // return $user;
        $user->name = $request['name'];
        $user->apellido = $request['lastName'];
        $user->celular = $request['cellPhone'];
        $user->email = $request['email'];
        $user->save();

        //return $t;
        //token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Datos de usuario actualizado exitosamente',
            'data' => ['user' => $user]
        ]);
    }
}