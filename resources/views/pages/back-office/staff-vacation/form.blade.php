@extends('layouts.app')

@php
    use App\Models\Staff\Staff;
@endphp

@section('content')
<div class="container mt-5">

    @include('includes.message')

    <x-form.main id="form" :action="$form_action" :method="$form_method">
        <div class="info-section">
            <h1 class="mb-3" align="center"> Demande de Congé </h1>
            <p>
                <strong> Jours de congé disponible: </strong>
                <span id="vacation-left"> 0 </span>
            </p>

            <x-form.select name="id-staff" :options="$staffs"> Employé </x-form.select>
            <x-form.input name="date-start" type="date" :value="(new \DateTime())->format('Y-m-d')"> Date de Début de Congé </x-form.input>
            <x-form.input name="date-end" type="date" :value="(new \DateTime())->format('Y-m-d')"> Date de Fin de Congé (Inclusif) </x-form.input>
            <x-form.textarea name="comment"> Motif </x-form.textarea>

            <x-button.primary> Valider </x-button.primary>
        </div>
    </x-form.main>
</div>

<script>
const dateStart = document.getElementById('date-start');
const idStaff = document.getElementById('id-staff');
const vacationLeft = document.getElementById('vacation-left');

function getVacationLeft()
{
    const id = idStaff.value;
    const today = dateStart.value;
    fetch(`/staff/${id}/vacation/${today}`)
        .then(data => data.json())
        .then(json => vacationLeft.textContent = json.day_vacation_left)
        .catch(e => {
            alert(e.message);
            console.error(e);
        });
}

idStaff.onchange = getVacationLeft;
dateStart.onchange = getVacationLeft;
getVacationLeft();

</script>

@endsection
