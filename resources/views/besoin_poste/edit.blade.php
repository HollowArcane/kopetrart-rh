@extends('layouts.app')

@section('content')
    <h1>Update Besoin_poste</h1>

    <form action="{{ route('besoin_poste.update', ['id' => $besoin_poste->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id">Id</label>
            <input type="number" class="form-control" id="id" name="id" value="{{ $besoin_poste->id }}" />
        </div>
        <div class="form-group">
            <label for="id_poste">Id_poste</label>
            <select class="form-control" id="id_poste" name="id_poste">
                <option value="">Select an option</option>
                @foreach($postes as $poste)
                    <option value="{{ $poste->id }}" {{ $besoin_poste->id_poste == $poste->id ? 'selected' : '' }}>
                        {{ $poste->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_genre">Id_genre</label>
            <select class="form-control" id="id_genre" name="id_genre">
                <option value="">Select an option</option>
                @foreach($genre as $genre)
                    <option value="{{ $genre->id }}" {{ $besoin_poste->id_genre == $genre->id ? 'selected' : '' }}>
                        {{ $genre->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_contrat">Id_contrat</label>
            <select class="form-control" id="id_contrat" name="id_contrat">
                <option value="">Select an option</option>
                @foreach($contrat as $contrat)
                    <option value="{{ $contrat->id }}" {{ $besoin_poste->id_contrat == $contrat->id ? 'selected' : '' }}>
                        {{ $contrat->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="agemin">Agemin</label>
            <input type="number" class="form-control" id="agemin" name="agemin" value="{{ $besoin_poste->agemin }}" />
        </div>
        <div class="form-group">
            <label for="finvalidite">Finvalidite</label>
            <input type="text" class="form-control" id="finvalidite" name="finvalidite" value="{{ $besoin_poste->finvalidite }}" />
        </div>
        <div class="form-group">
            <label for="priorite">Priorite</label>
            <select class="form-control" id="priorite" name="priorite">
                <option value="">Select an option</option>
                @foreach($priorite as $priorite)
                    <option value="{{ $priorite->id }}" {{ $besoin_poste->priorite == $priorite->id ? 'selected' : '' }}>
                        {{ $priorite->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="info_sup">Info_sup</label>
            <input type="text" class="form-control" id="info_sup" name="info_sup" value="{{ $besoin_poste->info_sup }}" />
        </div>
        <div class="form-group">
            <label for="personneltrouver">Personneltrouver</label>
            <input type="text" class="form-control" id="personneltrouver" name="personneltrouver" value="{{ $besoin_poste->personneltrouver }}" />
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

@endsection
