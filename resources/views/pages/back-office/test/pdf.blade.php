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
        <p><strong>Durée Totale:</strong> {{ $totalDuration }} minutes</p>
    </div>

    {{-- Test Parts --}}
    @foreach($test->parts as $index => $part)
    <div class="part">
        <h2>Partie {{ $index + 1 }}</h2>
        <p><strong>Durée:</strong> {{ $part->duration }} minutes</p>
        <p>{{ $part->content }}</p>
    </div>
    @endforeach

    {{-- Global Evaluation Criteria --}}
    <div class="criteria">
        <h2>Critères D'Évaluation Globaux</h2>
        @foreach($test->criteria as $criterion)
            <p>
                {{ $criterion->label }}:
                {{ number_format(($criterion->coefficient / $totalCoefficient) * 100, 1) }}%
            </p>
        @endforeach
    </div>

    {{-- Points of Interest --}}
    <div class="points">
        <h2>Points d'Intérêt Particuliers</h2>
        @foreach($test->points as $point)
            <div class="importance-point">
                <p>
                    {{ $point->label }}: <span style="color: {{ $importanceColors[$point->importance->label] ?? '#000' }}">{{ $point->importance->label }}</span>
                </p>
            </div>
        @endforeach
    </div>
</body>
</html>
