@extends('layouts.app')

@section('content')
    <h1>Update Employe</h1>

    <form action="{{ route('employe.update', ['id' => $employe->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <input type="hidden" class="form-control" id="id" name="id" value="{{ $employe->id }}" />
        </div>
        <div class="form-group">
            <label for="id_departement">Departement</label>
            <select class="form-control" id="id_departement" name="id_departement">
                <option value="">Select an option</option>
                @foreach($departement as $departement)
                    <option value="{{ $departement->id }}" {{ $employe->id_departement == $departement->id ? 'selected' : '' }}>
                        {{ $departement->libelle }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="id_poste">Poste</label>
            <select class="form-control" id="id_poste" name="id_poste">
                <option value="">Select an option</option>
                @foreach($postes as $poste)
                    <option value="{{ $poste->id }}" {{ $employe->id_poste == $poste->id ? 'selected' : '' }}>
                        {{ $poste->libelle }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="text" class="form-control" id="date" name="date" value="{{ $employe->date }}" />
        </div>

        <div class="form-group">
            <label for="id_contrat">Contrat</label>
            <select class="form-control" id="id_contrat" name="id_contrat">
                <option value="">Select an option</option>
                @foreach($contrat as $contrat)
                    <option value="{{ $contrat->id }}" {{ $employe->id_contrat == $contrat->id ? 'selected' : '' }}>
                        {{ $contrat->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

@endsection
