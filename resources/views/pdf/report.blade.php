<!DOCTYPE html>
<html>

<head>
    <title>Hi</title>
</head>

<body>
    <h1>{{ $title }}</h1>
    <p>{{ $content }}</p>
    <p>{{ $date }}</p>
    <p>{{ $signatory1 }}</p>
    <p>{{ $signature1 }}</p>
    <img src="{{ storage_path('app/public/' . $signature1) }}" alt="" srcset="">
</body>

</html>
