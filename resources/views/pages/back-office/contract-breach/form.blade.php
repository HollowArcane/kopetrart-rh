@extends('layouts.app')

@php
    use App\Models\Staff\Staff;
@endphp

@section('content')
<div class="container mt-5">

    @include('includes.message')

    <x-modal.main name="modal" type="info" title="Information">
        <p> <strong> Congé Disponible: </strong > <span id="vacation-available"> 0 </span> Jours </p>
        <h5 class="mb-3"> Les montants suivant seront versés à {{ $staff->first_name }} {{ $staff->last_name }} </h5>
        <table class="w-100">
            <tbody>
                <tr>
                    <th> Indemnité légale de licenciement: </th>
                    <td align="right"> <span id="salary-contract"> 0 </span> </td>
                </tr>
                <tr>
                    <th> Indemnité compensatrice de préavis: </th>
                    <td align="right"> <span id="salary-notice" class="{{ $type == 1 /* Démission */ ? 'text-danger': '' }}"> 0 </span> </td>
                </tr>
                <tr>
                    <th> Indemnité de congés payés: </th>
                    <td align="right"> <span id="salary-vacation"> 0 </span ></td>
                </tr>
                <tr>
                    <th> Montant additionel: </th>
                    <td align="right"> <span id="salary-more"> 0 </span ></td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table class="w-100">
            <tbody>
                <tr>
                    <th> TOTAL: </th>
                    <td align="right"> <span id="salary-total"> 0 </span> </td>
                </tr>
            </tbody>
        </table>
    </x-modal.main>

    <x-form.main id="form" :action="$form_action" :method="$form_method">
        <div class="info-section">
            <h1 class="mb-3" align="center"> {{ [ 'Démission' ,'Licenciement', 'Mise à la Retraite', 'Rupture Conventionelle' ][$type - 1] }} </h1>
            <p>
                <strong> Employé: </strong>
                {{ $staff->first_name }} {{ $staff->last_name }}
            </p>
            <p>
                <strong> Ancienneté: </strong>
                {{ Staff::format_seniority($staff, 'an', 'mois', 'jour') }}
            </p>
            <p>
                <strong> Durée de Préavis: </strong>
                {{ str_replace('mon', 'mois', str_replace('day', 'jour', $notice->duration ?? 'Non défini')) }}
            </p>

            <input type="hidden" name="id-contract-breach-type" value="{{ $type }}">
            <input type="hidden" name="salary" value="0">

            <x-form.input name="date-source" type="date" :value="(new \DateTime())->format('Y-m-d')"> Date de Déclaration </x-form.input>
            <x-form.input name="date-expected" type="date" :value="(new \DateTime($notice->date_notice))->format('Y-m-d')"> Date Prévue </x-form.input>

            @if ($type == 4 /* Rupture Conventionelle */)
                <x-form.input name="salary-additional" type="number" value="0"> Montant Additionel </x-form.input>
            @endif

            <x-form.textarea name="comment"> Motif </x-form.textarea>
            @if ($type == 2 /* Licenciement */)
                <x-form.checkbox name="comment-status" value="danger"> Motif Grave </x-form.checkbox>
            @endif

            {{-- <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur itaque maiores adipisci sunt libero vel reiciendis, eum eaque voluptatibus architecto ipsa quae, odio error, quas tenetur cumque totam fuga ratione. </p> --}}
            <x-modal.button id="modal-show" target="modal"> Valider </x-modal.button>
        </div>
    </x-form.main>
</div>


<script>

const staff = <?= json_encode($staff) ?>;
const button = document.getElementById('modal-show');
const form = document.getElementById('form');
const btnSubmit = document.getElementById('modal-btn-submit');

const salaryAdditional = document.getElementById('salary-additional');
const dateSource = document.getElementById('date-source');
const dateExpected = document.getElementById('date-expected');
const commentStatus = document.getElementById('comment-status-danger');

function formatNumber(num, decimalSeparator = ',', throusandSeparator = ' ', decimalDigits = 0)
{
    if(isNaN(num))
    { throw new Error('Invalid number'); }

    const parts = Number(num).toFixed(decimalDigits).split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, throusandSeparator);
    return parts.join(decimalSeparator);
}

function fetchSalary()
{
    const url = `/staff/${ staff.id }/contract-breach/{{ $type }}/salary/${ dateSource.value }/${ dateExpected.value }/${ (commentStatus !== null && commentStatus.checked) ? commentStatus.value: 'normal' }`;
    fetch(url).then(response => response.json())
    .then(data => {
        let total = 0;
        for(let key in data)
        {
            const id = key.replace('_', '-');
            const span = document.getElementById(id);
            span.textContent = formatNumber(Math.abs(data[key]));
            total += data[key];
        }

        if(salaryAdditional !== null)
        {
            total += parseFloat(salaryAdditional.value);
            const span = document.getElementById('salary-more');
            span.textContent = formatNumber(salaryAdditional.value);
        }

        const s0 = document.getElementById('vacation-available');
        s0.textContent = data.vacation_available;

        const span = document.getElementById('salary-total');
        span.textContent = formatNumber(total);
        document.querySelector('input[name="salary"]').value = total;
    })
    .catch(error => {
        alert(error.message);
        console.error(error);
    })
}

button.onclick = fetchSalary;

dateSource.onchange = e => {
    dateExpected.value = dateSource.value;
}
btnSubmit.onclick = e => form.submit();

</script>

@endsection
