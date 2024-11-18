@extends('layouts.app')

@section('content')
    <h1>Détails du Contrat</h1>

    <div>
        <strong>Id:</strong> {{ $contrat->id }}
    </div>
    <div>
        <strong>Libelle:</strong> {{ $contrat->libelle }}
    </div>
    <div>
        <strong>Minmois:</strong> {{ $contrat->minmois }}
    </div>
    <div>
        <strong>Maxmois:</strong> {{ $contrat->maxmois }}
    </div>

    <a href="{{ route('contrat.index') }}" class="btn btn-secondary">Retour</a>

@endsection
