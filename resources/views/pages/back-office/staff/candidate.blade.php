@extends('layouts.app')

@section('content')
    <h1>Liste des Candidats</h1>

    @php
        $session = session();
    @endphp

    @include('includes.message')

        <table class="table">
            <thead>
                <tr>
                    <th> Personnel </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $staff)
                    <tr>
                        <td> {{ $staff->first_name }} {{ $staff->last_name }} </td>

                        <td>
                            <a href="/staff/{{ $staff->id }}/contract" class="btn btn-secondary" data-mdb-tooltip-init title="Contrat d'Essai"> <i class="fas fa-file-contract"></i> </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
