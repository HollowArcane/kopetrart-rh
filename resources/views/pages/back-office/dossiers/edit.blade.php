@extends('layouts.app')

@section('content')
    <h1>Update Dossiers</h1>

    <form action="{{ route('dossiers.update', ['id' => $dossier->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id">Id</label>
            <input type="number" class="form-control" id="id" name="id" value="{{ $dossier->id }}" />
        </div>
        <div class="form-group">
            <label for="candidat">Candidat</label>
            <input type="text" class="form-control" id="candidat" name="candidat" value="{{ $dossier->candidat }}" />
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ $dossier->email }}" />
        </div>
        <div class="form-group">
            <label for="id_besoin_poste">Id_besoin_poste</label>
            <select class="form-control" id="id_besoin_poste" name="id_besoin_poste">
                <option value="">Select an option</option>
                @foreach($besoin_poste as $besoin_poste)
                    <option value="{{ $besoin_poste->id }}" {{ $dossier->id_besoin_poste == $besoin_poste->id ? 'selected' : '' }}>
                        {{ $besoin_poste->id_poste }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="date_reception">Date_reception</label>
            <input type="text" class="form-control" id="date_reception" name="date_reception" value="{{ $dossier->date_reception }}" />
        </div>
        <div class="form-group">
            <label for="statut">Statut</label>
            <input type="text" class="form-control" id="statut" name="statut" value="{{ $dossier->statut }}" />
        </div>
        <div class="form-group">
            <input type="hidden" class="form-control" id="cv" name="cv" value="{{ $dossier->cv }}" />
        </div>
        <div class="form-group">
            <label for="lettre_motivation">Lettre_motivation</label>
            <input type="text" class="form-control" id="lettre_motivation" name="lettre_motivation" value="{{ $dossier->lettre_motivation }}" />
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

@endsection
