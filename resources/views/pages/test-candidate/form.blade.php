@extends('layouts.app')

@section('aside')
<x-navbar.main :active="$template_url"></x-navbar.main>
@endsection

@php
    $candidate = old('candidate') ?? $item?->candidate ?? '';
    $test = old('test') ?? $item?->test ?? '';
    $date_reception = old('date-reception') ?? $item?->date_received ?? date('Y-m-d');
@endphp

@section('content')
<x-form.main :action="$form_action" :method="$form_method">
    <h2> {{ $form_title }} </h2>

    <x-form.select name="id-candidate" :options="$candidates" :value="$candidate"> Candidat </x-form.select>
    <x-form.select name="id-test" :options="$tests" :value="$test"> Test </x-form.select>
    <x-form.input name="file" type="file"> Fichier </x-form.input>
    <x-form.input name="date-reception" type="date" :value="$date_reception"> Date de Réception </x-form.input>

    <x-button.primary> Valider </x-button.primary>
</x-form.main>
@endsection
