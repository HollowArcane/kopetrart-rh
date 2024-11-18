<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title> {{ env('APP_NAME', 'Kopetrart RH') }} </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: left;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .part {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f5f5f5;
        }
        .criteria {
            margin-top: 20px;
        }
        .points {
            margin-top: 20px;
        }
        .importance-point {
            margin: 5px 0;
        }
        h1 { font-size: 24px; }
        h2 { font-size: 20px; }
        h3 { font-size: 18px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 align="center">{{ $test->title }}</h1>
        <p><strong>Objectif:</strong> {{ $test->goal }}</p>
    </div>

    {{-- Test Parts --}}
    @foreach($test->parts as $i => $part)
    <div class="part">
        <h2>Partie {{ $i + 1 }}</h2>
        <p>{{ $part->content }}</p>

        <h4> Réponse: </h4>
        <p>{{ trim($response[$i]) }}</p>
    </div>
    @endforeach

</body>
</html>
