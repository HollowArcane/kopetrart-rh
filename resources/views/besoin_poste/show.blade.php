@extends('layouts.app')

@section('content')
    <h1>Détails du Besoin_poste</h1>

    <div>
        <strong>Id:</strong> {{ $besoin_poste->id }}
    </div>
    <div>
        <strong>Id_poste:</strong> {{ $besoin_poste->id_poste }}
    </div>
    <div>
        <strong>Id_genre:</strong> {{ $besoin_poste->id_genre }}
    </div>
    <div>
        <strong>Id_contrat:</strong> {{ $besoin_poste->id_contrat }}
    </div>
    <div>
        <strong>Agemin:</strong> {{ $besoin_poste->agemin }}
    </div>
    <div>
        <strong>Finvalidite:</strong> {{ $besoin_poste->finvalidite }}
    </div>
    <div>
        <strong>Priorite:</strong> {{ $besoin_poste->priorite }}
    </div>
    <div>
        <strong>Info_sup:</strong> {{ $besoin_poste->info_sup }}
    </div>
    <div>
        <strong>Personneltrouver:</strong> {{ $besoin_poste->personneltrouver }}
    </div>

    <a href="{{ route('besoin_poste.index') }}" class="btn btn-secondary">Retour</a>

@endsection
