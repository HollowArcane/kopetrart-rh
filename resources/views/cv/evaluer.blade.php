@extends('layouts.app')

@section('content')
<html>
    <head>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding: 20px; 
            max-width: 100%; /* Supprimer la limite de largeur */
            margin: 0; /* Supprimer la marge automatique */
            width: 100%; /* Utiliser toute la largeur */
        }
        .header { 
            border-bottom: 2px solid black; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        .classification-section { 
            border: 1px solid #ddd; 
            padding: 15px; 
            margin: 10px 0; 
            border-radius: 8px; 
        }
        input, select, textarea { 
            width: 100%;
            background: #f0f0f0; 
            border: 1px solid #ddd; 
            padding: 8px 12px; 
            border-radius: 8px; 
            margin-bottom: 10px;
        }
        button {
            background: black;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px; /* Espace au-dessus du bouton */
        }
        .tag {
            display: inline-block;
            padding: 4px 8px;
            margin: 4px;
            background: #f0f0f0;
            border-radius: 4px;
            cursor: pointer;
        }
        .popup {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
        }
        .popup input {
            width: 100%;
            margin-bottom: 10px;
        }
        .popup button {
            background: green;
            color: white;
        }
        .cart {
            border-left: 1px solid #ddd;
            padding-left: 20px;
            margin-left: 20px;
        }
        .cart .card-item {
            margin: 5px 0;
            padding: 5px;
            background: #f0f0f0;
            border-radius: 4px;
        }
        .form-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
    </head>
    <body>
        <div class="header">
            <h1>Classification du CV</h1>
        </div>

        <div class="form-container">
            <div class="form-group" style="width: 60%;"> <!-- Formulaire à gauche -->
                <label>Candidat</label>
                <!-- Utiliser la donnée du candidat -->
                @if($dossier->candidat)
                    <input type="text" value="{{ $dossier->candidat }}" readonly>
                @else
                    <p>No candidate found.</p>
                @endif


                <div class="classification-section">
                    <h3>Compétences identifiées</h3>
                    <div id="competences">
                        <label>
                            <input type="radio" name="competence" value="PHP"> PHP
                        </label>
                        <label>
                            <input type="radio" name="competence" value="Laravel"> Laravel
                        </label>
                        <label>
                            <input type="radio" name="competence" value="JavaScript"> JavaScript
                        </label>
                        <label>
                            <input type="radio" name="competence" value="Git"> Git
                        </label>
                    </div>
                    <button type="button" onclick="openCompetencePopup()">Ajouter une compétence +</button>
                </div>

                <div class="classification-section">
                    <h3>Niveau d'expérience</h3>
                    <label>
                        <input type="text" id="experience-level" name="experience_level" placeholder="Années d'Expérience" value="">
                    </label>
                    <!-- Bouton Ajouter à la carte -->
                    <button type="button" onclick="addToCart()">Ajouter à la carte</button>
                </div>

                <div class="classification-section">
                    <h3>Domaines d'expertise</h3>
                    <div id="domaines">
                        <span class="tag">Développement Web</span>
                        <span class="tag">Architecture logicielle</span>
                        <span class="tag">CI/CD</span>
                    </div>
                    <button type="button" onclick="openDomainePopup()">Ajouter un domaine +</button>
                </div>

                <div class="classification-section">
                    <h3>Notes additionnelles</h3>
                    <textarea rows="4" placeholder="Ajoutez des notes sur le CV..."></textarea>
                </div>
            </div>

            <!-- Carte à droite -->
            <div class="cart" style="width: 35%;"> <!-- Carte à droite -->
                <h3>Carte de Compétences</h3>
                <div id="cart-items"></div>
            </div>
        </div>

        <div class="actions">
        <form action="{{ route('classification_cv.store', ['id' => $dossier->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit">Enregistrer la classification</button>
        </form>
        <a href="{{ route('dossiers.index') }}" class="btn" style="background: white; color: black; border: 1px solid black;">Retour</a>
    </div>

        <!-- Competence Popup -->
        <div class="popup" id="competence-popup">
            <div class="popup-content">
                <h3>Ajouter une nouvelle compétence</h3>
                <input type="text" id="competence-name" placeholder="Nom de la compétence">
                <button type="button" onclick="addCompetence()">Ajouter</button>
                <button type="button" onclick="closeCompetencePopup()">Fermer</button>
            </div>
        </div>

        <!-- Domaine Popup -->
        <div class="popup" id="domaine-popup">
            <div class="popup-content">
                <h3>Ajouter un domaine d'expertise</h3>
                <input type="text" id="domaine-name" placeholder="Nom du domaine">
                <button type="button" onclick="addDomaine()">Ajouter</button>
                <button type="button" onclick="closeDomainePopup()">Fermer</button>
            </div>
        </div>

    </body>

    <script>
            // Attendre que jQuery soit chargé
            $(document).ready(function() {
                // Initialisation de Select2
                $('#candidat').select2();

                // Fonction pour ajouter la compétence à la carte
                window.addToCart = function() {
                    const competence = document.querySelector('input[name="competence"]:checked');
                    const experienceLevel = document.getElementById('experience-level').value;
                    if (competence && experienceLevel) {
                        const cartItems = document.getElementById('cart-items');
                        const newItem = document.createElement('div');
                        newItem.classList.add('card-item');
                        newItem.textContent = `${competence.value} - ${experienceLevel} ans d'expérience`;
                        cartItems.appendChild(newItem);
                    }
                };

                // Popups pour ajouter des compétences et domaines
                window.openCompetencePopup = function() {
                    document.getElementById('competence-popup').style.display = 'flex';
                };

                window.closeCompetencePopup = function() {
                    document.getElementById('competence-popup').style.display = 'none';
                };

                window.addCompetence = function() {
                    const competenceName = document.getElementById('competence-name').value;
                    if (competenceName) {
                        const competenceDiv = document.createElement('label');
                        competenceDiv.innerHTML = `<input type="radio" name="competence" value="${competenceName}"> ${competenceName}`;
                        document.getElementById('competences').appendChild(competenceDiv);
                        closeCompetencePopup(); 
                    }
                };

                window.openDomainePopup = function() {
                    document.getElementById('domaine-popup').style.display = 'flex';
                };

                window.closeDomainePopup = function() {
                    document.getElementById('domaine-popup').style.display = 'none';
                };

                window.addDomaine = function() {
                    const domaineName = document.getElementById('domaine-name').value;
                    if (domaineName) {
                        const domaineTag = document.createElement('span');
                        domaineTag.classList.add('tag');
                        domaineTag.textContent = domaineName;
                        document.getElementById('domaines').appendChild(domaineTag);
                        closeDomainePopup(); 
                    }
                };

                // Fonction pour collecter les éléments du panier
                function collectCartItems() {
                    const cartItems = document.getElementById('cart-items');
                    const items = [];
                    
                    cartItems.querySelectorAll('.card-item').forEach(item => {
                        const text = item.textContent;
                        const [competence, experienceText] = text.split(' - ');
                        const experience = parseInt(experienceText);
                        
                        items.push({
                            competence: competence,
                            experience: experience
                        });
                    });
                    
                    return items;
                }

                // Fonction pour collecter les domaines
                function collectDomaines() {
                    const domainesDiv = document.getElementById('domaines');
                    const domaines = [];
                    
                    domainesDiv.querySelectorAll('.tag').forEach(tag => {
                        domaines.push(tag.textContent);
                    });
                    
                    return domaines;
                }

                // Gestionnaire de soumission du formulaire
                $('form').on('submit', function(e) {
                    e.preventDefault();
                    
                    // Créer un champ caché pour les éléments du panier
                    const cartItemsInput = document.createElement('input');
                    cartItemsInput.type = 'hidden';
                    cartItemsInput.name = 'cart_items';
                    cartItemsInput.value = JSON.stringify(collectCartItems());
                    
                    // Créer un champ caché pour les domaines
                    const domainesInput = document.createElement('input');
                    domainesInput.type = 'hidden';
                    domainesInput.name = 'domaines';
                    domainesInput.value = JSON.stringify(collectDomaines());
                    
                    // Récupérer la valeur des notes
                    const notesInput = document.createElement('input');
                    notesInput.type = 'hidden';
                    notesInput.name = 'notes';
                    notesInput.value = document.querySelector('textarea').value;
                    
                    // Ajouter les champs au formulaire
                    this.appendChild(cartItemsInput);
                    this.appendChild(domainesInput);
                    this.appendChild(notesInput);
                    
                    // Soumettre le formulaire
                    this.submit();
                });
            });
        </script>
</html>
@endsection
