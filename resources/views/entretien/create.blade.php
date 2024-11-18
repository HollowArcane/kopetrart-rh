@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h1 class="h3">Validation d'entretien</h1>
      </div>

      <div class="card-body">
        @if(session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif

        <form action="{{ route('entretien.store', ['id' => $cv->id]) }}" method="POST">
          @csrf

          <div class="form-group">
            <label for="candidat">Candidat</label>
            <input type="text" id="candidat" value="{{ $cv->dossier->candidat ?? 'N/A' }}" readonly class="form-control">
          </div>

          <div class="form-group">
            <label for="poste">Poste</label>
            <input type="text" id="poste" value="{{ $cv->dossier->besoinPoste->poste->libelle ?? 'N/A' }}" readonly class="form-control">
          </div>

          <div class="form-group">
            <label for="date_entretien">Date d'entretien</label>
            <input type="datetime-local" id="date_entretien" name="date_entretien" value="{{ old('date_entretien') }}" required class="form-control">
            @error('date_entretien')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="commentaire">Commentaires détaillés</label>
            <textarea id="commentaire" name="commentaire" rows="4" placeholder="Ajoutez vos observations..." class="form-control">{{ old('commentaire') }}</textarea>
            @error('commentaire')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <input type="hidden" name="status" id="status_input">

          <div class="button-group">
            <button type="submit" class="btn btn-success" onclick="setStatus('valide')">
              Valider l'entretien
            </button>
            <button type="submit" class="btn btn-danger" onclick="setStatus('rejete')">
              Rejeter la candidature
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
              Retour
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function setStatus(status) {
        document.getElementById('status_input').value = status;
    }
  </script>

@endsection

@push('styles')
<style>
  .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }

  .card {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 20px;
  }

  .card-header {
    border-bottom: 2px solid #f1f1f1;
    margin-bottom: 20px;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-control {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: #f8f9fa;
  }

  .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }

  .button-group button {
    margin-right: 10px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 16px;
  }

  .btn-success {
    background-color: #28a745;
    color: white;
  }

  .btn-danger {
    background-color: #dc3545;
    color: white;
  }

  .btn-outline-secondary {
    background-color: white;
    color: #6c757d;
    border: 1px solid #ddd;
  }

  .btn-outline-secondary:hover {
    background-color: #f8f9fa;
  }

  .alert {
    margin-bottom: 20px;
    border-radius: 8px;
    padding: 15px;
    font-size: 16px;
  }

  .invalid-feedback {
    font-size: 14px;
    color: #e3342f;
  }
</style>
@endpush