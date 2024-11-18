@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Liste des Annonces</h1>

        <!-- Message de notification -->
        <div id="notification" class="alert" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 1000;"></div>

        @if($annonce && count($annonce) > 0)
            <div class="form-group mb-4">
                <!-- Liste déroulante des annonces -->
                <label for="id_annonce">Sélectionnez une annonce</label>
                <select class="form-control mb-3" id="id_annonce" name="id_annonce" required>
                    <option value="">Sélectionnez une annonce</option>
                    @foreach($annonce as $liste_annonce)
                        <option
                            value="{{ $liste_annonce->id_annonce }}"
                            data-poste="{{ $liste_annonce->poste }}"
                            data-departement="{{ $liste_annonce->departement }}"
                        >
                            ANN{{ $liste_annonce->id_annonce }} - {{ $liste_annonce->poste }} ({{ $liste_annonce->departement }})
                        </option>
                    @endforeach
                </select>

                <!-- Liste déroulante des moyens de communication -->
                <label for="id_moyenne_comm">Moyen de Communication</label>
                <select class="form-control mb-3" id="id_moyenne_comm" name="id_moyenne_comm" required>
                    <option value="">Tout afficher</option>
                    @foreach($moyenne_comm as $moyenne)
                        <option value="{{ $moyenne->id }}" data-libelle="{{ $moyenne->libelle }}">
                            {{ $moyenne->libelle }}
                        </option>
                    @endforeach
                </select>

                <!-- Date de communication -->
                <label for="communication_date">Date de communication</label>
                <input type="date" class="form-control mb-3" id="communication_date" name="communication_date" required>

                <!-- Boutons d'action -->
                <div class="d-flex gap-2">
                    <button class="btn btn-primary add-to-cart">
                        <i class="fas fa-cart-plus"></i> Ajouter au panier
                    </button>
                    <button class="btn btn-secondary clear-selection">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Tableau du panier -->
            <div class="cart-section mt-5">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Votre panier</h2>
                    <button class="btn btn-success validate-cart" id="validate-cart" disabled>
                        <i class="fas fa-check"></i> Valider la commande
                    </button>
                </div>

                <table class="table mt-3" id="cart-table">
                    <thead>
                        <tr>
                            <th>Id Annonce</th>
                            <th>Poste</th>
                            <th>Département</th>
                            <th>Moyen de communication</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Les éléments du panier seront ajoutés ici dynamiquement -->
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                Aucune annonce disponible.
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const validateButton = document.getElementById('validate-cart');
        let cartItems = [];

        // Définir la date minimale à aujourd'hui
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('communication_date').min = today;

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `alert alert-${type}`;
            notification.style.display = 'block';

            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        function updateCartTable() {
            const cartTableBody = document.querySelector('#cart-table tbody');
            cartTableBody.innerHTML = '';

            cartItems.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>ANN${item.id}</td>
                    <td>${item.poste}</td>
                    <td>${item.departement}</td>
                    <td>${item.moyenneCommLibelle}</td>
                    <td>${item.date}</td>
                    <td>
                        <button class="btn btn-danger btn-sm remove-from-cart"
                            data-id="${item.id}"
                            data-moyenne-comm="${item.moyenneCommId}"
                            data-date="${item.date}">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </td>
                `;
                cartTableBody.appendChild(row);

                row.querySelector('.remove-from-cart').addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    const moyenneCommId = this.getAttribute('data-moyenne-comm');
                    const date = this.getAttribute('data-date');
                    cartItems = cartItems.filter(item =>
                        !(item.id === itemId &&
                        item.moyenneCommId === moyenneCommId &&
                        item.date === date)
                    );
                    updateCartTable();
                    showNotification('Article retiré du panier');
                });
            });

            validateButton.disabled = cartItems.length === 0;
        }

        // Ajouter au panier
        document.querySelector('.add-to-cart').addEventListener('click', function() {
            const annonceSelect = document.getElementById('id_annonce');
            const selectedOption = annonceSelect.options[annonceSelect.selectedIndex];
            const moyenneCommSelect = document.getElementById('id_moyenne_comm');
            const moyenneCommId = moyenneCommSelect.value;
            const moyenneCommLibelle = moyenneCommSelect.options[moyenneCommSelect.selectedIndex].text;
            const communicationDate = document.getElementById('communication_date').value;

            // Validation de l'annonce
            if (!annonceSelect.value) {
                showNotification('Veuillez sélectionner une annonce', 'warning');
                return;
            }

            // Validation du moyen de communication
            if (!moyenneCommId) {
                showNotification('Veuillez sélectionner un moyen de communication', 'warning');
                return;
            }

            // Validation de la date
            if (!communicationDate) {
                showNotification('Veuillez sélectionner une date', 'warning');
                return;
            }

            const selectedDate = new Date(communicationDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                showNotification('La date ne peut pas être antérieure à aujourd\'hui', 'warning');
                return;
            }

            const item = {
                id: annonceSelect.value,
                poste: selectedOption.dataset.poste,
                departement: selectedOption.dataset.departement,
                moyenneCommId: moyenneCommId,
                moyenneCommLibelle: moyenneCommLibelle,
                date: communicationDate
            };

            // Vérifier les doublons
            if (!cartItems.some(cartItem =>
                cartItem.id === item.id &&
                cartItem.moyenneCommId === item.moyenneCommId &&
                cartItem.date === item.date
            )) {
                cartItems.push(item);
                updateCartTable();
                showNotification('Article ajouté au panier');
            } else {
                showNotification('Cette combinaison article/moyen de communication/date existe déjà dans votre panier', 'warning');
            }
        });

        // Validate cart
        document.getElementById('validate-cart').addEventListener('click', function() {
            if (cartItems.length === 0) {
                showNotification('Le panier est vide', 'warning');
                return;
            }

            const token = '{{ csrf_token() }}';

            fetch('/annonce_communication/batch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    items: cartItems
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => Promise.reject(data));
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    cartItems = [];
                    updateCartTable();
                    showNotification('Commande validée avec succès');
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Une erreur est survenue lors de la validation';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                showNotification(errorMessage, 'danger');
            });
        });

        // Clear selection
        document.querySelector('.clear-selection').addEventListener('click', function() {
            document.getElementById('id_annonce').value = '';
            document.getElementById('id_moyenne_comm').value = '';
            document.getElementById('communication_date').value = '';
        });
    });
    </script>
    @endpush
@endsection

@push('styles')
<style>
    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-control {
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #f8f9fa;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn-primary, .btn-secondary {
        padding: 10px 20px;
        border-radius: 8px;
    }

    .cart-section {
        margin-top: 30px;
    }

    .cart-table th, .cart-table td {
        text-align: center;
    }

    .cart-table td button {
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #dc3545;
        color: white;
    }

    .cart-table td button:hover {
        background-color: #c82333;
    }

    .validate-cart {
        font-weight: bold;
    }
</style>
@endpush