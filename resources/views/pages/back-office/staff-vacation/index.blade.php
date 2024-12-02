@extends('layouts.app')

@section('content')
    <h1>
        @if (session('role') == 3 /* RE */)
            <x-button.add href="/staff-vacation/create"></x-button.add>
        @endif
        Liste des Demandes de Congé
    </h1>

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
                    <th> Date Début </th>
                    <th> Date Fin (Inclusif) </th>
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
                        <td> {{ $staff->date_start }} </td>
                        <td> {{ $staff->date_end }} </td>

                        <td>
                            @if ($staff->date_validated == null)
                                @if (in_array(session('role'), [1 /* PDG */, 3 /* RE */]))
                                <x-button.delete tooltip="Annuler" href="/staff-vacation/{{ $staff->id }}"></x-button.delete>
                                @endif
                                @if (session('role') == 1 /* PDG */)
                                    <a data-mdb-tooltip-init title="Valider" href="/staff-vacation/{{ $staff->id }}" class="btn btn-secondary text-primary"> <i class="fa-solid fa-check"></i> </a>
                                @endif
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
