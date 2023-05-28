<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imagenes = Image::all();
        return view('uploadImage', compact('imagenes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->hasFile('foto')) {

            $request->validate([
                'foto' => 'required|image'
            ]);

            $foto = $request->file('foto');

            $url = Storage::disk('s3')->put('Contenido', $foto, 'public');
            $image = Image::create([
                'url' => $url
            ]);
            //  return $url;
            // ProcessVerificarFoto::dispatch($image->id);
        }
        //return "no es imagen";
        return redirect('/');
    }
    public function controlImagen(Request $request)
    {



        if ($request->hasFile('foto')) {

            $client = new RekognitionClient([
                'version' => 'latest',
                'region' => env('AWS_DEFAULT_REGION'),
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            /* OBTENIENDO LA IMG */
            $image = fopen($request->file('foto')->getPathName(), 'r');
            $bytes = fread($image, $request->file('foto')->getSize());


            /* CONSULTANDO EL SERVICIO DE AWS */

            $result = $client->detectModerationLabels([
                'Image' => ['Bytes' => $bytes],
                'MinConfidence' => 51

            ]);
            $labels = $result['ModerationLabels'] ?? []; //verifica si retorna etiquetas de descripción de la foto de contenido inapropiado, si la foto no tuviera contenido inapropiado no devolvería nada

            if ($labels != []) {
                $carpeta = "Contenido"; //Carpeta en la que se guardará en S3

                $url = Storage::disk('s3')->put($carpeta, $request->foto, 'public'); //Subimos la imagen a S3, retorna la url de S3
                $image = Image::create([ //Creamos la imagen, podemos extraer el contenido de array labels, para ver que contenido inapropiado tiene la imagen
                    'url' => $url
                ]);
            }
            return response()->json(['labels' => $labels]);//retorna las etiquetas de la imagen ya enviada a analizar a Rekognition
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
