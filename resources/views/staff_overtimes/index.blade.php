@extends('layouts.app')

@section('content')
    <h1>Registres mensuels des heures supplémentaires</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <a href="{{ route('staff_overtimes.create') }}" class="btn btn-primary">Ajouter une heure supp</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Personnel</th>
                <th>Début</th>
                <th>Fin</th>
                <th>8 premières heures</th>
                <th>12 dernières heures</th>
                <th>week-end</th>
                <th>jours fériés</th>
                <th>heures supplémentaires</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monthlyOvertimes as $overtime)
                <tr>
                    <td>STF-00{{ $overtime->id_staff }}</td>
                    <td>{{ $overtime->period_start }}</td>
                    <td>{{ $overtime->period_end }}</td>
                    <td>{{ $overtime->total_first_8_hours }}</td>
                    <td>{{ $overtime->total_last_12_hours }}</td>
                    <td>{{ $overtime->total_weekend }}</td>
                    <td>{{ $overtime->total_holiday }}</td>
                    <td>{{ $overtime->total_overtime }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Aucun enregistrement trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection