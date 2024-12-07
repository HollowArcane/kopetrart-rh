@extends('layouts.app')

@section('content')
    <h1>Liste des Ruptures de Contrat</h1>

    @php
        $session = session();
    @endphp

    @include('includes.message')
        <table class="table">
            <thead>
                <tr>
                    <th> Nº Matricule </th>
                    <th> Personnel </th>
                    <th> Poste </th>
                    <th> Département </th>
                    <th> Date d'Embauche </th>
                    <th> Contrat </th>
                    <th> Date Prévue à la Rupture </th>
                    <th> Actions </th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $staff)
                    <x-modal.main title="Dossier de Rupture" :footer="false" name="modal-{{ $staff->id }}">
                        <a target="_blank" href="/contract-breach/{{ $staff->id }}/pdf" class="btn btn-block btn-secondary text-primary"> Certificat de Travail </a>
                        <a target="_blank" href="/contract-breach/{{ $staff->id }}/pdf-detail" class="btn btn-block btn-secondary text-primary"> Attestation Pôle Emploi </a>
                        <a target="_blank" href="/payroll/export-pdf/{{ $staff->id_staff }}/{{ $staff->date_validated }}" class="btn btn-block btn-secondary text-primary"> Fiche de Paie </a>
                        <a class="btn btn-block btn-primary" href="/contract-breach/{{ $staff->id }}"> Mettre à Jour </a>
                    </x-modal.main>
                    <tr>
                        <td> <a class="btn btn-secondary text-primary" href="/staff/{{ $staff->id_staff }}"> {{ $staff->id_staff }} </a> </td>
                        <td> {{ $staff->first_name }} {{ $staff->last_name }} </td>
                        <td> {{ $staff->staff_position }} </td>
                        <td> {{ $staff->department }} </td>
                        <td> {{ $staff->d_date_contract_start }} </td>
                        <td> {{ $staff->staff_contract }} </td>
                        <td> {{ $staff->date_validated }} </td>

                        <td>
                            @if (!$staff->is_validated && session('role') == $staff->id_role /* only owner can cancel operation */)
                                <x-button.delete tooltip="Annuler" href="/contract-breach/{{ $staff->id }}"></x-button.delete>
                            @elseif (!$staff->is_validated && $staff->date_target != null && (new \DateTime())->format('Y-m-d') == $staff->date_validated && session('role') == 2 /* RH */)
                                <x-modal.button type="secondary" tooltip="Mettre à Jour" :target="'modal-'.$staff->id"> <i class="fa-solid fa-check"></i> </x-modal.button>
                            @elseif ($staff->date_target == null && in_array(session('role'), [1 /* PDG */, 3 /* RE */]) && session('role') != $staff->id_role /* only target can validate operation */)
                                <a class="btn btn-secondary text-primary" tooltip="Valider" href="/contract-breach/{{ $staff->id }}"> <i class="fa-solid fa-check"></i> </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
