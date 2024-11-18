@extends('layouts.app')

@section('content')
    <h1>Détails du Promotion</h1>

    <div>
        <strong>Id:</strong> {{ $promotion->id }}
    </div>
    <div>
        <strong>Id_employe:</strong> {{ $promotion->id_employe }}
    </div>
    <div>
        <strong>Id_poste:</strong> {{ $promotion->id_poste }}
    </div>
    <div>
        <strong>Date:</strong> {{ $promotion->date }}
    </div>
    <div>
        <strong>Salaire:</strong> {{ $promotion->salaire }}
    </div>
    <div>
        <strong>Status:</strong> {{ $promotion->status }}
    </div>

    <a href="{{ route('promotion.index') }}" class="btn btn-secondary">Retour</a>

@endsection
