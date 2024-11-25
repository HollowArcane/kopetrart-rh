@extends('layouts.app')

@section('aside')
<x-navbar.main :active="$template_url"></x-navbar.main>
@endsection

@php
    $candidate = old('candidate') ?? $item?->candidate ?? '';
    $test = old('test') ?? $item?->test ?? '';
    $date_validation = old('date-validation') ?? $item?->date_validation ?? date('Y-m-d');

    $old_requirements = old('requirements') ?? $item?->requirements ?? [];
    $old_criteria = old('criteria') ?? [];
    $old_points = old('points') ?? [];
@endphp

@section('content')
<x-form.main :action="$form_action" :method="$form_method">
    <h2> {{ $form_title }} </h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $e)
            <li> {{ $e }} </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="mb-3">
        <a class="btn btn-secondary text-primary" href=""> Télécharger le dossier du candidat </a>
        <a class="btn btn-secondary text-primary" href="/test-candidate-file/cv/{{ $item }}"> Télécharger le test </a>
        <a class="btn btn-secondary text-primary" target="_blank" href="/test/pdf/{{ $test_item }}"> Voir le questionaire </a>
    </div>

    <h5> Requierements </h5>
    <div class="bordered rounded mb-3">
    @foreach ($test_requirements as $i => $test_requirement)
        <div class="mb-2">
            <input class="form-check-input" type="checkbox" id="requirement-{{ $i }}" name="requirements[{{ $i }}]" {{ isset($old_requirements[$i]) ? 'checked': '' }} >
            <label class="form-check-label" for="requirement-{{ $i }}">{{ $test_requirement }}</label>
        </div>
    @endforeach
    </div>

    <h5> Critères d'Évaluation </h5>
    <div class="bordered rounded mb-3">
    @foreach ($test_criteria as $test_criterion)
        <div class="mb-2">
            <label class="form-label" for="criterion-{{ $i }}">{{ $test_criterion->label }}</label>
            <input class="form-range" type="range" id="criterion-{{ $i }}" min="0" max="5" step="1" name="criteria[{{ $test_criterion->id }}]" value={{ $old_criteria[$test_criterion->id] ?? '' }}>
        </div>
    @endforeach
    </div>

    <h5> Attention Particulières </h5>
    <div class="bordered rounded mb-3">
        @foreach ($test_points as $test_point)
        <div class="mb-2">
            <input class="form-check-input" type="checkbox" id="point-{{ $i }}" name="points[{{ $test_point->id }}]" {{ isset($old_points[$test_point->id]) ? 'checked': '' }}>
            <label class="form-check-label" for="point-{{ $i }}">{{ $test_point->label }}</label>
        </div>
    @endforeach
    </div>

    <x-form.input name="date-validation" type="date" :value="$date_validation"> Date de Validation </x-form.input>

    <x-button.primary> Valider </x-button.primary>
</x-form.main>
@endsection
