@extends('layouts.app')

@section('content')
    <h1>Liste des Contrats</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Contrat</th>
                <th>Date d expiration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contrat as $liste_contrat)
                <tr>
                    <td>{{ $liste_contrat->candidat}}</td>
                    <td>{{ $liste_contrat->contrat }}</td>
                    <td>{{ $liste_contrat->date_expiration }}</td>
                    <td>
                        <a href="{{ route('contrat.showRenewalForm', $liste_contrat->id) }}" class="btn btn-warning">Renouveler</a>
                        <a href="/contrat/pdf/{{ $liste_contrat->id }}" class="btn btn-secondary text-primary"> <i class="fa fa-file-pdf"></i> </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
