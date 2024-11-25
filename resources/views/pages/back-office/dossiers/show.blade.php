@extends('layouts.app')

@section('content')
    <h1>Détails du Dossiers</h1>

    <div>
        <strong>Id:</strong> {{ $dossiers->id }}
    </div>
    <div>
        <strong>Candidat:</strong> {{ $dossiers->candidat }}
    </div>
    <div>
        <strong>Email:</strong> {{ $dossiers->email }}
    </div>
    <div>
        <strong>Id_besoin_poste:</strong> {{ $dossiers->id_besoin_poste }}
    </div>
    <div>
        <strong>Date_reception:</strong> {{ $dossiers->date_reception }}
    </div>
    <div>
        <strong>Statut:</strong> {{ $dossiers->statut }}
    </div>
    <div>
        <strong>Cv:</strong> {{ $dossiers->cv }}
    </div>
    <div>
        <strong>Lettre_motivation:</strong> {{ $dossiers->lettre_motivation }}
    </div>

    <a href="{{ route('dossiers.index') }}" class="btn btn-secondary">Retour</a>

@endsection
