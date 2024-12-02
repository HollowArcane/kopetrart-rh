@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($staffCompensation) ? 'Edit' : 'Add' }} Staff Compensation</h1>
        <form action="{{ isset($staffCompensation) ? route('staff_compensations.update', $staffCompensation->id) : route('staff_compensations.store') }}" method="POST">
            @csrf
            @if (isset($staffCompensation)) @method('PUT') @endif

            <div class="form-group">
                <label for="id_staff">Staff</label>
                <select name="id_staff" id="id_staff" class="form-control" required>
                    <option value="">Select Staff</option>
                    @foreach ($staffs as $staff)
                        <option value="{{ $staff->id }}" {{ old('id_staff', $staffCompensation->id_staff ?? '') == $staff->id ? 'selected' : '' }}>
                            {{ $staff->first_name }} {{ $staff->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="motif">Motif</label>
                <input type="text" name="motif" id="motif" class="form-control" value="{{ old('motif', $staffCompensation->motif ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="date_compensation">Date</label>
                <input type="date" name="date_compensation" id="date_compensation" class="form-control" value="{{ old('date_compensation', $staffCompensation->date_compensation ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ old('amount', $staffCompensation->amount ?? '') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($staffCompensation) ? 'Update' : 'Add' }}</button>
        </form>
    </div>
@endsection
