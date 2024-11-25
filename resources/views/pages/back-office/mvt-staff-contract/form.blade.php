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
        <h3>Détails du Contrat</h3>

        <x-form.select name="type_contrat" :options="$contracts"> Contrat </x-form.select>
        <x-form.input name="date_entree" type="date" :value="(new \DateTime($staff->d_date_contract_end ?? 'now'))->format('Y-m-d')"> Date de Début de Contrat </x-form.input>
        <x-form.input name="periode" type="date" :value="(new \DateTime($staff->d_date_contract_end ?? 'now'))->add(new \DateInterval('P6M'))->format('Y-m-d')"> Date de Fin de Contrat </x-form.input>

        <x-form.select name="position" :options="$positions"> Poste </x-form.select>
        <x-form.select name="department" :options="$departments"> Département </x-form.select>
        <x-form.input name="salaire_propose" type="number"> Salaire </x-form.input>

        <label for="notes_sup">Commentaires</label>
        <textarea name="notes_sup" id="notes_sup" rows="4" placeholder="Ajoutez vos commentaires sur le renouvellement..." class="form-control"></textarea>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary"> Valider </button>
    </div>
    </x-form.main>
</div>

<script>
    const contractInput = document.querySelector('#type_contrat');
    const endDateInput = document.querySelector('#periode');

    function disableSelector()
    {
        if(contractInput.value == 2) // CDI
        { endDateInput.setAttribute('disabled', ''); }
        else
        { endDateInput.removeAttribute('disabled'); }
    }

    contractInput.onchange = disableSelector;
    disableSelector();
</script>

@endsection
