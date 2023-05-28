<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('upload.image.index') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-control">
            <label for="">Subir imagen</label>
            <input type="file" id="foto" name="foto">
            <button class="btn btn-success">Subir</button>
        </div>
    </form>
    @foreach ($imagenes as $img)
        <img src="{{Storage::disk('s3')->url($img->url)}}" class="card-img-top" alt="Descripción de la imagen" width=100 height=100>
                    
    @endforeach
</body>

</html>
