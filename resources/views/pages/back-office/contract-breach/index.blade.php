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
                                <a class="btn btn-secondary text-primary" tooltip="Mettre à Jour" href="/contract-breach/{{ $staff->id }}"> <i class="fa-solid fa-check"></i> </a>
                            @elseif ($staff->date_target == null && in_array(session('role'), [1 /* PDG */, 3 /* RE */]) && session('role') != $staff->id_role /* only target can validate operation */)
                                <a class="btn btn-secondary text-primary" tooltip="Valider" href="/contract-breach/{{ $staff->id }}"> <i class="fa-solid fa-check"></i> </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
