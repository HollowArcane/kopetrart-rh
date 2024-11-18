@extends('layouts.app')

@section('content')
    <h1>Créer une Promotion</h1>

    <form action="{{ route('promotion.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <input type="hidden" class="form-control" id="id" name="id" />
        </div>

        <!-- Affichage du candidat -->
        <div class="form-group">
            <label for="candidat">Candidat</label>
            <p>{{ $candidat }}</p> <!-- Affiche le candidat -->
        </div>

        <div class="form-group">
            <label for="id_poste">Poste</label>
            <select class="form-control" id="id_poste" name="id_poste">
                <option value="">All</option>
                @foreach($postes as $poste)
                    <option value="{{ $poste->id }}" {{ $poste->id == $postePromotion ? 'selected' : '' }}>
                        {{ $poste->libelle }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" id="date" name="date" />
        </div>

        <div class="form-group">
            <label for="salaire">Salaire</label>
            <input type="number" class="form-control" id="salaire" name="salaire" />
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" id="status" name="status" value="false" />
        </div>

        <input type="hidden" name="id_employe" value="{{ $id_employe }}" /> <!-- Ajout de l'id_employe caché -->

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@endsection
