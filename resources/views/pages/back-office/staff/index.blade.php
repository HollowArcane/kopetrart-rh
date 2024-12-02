@extends('layouts.app')

@section('content')
    <h1>Liste des Employés</h1>

    @php
        $session = session();
    @endphp

    @include('includes.message')

    @if ($session->get('role') == 3 && isset($id_besoins))
        <p><a href="{{ route('annonce.create', $id_besoins) }}" class="btn btn-warning">Faire une Annonce</a></p>
    @endif
        <table class="table">
            <thead>
                <tr>
                    <th> Nº Matricule </th>
                    <th> Personnel </th>
                    <th> Poste </th>
                    <th> Département </th>
                    <th> Date d'Embauche </th>
                    <th> Contrat </th>
                    <th> Actions </th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $staff)
                    <tr>
                        <td> <a class="btn btn-secondary text-primary" href="/staff/{{ $staff->id }}"> {{ $staff->id }} </a> </td>
                        <td> {{ $staff->first_name }} {{ $staff->last_name }} </td>
                        <td> {{ $staff->staff_position }} </td>
                        <td> {{ $staff->department }} </td>
                        <td> {{ $staff->d_date_contract_start }} </td>
                        <td> {{ $staff->staff_contract }} </td>

                        <td>
                            <a href="/staff/{{ $staff->id }}/contract" class="btn btn-secondary"  data-mdb-tooltip-init title="Renouveler Contrat"> <i class="fas fa-file-contract"></i> </a>
                            <a href="/staff/{{ $staff->id }}/contract/pdf" class="btn btn-secondary text-danger"  data-mdb-tooltip-init title="Exporter PDF"> <i class="fa fa-file-pdf"></i> </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
