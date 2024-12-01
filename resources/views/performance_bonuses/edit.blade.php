@extends('layouts.app')

@section('content')
    <h1>Edit Performance Bonus</h1>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('performance_bonuses.update', $performanceBonus->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="id_staff">Staff</label>
            <select name="id_staff" id="id_staff" class="form-control" required>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}" 
                        {{ $performanceBonus->id_staff == $staff->id ? 'selected' : '' }}>
                        {{ $staff->first_name }} {{ $staff->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date_bonus">Date</label>
            <input type="date" name="date_bonus" id="date_bonus" class="form-control" 
                value="{{ $performanceBonus->date_bonus }}" required>
        </div>

        <div class="form-group">
            <label for="performance">Performance (%)</label>
            <input type="number" step="0.01" name="performance" id="performance" class="form-control" 
                value="{{ $performanceBonus->performance }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection