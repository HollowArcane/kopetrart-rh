@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Staff Compensations</h1>
        <a href="{{ route('staff_compensations.create') }}" class="btn btn-primary mb-3">Add Compensation</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Staff</th>
                    <th>Motif</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($compensations as $compensation)
                    <tr>
                        <td>{{ $compensation->id }}</td>
                        <td>{{ $compensation->staff->first_name }} {{ $compensation->staff->last_name }}</td>
                        <td>{{ $compensation->motif }}</td>
                        <td>{{ $compensation->date_compensation }}</td>
                        <td>{{ $compensation->amount }}</td>
                        <td>
                            <a href="{{ route('staff_compensations.edit', $compensation->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('staff_compensations.destroy', $compensation->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this record?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $compensations->links() }}
    </div>
@endsection