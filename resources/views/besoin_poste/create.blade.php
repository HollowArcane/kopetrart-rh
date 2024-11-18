@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <h1 class="mb-4 text-center">Créer un Besoin de poste</h1>

    <!-- Message de notification -->
    <div id="notification" class="alert alert-info alert-dismissible fade show" role="alert"    >
    </div>

    <form method="POST" id="besoinPosteForm" class="container">
        @csrf
        <input type="hidden" class="form-control" id="id" name="id" />

        <!-- Informations générales -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Informations générales</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="id_poste">Poste</label>
                        <select class="form-control" id="id_poste" name="id_poste" required>
                            <option value="">All</option>
                            @foreach($postes as $poste)
                                <option value="{{ $poste->id }}">{{ $poste->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="id_genre">Genre</label>
                        <select class="form-control" id="id_genre" name="id_genre" required>
                            <option value="">All</option>
                            @foreach($genre as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="id_contrat">Contrat</label>
                        <select class="form-control" id="id_contrat" name="id_contrat" required>
                            <option value="">All</option>
                            @foreach($contrat as $contrat)
                                <option value="{{ $contrat->id }}">{{ $contrat->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="agemin">Age minimum</label>
                        <input type="number" class="form-control" id="agemin" name="agemin" required />
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="agemax">Age max</label>
                        <input type="number" class="form-control" id="agemax" name="agemax" required />
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="finvalidite">Date de fin de validité</label>
                        <input type="date" class="form-control" id="finvalidite" name="finvalidite" required />
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="priorite">Priorité</label>
                        <select class="form-control" id="priorite" name="priorite" required>
                            <option value="">All</option>
                            @foreach($priorite as $priorite)
                                <option value="{{ $priorite->id }}">{{ $priorite->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Talents -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Talents requis</h4>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 mb-3">
                    <select class="form-control" id="id_talent">
                        <option value="">Sélectionner un talent</option>
                        @foreach($talent as $talent)
                            <option value="{{ $talent->id }}" data-libelle="{{ $talent->libelle }}">
                                {{ $talent->libelle }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-primary" id="addTalentToCart">
                        <i class="fas fa-plus"></i> Ajouter
                    </button>
                </div>

                <!-- Liste des talents avec leurs détails -->
                <div id="selected-talents-container" class="list-group mb-4">
                    <!-- Les talents seront ajoutés ici dynamiquement -->
                </div>
                <input type="hidden" name="talents_data" id="talents-data-input" value="[]">
            </div>
        </div>

        <input type="hidden" class="form-control" id="personneltrouver" name="personneltrouver" />

        <!-- Submit button -->
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>

    <!-- Template pour un talent -->
    <template id="talent-template">
        <div class="talent-card card mb-3" data-talent-id="">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="talent-title mb-0"></h5>
                <button type="button" class="btn btn-danger btn-sm remove-talent">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Expérience minimum</label>
                        <input type="number" class="form-control talent-expmin" required />
                    </div>
                    <div class="col-md-6 form-group">
                        <input type="hidden" value="12" class="form-control talent-expmax" />
                    </div>
                </div>
                <div class="form-group">
                    <label>Niveau d'études</label>
                    <input type="number" class="form-control talent-etude" required />
                </div>
                <div class="form-group">
                    <label>Informations supplémentaires</label>
                    <input type="text" class="form-control talent-info-sup" />
                </div>
            </div>
        </div>
    </template>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
    let selectedTalents = [];
    const talentSelect = document.getElementById('id_talent');
    const addTalentButton = document.getElementById('addTalentToCart');
    const selectedTalentsContainer = document.getElementById('selected-talents-container');
    const talentsDataInput = document.getElementById('talents-data-input');
    const form = document.getElementById('besoinPosteForm');
    const talentTemplate = document.getElementById('talent-template');

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = `alert alert-${type}`;
        notification.style.display = 'block';

        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    function updateTalentsData() {
        const talentsData = selectedTalents.map(talent => {
            const talentCard = document.querySelector(`.talent-card[data-talent-id="${talent.id}"]`);
            return {
                id: talent.id,
                libelle: talent.libelle,
                expmin: talentCard.querySelector('.talent-expmin').value,
                expmax: talentCard.querySelector('.talent-expmax').value,
                etude: talentCard.querySelector('.talent-etude').value,
                info_sup: talentCard.querySelector('.talent-info-sup').value
            };
        });
        talentsDataInput.value = JSON.stringify(talentsData);
    }

    function addTalentCard(talent) {
        const template = talentTemplate.content.cloneNode(true);
        const talentCard = template.querySelector('.talent-card');

        talentCard.dataset.talentId = talent.id;
        talentCard.querySelector('.talent-title').textContent = talent.libelle;

        // Ajouter les écouteurs d'événements pour les champs
        talentCard.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', updateTalentsData);
        });

        // Gérer la suppression
        talentCard.querySelector('.remove-talent').addEventListener('click', function() {
            selectedTalents = selectedTalents.filter(t => t.id !== talent.id);
            talentCard.remove();
            updateTalentsData();
            showNotification('Talent retiré avec succès');
        });

        selectedTalentsContainer.appendChild(talentCard);
    }

    addTalentButton.addEventListener('click', function() {
        const selectedOption = talentSelect.options[talentSelect.selectedIndex];
        if (selectedOption.value) {
            const talentId = selectedOption.value;
            const talentLibelle = selectedOption.text;

            if (!selectedTalents.some(t => t.id === talentId)) {
                const talent = {
                    id: talentId,
                    libelle: talentLibelle
                };
                selectedTalents.push(talent);
                addTalentCard(talent);
                updateTalentsData();
                showNotification('Talent ajouté avec succès');
                talentSelect.value = '';
            } else {
                showNotification('Ce talent est déjà sélectionné', 'warning');
            }
        } else {
            showNotification('Veuillez sélectionner un talent', 'warning');
        }
    });

    // Set minimum date for finvalidite to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('finvalidite').min = today;

    // Form validation and AJAX submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (selectedTalents.length === 0) {
            showNotification('Veuillez sélectionner au moins un talent', 'warning');
            return;
        }

        // Vérifier que tous les champs requis sont remplis
        let isValid = true;
        selectedTalents.forEach(talent => {
            const talentCard = document.querySelector(`.talent-card[data-talent-id="${talent.id}"]`);
            talentCard.querySelectorAll('input[required]').forEach(input => {
                if (!input.value) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
        });

        if (!isValid) {
            showNotification('Veuillez remplir tous les champs requis pour chaque talent', 'warning');
            return;
        }

        // Collecter les données du formulaire
        const formData = new FormData(form);
        formData.append('talents_data', talentsDataInput.value);

        // Soumettre le formulaire via AJAX
        $.ajax({
            url: '{{ route('besoin_poste.store') }}', // Votre route ici
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showNotification('Besoin de poste créé avec succès', 'success');
                // Réinitialiser le formulaire après soumission réussie
                form.reset();
                selectedTalents = [];
                selectedTalentsContainer.innerHTML = '';
            },
            error: function(xhr, status, error) {
                showNotification('Une erreur est survenue. Veuillez réessayer.', 'danger');
            }
        });
    });
});
</script>
@endsection
