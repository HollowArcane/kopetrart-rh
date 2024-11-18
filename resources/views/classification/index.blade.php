@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Curriculum Vitae Additional Data</h2>

    <!-- Display success message if present -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table of Denormalized CV Entries -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Candidat Name</th>
                <th>Poste</th>
                <th>Date Depot Dossier</th>
                <th>Interests</th>
                <th>Qualities</th>
                <th>Education</th>
                <th>Experiences</th>
                <th>Adequate</th>
                <th>Potentiel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($denormalizedCvs as $cv)
                <tr>
                    <td>{{ $cv->id }}</td>
                    <td>{{ $cv->candidat_name }}</td>
                    <td>{{ $cv->poste }}</td>
                    <td>{{ $cv->date_depot_dossier }}</td>
                    <td>
                        @foreach($cv->interests as $interest)
                            {{ $interest->label }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($cv->qualities as $quality)
                            {{ $quality->label }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($cv->educations as $education)
                            {{ $education->label }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($cv->experiences as $experience)
                            {{ $experience->label }} ({{ number_format($experience->month_duration, 0) }} months)@if(!$loop->last), @endif
                        @endforeach
                    </td>

                    <td>
                        <span class="{{ $cv->adequate ? 'badge badge-success' : 'badge badge-danger' }}"> {{ $cv->adequate ? 'Yes' : 'No' }} </span>
                    </td>
                    <td>
                        <span class="{{ $cv->potentiel ? 'badge badge-success' : 'badge badge-danger' }}"> {{ $cv->potentiel ? 'Yes' : 'No' }} </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
