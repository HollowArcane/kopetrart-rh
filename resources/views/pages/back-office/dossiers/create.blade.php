@extends('layouts.app')

@section('content')

<form class="mt-5" action="{{ route('dossiers.store') }}" method="POST" enctype="multipart/form-data">
    <h1>Réception du dossier de candidat</h1>

    @csrf

    {{-- ID caché --}}
    <input type="hidden" id="id" name="id">

    <x-form.input name="candidat" required=""> Nom du Candidat </x-form.input>
    <x-form.input name="email" type="email" required=""> Email </x-form.input>
    <x-form.input name="date-birth" type="date" required=""> Date de Naissance </x-form.input>
    <x-form.select name="id_besoin_poste" :options="$besoin_poste" required=""> Besoin Poste </x-form.select>
    <x-form.input name="cv" type="file" required="" accept=".pdf,.doc,.docx"> CV </x-form.input>
    <x-form.input name="lettre_motivation" type="file" required="" accept=".pdf,.doc,.docx"> Lettre de Motivation </x-form.input>

    {{-- Date de réception automatique --}}
    <input type="hidden" id="date_reception" name="date_reception" value="{{ date('Y-m-d') }}">

    {{-- Statut initial --}}
    <input type="hidden" id="statut" name="statut" value="Nouveau">

    <div class="actions">
        <button class="btn btn-secondary" type="button" class="cancel-btn" onclick="window.history.back()"> Annuler </button>
        <button class="btn btn-primary" type="submit"> Enregistrer </button>
    </div>
</form>
@endsection
