@extends('layouts.app')

@section('content')
    <h1>Salary Advances</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('salary_advances.create') }}" class="btn btn-primary mb-3">Add New Salary Advance</a>

    @if ($salaryAdvances->isEmpty())
        <p>No salary advance records found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Staff</th>
                    <th>Date Advance</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salaryAdvances as $salaryAdvance)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $salaryAdvance->staff->first_name }} {{ $salaryAdvance->staff->last_name }}</td>
                        <td>{{ $salaryAdvance->date_advance }}</td>
                        <td>{{ number_format($salaryAdvance->amount, 2) }}</td>
                        <td>
                            <a href="{{ route('salary_advances.edit', $salaryAdvance->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('salary_advances.destroy', $salaryAdvance->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection