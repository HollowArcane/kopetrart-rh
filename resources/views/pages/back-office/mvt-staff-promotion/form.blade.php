@extends('layouts.app')

@section('content')
<div class="container">

    @include('includes.message')

    <x-form.main :action="$form_action" :method="$form_method">
    <div class="form-group">
        <div class="info-section">
        <h3>Informations sur l'employé</h3>

        <p>
            <strong> Employé: </strong>
            {{ $staff->first_name }} {{ $staff->last_name }}
        </p>


        <div class="info-section">
        <h3> Détails de Promotion </h3>

        <x-form.input name="date" type="date" :value="(new \DateTime($staff->d_date_contract_end ?? 'now'))->format('Y-m-d')"> Date de Promotion </x-form.input>

        <x-form.select name="id-staff-position" :options="$positions"> Poste </x-form.select>
        <x-form.select name="id-department" :options="$departments"> Département </x-form.select>
        <x-form.input name="salary" type="number"> Salaire </x-form.input>

        <label for="notes_sup">Commentaires</label>
        <textarea name="notes_sup" id="notes_sup" rows="4" placeholder="Ajoutez vos commentaires sur le renouvellement..." class="form-control"></textarea>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary"> Valider </button>
    </div>
    </x-form.main>
</div>
@endsection
