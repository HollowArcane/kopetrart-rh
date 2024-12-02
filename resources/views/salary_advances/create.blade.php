@extends('layouts.app')

@section('content')
    <h1>Add New Salary Advance</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('salary_advances.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="id_staff">Staff</label>
            <select name="id_staff" id="id_staff" class="form-control" required>
                <option value="" disabled selected>Select a staff member</option>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date_advance">Date Advance</label>
            <input type="date" name="date_advance" id="date_advance" class="form-control" value="{{ old('date_advance') }}" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Add</button>
    </form>
@endsection