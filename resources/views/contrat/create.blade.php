@extends('layouts.app')

@section('content')
    <h1>Créer un Contrat</h1>

    <form action="{{ route('contrat.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="id">Id</label>
            <input type="number" class="form-control" id="id" name="id" />
        </div>
        <div class="form-group">
            <label for="libelle">Libelle</label>
            <input type="text" class="form-control" id="libelle" name="libelle" />
        </div>
        <div class="form-group">
            <label for="minmois">Minmois</label>
            <input type="number" class="form-control" id="minmois" name="minmois" />
        </div>
        <div class="form-group">
            <label for="maxmois">Maxmois</label>
            <input type="number" class="form-control" id="maxmois" name="maxmois" />
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@endsection
