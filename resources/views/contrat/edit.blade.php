@extends('layouts.app')

@section('content')
    <h1>Update Contrat</h1>

    <form action="{{ route('contrat.update', ['id' => $contrat->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id">Id</label>
            <input type="number" class="form-control" id="id" name="id" value="{{ $contrat->id }}" />
        </div>
        <div class="form-group">
            <label for="libelle">Libelle</label>
            <input type="text" class="form-control" id="libelle" name="libelle" value="{{ $contrat->libelle }}" />
        </div>
        <div class="form-group">
            <label for="minmois">Minmois</label>
            <input type="number" class="form-control" id="minmois" name="minmois" value="{{ $contrat->minmois }}" />
        </div>
        <div class="form-group">
            <label for="maxmois">Maxmois</label>
            <input type="number" class="form-control" id="maxmois" name="maxmois" value="{{ $contrat->maxmois }}" />
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

@endsection
