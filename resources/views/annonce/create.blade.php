@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Créer une Annonce</h1>

        <form action="{{ route('annonce.store') }}" method="POST">
            @csrf
            <!-- Hidden ID field -->
            <input type="hidden" id="id" name="id" />

            <div class="form-group mb-4">
                <label for="id_besoin_poste">On a besoin de</label>
                @foreach($besoins as $besoin_poste)
                    <div class="announcement-details p-3 border rounded mb-3">
                        <p class="font-weight-bold">Poste: {{ $besoin_poste->poste }}</p>
                        <p class="text-muted">Département: {{ $besoin_poste->departement }}</p>
                        
                        <!-- Hidden fields for each 'besoin' -->
                        <input type="hidden" class="form-control" id="id_besoin_poste" name="id_besoin_poste" value="{{ $besoin_poste->id }}" />
                        <input type="hidden" class="form-control" id="is_validate" name="is_validate" value="true" />
                    </div>
                @endforeach
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block">Créer l'Annonce</button>
        </form>
    </div>

@endsection

@push('styles')
<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-group label {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .announcement-details {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .announcement-details p {
        margin: 5px 0;
    }

    .btn-block {
        padding: 12px;
        font-size: 1.2rem;
        font-weight: bold;
    }
</style>
@endpush