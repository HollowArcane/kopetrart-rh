@extends('layouts.app')

@section('content')
    <h1>Add New Impot Due</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('impot_dues.store') }}" method="POST">
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
            <label for="date_due">Due Date</label>
            <input type="date" name="date_due" id="date_due" class="form-control" value="{{ old('date_due') }}" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Add</button>
    </form>
@endsection