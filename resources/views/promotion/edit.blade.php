@extends('layouts.app')

@section('content')
    <h1>Update Promotion</h1>

    <form action="{{ route('promotion.update', ['id' => $promotion->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id">Id</label>
            <input type="number" class="form-control" id="id" name="id" value="{{ $promotion->id }}" />
        </div>
        <div class="form-group">
            <label for="id_employe">Id_employe</label>
            <select class="form-control" id="id_employe" name="id_employe">
                <option value="">Select an option</option>
                @foreach($employe as $employe)
                    <option value="{{ $employe->id }}" {{ $promotion->id_employe == $employe->id ? 'selected' : '' }}>
                        {{ $employe->id_departement }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_poste">Id_poste</label>
            <select class="form-control" id="id_poste" name="id_poste">
                <option value="">Select an option</option>
                @foreach($postes as $poste)
                    <option value="{{ $poste->id }}" {{ $promotion->id_poste == $poste->id ? 'selected' : '' }}>
                        {{ $poste->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="text" class="form-control" id="date" name="date" value="{{ $promotion->date }}" />
        </div>
        <div class="form-group">
            <label for="salaire">Salaire</label>
            <input type="number" class="form-control" id="salaire" name="salaire" value="{{ $promotion->salaire }}" />
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <input type="text" class="form-control" id="status" name="status" value="{{ $promotion->status }}" />
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

@endsection
