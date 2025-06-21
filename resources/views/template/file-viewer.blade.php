<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Isarman Setegr Bengkulu - File Viewer</title>
    <link rel="stylesheet" href="{{ asset('css/file-viewer.css') }}">
</head>
<body>
    @if($fileType == 'pdf')
    <iframe style="border-style:none;" src="{{ asset('storage/' . $filePath) }}" width="100%" height="100%"></iframe>
    @else
    <img src="{{ asset('storage/' . $filePath) }}" alt="File Preview" style="width: 100%; height: auto; max-height: 100vh; object-fit: contain;">
    @endif
</body>
</html>