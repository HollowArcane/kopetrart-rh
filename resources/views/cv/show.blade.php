@extends('layouts.app')

@section('content')
    <h1>Détails du Cv</h1>

    <div>
        <strong>Id:</strong> {{ $cv->id }}
    </div>
    <div>
        <strong>Id_dossier:</strong> {{ $cv->id_dossier }}
    </div>
    <div>
        <strong>Status:</strong> {{ $cv->status }}
    </div>
    <div>
        <strong>Notes:</strong> {{ $cv->notes }}
    </div>
    <div>
        <strong>Test:</strong> {{ $cv->test }}
    </div>
    <div>
        <strong>Entretien:</strong> {{ $cv->entretien }}
    </div>
    <div>
        <strong>Comparaisonvalider:</strong> {{ $cv->comparaisonvalider }}
    </div>

    <a href="{{ route('cv.index') }}" class="btn btn-secondary">Retour</a>

@endsection
