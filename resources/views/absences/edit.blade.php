@extends('layouts.app')

@section('content')
    <h1>Modifier une Absence</h1>
    
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('absences.update', $absence->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id_staff">Staff</label>
            <select class="form-control" id="id_staff" name="id_staff" required>
                <option value="" disabled>Choisir un membre du personnel</option>
                @foreach($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ $absence->id_staff == $staff->id ? 'selected' : '' }}>
                        {{ $staff->first_name }} - {{ $staff->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="number_day_absence">Nombre de jours</label>
            <input type="number" class="form-control" id="number_day_absence" name="number_day_absence" value="{{ $absence->number_day_absence }}" step="0.5" min="0.5" required>
        </div>
        <div class="form-group">
            <label for="date_absence">Date</label>
            <input type="date" class="form-control" id="date_absence" name="date_absence" value="{{ $absence->date_absence }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
@endsection
