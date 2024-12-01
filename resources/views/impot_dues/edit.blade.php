@extends('layouts.app')

@section('content')
    <h1>Edit Impot Due</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('impot_dues.update', $impotDue->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="id_staff">Staff</label>
            <select name="id_staff" id="id_staff" class="form-control" required>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}" 
                        {{ $impotDue->id_staff == $staff->id ? 'selected' : '' }}>
                        {{ $staff->first_name }} {{ $staff->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date_due">Due Date</label>
            <input type="date" name="date_due" id="date_due" class="form-control" 
                value="{{ $impotDue->date_due }}" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" 
                value="{{ $impotDue->amount }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection