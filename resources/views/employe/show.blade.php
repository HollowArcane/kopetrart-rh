@extends('layouts.app')

@section('content')
    <h1>Détails du Employe</h1>

    <div>
        <strong>Id:</strong> {{ $employe->id }}
    </div>
    <div>
        <strong>Id_departement:</strong> {{ $employe->id_departement }}
    </div>
    <div>
        <strong>Id_poste:</strong> {{ $employe->id_poste }}
    </div>
    <div>
        <strong>Date:</strong> {{ $employe->date }}
    </div>
    <div>
        <strong>Id_contrat:</strong> {{ $employe->id_contrat }}
    </div>

    <a href="{{ route('employe.index') }}" class="btn btn-secondary">Retour</a>

@endsection
