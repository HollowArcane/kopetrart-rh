@extends('layouts.app')

@section('content')
  <div class="container">

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @elseif(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    <div class="card">
      <div class="card-header">
        <h1 class="h3">Contrat d'Essai</h1>
      </div>

      <div class="card-body">
        <form action="{{ route('contrat.essai.store') }}" method="POST">
          @csrf

          <div class="form-group">
            <div class="info-section">
              <h3>Informations sur l'employé</h3>

              <!-- Nom complet de l'employé -->
              <label for="employee">Nom complet</label>
              <select id="employee" name="employee" class="form-control" onchange="updatePoste(this)">
                @foreach($entretiensValides as $entretien)
                  <option value="{{ $entretien->cv->dossier->id }}"
                          {{ $loop->first ? 'selected' : '' }}>
                    {{ $entretien->cv->dossier->candidat }} ({{ $entretien->cv->dossier->besoinPoste->poste->libelle }})
                  </option>
                @endforeach
              </select>

              <!-- Poste actuel -->
              <label for="poste">Poste actuel</label>
              <input type="text" id="poste" value="{{ $entretiensValides->first()->cv->dossier->besoinPoste->poste->libelle ?? 'Non renseigné' }}" readonly class="form-control">

              <!-- Date d'entrée -->
              <label for="date_entree">Date d'entrée</label>
              <input type="date" id="date_entree" value="{{ $entretiensValides->first()->cv->dossier->date_reception ?? '2023-01-01' }}" class="form-control">
            </div>

            <div class="info-section">
              <h3>Détails du contrat d'essaie</h3>

              <!-- Type de contrat -->
              <label for="type_contrat">Type de contrat</label>
              <select name="type_contrat" id="type_contrat" class="form-control">
                @foreach($contrats as $contrat)
                  <option value="{{ $contrat->libelle }}">{{ $contrat->libelle }}</option>
                @endforeach
              </select>

              <!-- Nouvelle durée (si CDD) -->
              <label for="periode">Nouvelle durée (si CDD)</label>
              <select name="periode" id="periode" class="form-control">
                <option value="6">6 mois</option>
                <option value="12">1 an</option>
                <option value="24">2 ans</option>
              </select>

              <!-- Salaire proposé -->
              <label for="salaire_propose">Salaire proposé</label>
              <input type="text" name="salaire_propose" id="salaire_propose" placeholder="Montant annuel" class="form-control">
            </div>

            <label for="notes_sup">Commentaires</label>
            <textarea name="notes_sup" id="notes_sup" rows="4" placeholder="Ajoutez vos commentaires sur le renouvellement..." class="form-control"></textarea>
          </div>

          <div class="actions">
            <button type="submit" class="btn btn-success">Valider le renouvellement</button>
            <button type="button" class="btn btn-outline-secondary" style="background-color: #666; color: white;">Ne pas renouveler</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Fonction pour mettre à jour le poste actuel en fonction du candidat sélectionné
    function updatePoste(selectElement) {
      var candidateId = selectElement.value;

      console.log("ID ve tena ???" + candidateId);

      var posteElement = document.getElementById('poste');

      // Récupérer tous les entretiens valides envoyés depuis PHP
      var selectedCandidate = @json($entretiensValides);

      console.log(selectedCandidate);

      // Trouver l'entretien correspondant au candidat sélectionné
      var entretien = selectedCandidate.find(function(entretien) {
        return entretien.cv.dossier.candidat.trim().toLowerCase() == candidateId.trim().toLowerCase();
      });


      //console.log(entretien);

      // Mettre à jour le champ "poste" avec le libellé du poste
      if (entretien && entretien.cv.dossier.besoin_poste && entretien.cv.dossier.besoin_poste.poste) {
        posteElement.value = entretien.cv.dossier.besoin_poste.poste.libelle;
      } else {
        posteElement.value = 'Non renseigné';
      }
    }

    // Initialisation correcte de la valeur du poste lors du chargement de la page
    window.onload = function() {
      var firstEmployeeSelect = document.getElementById('employee');
      if (firstEmployeeSelect) {
        updatePoste(firstEmployeeSelect); // Mettre à jour dès le chargement
      }
    };
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

  .btn-success {
    background-color: #28a745;
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

  .info-section {
    border: 1px solid #ddd;
    padding: 15px;
    margin: 15px 0;
    border-radius: 8px;
    background-color: #f9f9f9;
  }

  .actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
  }
</style>
@endpush
