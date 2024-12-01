@extends('layouts.app')

@section('content')
    <h1>Performance Bonuses</h1>
    <a href="{{ route('performance_bonuses.create') }}" class="btn btn-primary">Add Performance Bonus</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Staff</th>
                <th>Date</th>
                <th>Performance (%)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($performanceBonuses as $bonus)
                <tr>
                    <td>{{ $bonus->id }}</td>
                    <td>{{ $bonus->first_name }} {{ $bonus->last_name }}</td>
                    <td>{{ $bonus->date_bonus }}</td>
                    <td>{{ $bonus->performance }}</td>
                    <td>
                        <a href="{{ route('performance_bonuses.edit', $bonus->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('performance_bonuses.destroy', $bonus->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection