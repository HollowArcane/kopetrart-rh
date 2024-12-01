@extends('layouts.app')

@section('content')
    <h1>Liste des Absences</h1>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <a href="{{ route('absences.create') }}" class="btn btn-primary">Ajouter une Absence</a>
    <table class="table">
        <thead>
            <tr>
                <th>Staff</th>
                <th>Nombre de jours</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absences as $absence)
                <tr>
                    <td>{{ $absence->staff->first_name ?? 'N/A' }}</td>
                    <td>{{ $absence->number_day_absence }}</td>
                    <td>{{ $absence->date_absence }}</td>
                    <td>
                        <a href="{{ route('absences.edit', $absence->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('absences.destroy', $absence->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Confirmer la suppression?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection