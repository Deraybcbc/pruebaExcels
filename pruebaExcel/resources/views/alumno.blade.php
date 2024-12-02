<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
<h1>Archivo</h1>
<div class="input-group">
    <form action="{{route('excel.import')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="file" name="file" accept=".xlsx, .xls, .csv" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
        <button type="submit">Importar</button>
    </form>
</div>
</body>

</html>
