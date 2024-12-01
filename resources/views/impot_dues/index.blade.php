@extends('layouts.app')

@section('content')
    <h1>Impot Due Records</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('impot_dues.create') }}" class="btn btn-primary mb-3">Add New Impot Due</a>

    @if ($impotDues->isEmpty())
        <p>No records found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Staff</th>
                    <th>Date Due</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($impotDues as $impotDue)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $impotDue->staff->first_name }} {{ $impotDue->staff->last_name }}</td>
                        <td>{{ $impotDue->date_due }}</td>
                        <td>{{ number_format($impotDue->amount, 2) }}</td>
                        <td>
                            <a href="{{ route('impot_dues.edit', $impotDue->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('impot_dues.destroy', $impotDue->id) }}" method="POST" style="display:inline;">
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