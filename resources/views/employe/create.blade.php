@extends('layouts.app')

@section('content')
    <h1>Créer un Employe</h1>

    <form action="{{ route('employe.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="id">Id</label>
            <input type="number" class="form-control" id="id" name="id" />
        </div>
        <div class="form-group">
            <label for="id_departement">Id_departement</label>
            <select class="form-control" id="id_departement" name="id_departement">
                <option value="">All</option>
                @foreach($departement as $departement)
                    <option value="{{ $departement->id }}">{{ $departement->libelle }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="id_poste">Id_poste</label>
            <select class="form-control" id="id_poste" name="id_poste">
                <option value="">All</option>
                @foreach($postes as $poste)
                    <option value="{{ $poste->id }}">{{ $poste->libelle }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="text" class="form-control" id="date" name="date" />
        </div>
        <div class="form-group">
            <label for="id_contrat">Id_contrat</label>
            <select class="form-control" id="id_contrat" name="id_contrat">
                <option value="">All</option>
                @foreach($contrat as $contrat)
                    <option value="{{ $contrat->id }}">{{ $contrat->libelle }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@endsection
