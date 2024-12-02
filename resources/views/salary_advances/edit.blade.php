@extends('layouts.app')

@section('content')
    <h1>Edit Salary Advance</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('salary_advances.update', $salaryAdvance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="id_staff">Staff</label>
            <select name="id_staff" id="id_staff" class="form-control" required>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ $staff->id == $salaryAdvance->id_staff ? 'selected' : '' }}>
                        {{ $staff->first_name }} {{ $staff->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date_advance">Date Advance</label>
            <input type="date" name="date_advance" id="date_advance" class="form-control" value="{{ $salaryAdvance->date_advance }}" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ $salaryAdvance->amount }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection