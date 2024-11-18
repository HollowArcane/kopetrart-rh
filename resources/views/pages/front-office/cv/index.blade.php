@extends('templates.home')

@php
$state_color = [
    'Approuvé' => 'success',
    'Rejeté' => 'danger'
];
@endphp

@section('content')
<h1> <x-button.add href="{{ $url }}/create"></x-button.add> Liste des Dépôts de Dossiers </h1>

@if (!isset($cvs) || empty($cvs))
    <h3 class="text-muted"> Commencez par déposer un dossier </h3>
@else
    <br>

    @foreach ($cvs as $cv)
    <div class="card shadow-3 mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <p> Poste Demandé: <b> {{ $cv->poste }} </b></p>
                <p> {{ $cv->date_deposit }} </p>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-2">
                    <p> <span class="badge badge-{{ $state_color[$cv->state] ?? 'info' }}">{{ $cv->state }}</span> </p>
                </div>
                <div class="col-sm-12 col-md-10">
                    <x-progress-bar :failed="$cv->state == 'Rejeté'" :progress="$cv->progression" />
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
