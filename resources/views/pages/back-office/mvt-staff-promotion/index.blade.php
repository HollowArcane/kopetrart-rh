@extends('layouts.app')

@section('content')
    <h1>Liste des Promotions</h1>

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
                    <th> Date de Promotion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $staff)
                    <tr>
                        <td> <a class="btn btn-secondary text-primary" href="/staff/{{ $staff->id_staff }}"> {{ $staff->id_staff }} </a> </td>
                        <td> {{ $staff->first_name }} {{ $staff->last_name }} </td>
                        <td> {{ $staff->staff_position }} </td>
                        <td> {{ $staff->department }} </td>
                        <td> {{ $staff->date }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
