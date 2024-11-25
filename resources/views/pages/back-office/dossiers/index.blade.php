@extends('layouts.app')

@section('content')
@php
$session = session();

$state_color = [
    'Approuvé' => 'success',
    'Rejeté' => 'danger'
];
@endphp

    <h1>Liste des Dossiers</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Candidat</th>
                <th>Poste</th>
                <th>Date réception</th>
                <th>Statut</th>
                <th>Progression</th>
                <th>Documents</th>
                @if ($session->get('role') == 5)
                <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($dossiers as $liste_dossiers)
                <tr>
                    <td>{{ $liste_dossiers->candidat }}</td>
                    <td>{{ $liste_dossiers->besoinPoste->poste->libelle ?? 'N/A' }}</td>
                    <td>{{ $liste_dossiers->date_reception }}</td>
                    <td><span class="badge badge-{{ $state_color[$liste_dossiers->progression_status] ?? 'info' }}">{{ $liste_dossiers->progression_status }}</span></td>
                    <td>{{ $liste_dossiers->progression }} %</td>

                    <td>
                        <!-- Boutons pour ouvrir les fichiers PDF dans une fenêtre modale -->
                        <button class="btn-doc" onclick="openModal('{{ Storage::url($liste_dossiers->lettre_motivation) }}')">LM</button>
                        <button class="btn-doc" onclick="openModal('{{ Storage::url($liste_dossiers->cv) }}')">CV</button>
                    </td>
                    @if ($session->get('role') == 5)
                    <td>

                        <!-- Bouton pour "Évaluer" -->
                        @if ( $liste_dossiers->statut != 'Refusé' && !$liste_dossiers->esttraduit)

                        <a href="{{ route('classification_cv.evaluate', $liste_dossiers->id) }}" class="btn-action"
                            {{ $liste_dossiers->statut === 'Refusé' ? 'disabled' : '' }}>
                            Traduction
                        </a>
                        @endif

                        <!-- Bouton pour "Refuser" -->
                        <button class="btn-action"
                        onclick="refuser({{ $liste_dossiers->id }})"
                        id="refuser-btn-{{ $liste_dossiers->id }}"
                        {{ $liste_dossiers->statut === 'Refusé' ? 'disabled' : '' }}>
                        {{ $liste_dossiers->statut === 'Refusé' ? 'Refusé' : 'Refuser' }}
                        </button>
                    </td>
                    @endif

                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal pour afficher les fichiers PDF -->
    <div id="pdfModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <iframe id="pdfIframe" style="width:100%; height: 500px;"></iframe>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Style pour la modal */
        .modal {
            display: none;  /* Caché par défaut */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);  /* Fond semi-transparent */
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            width: 80%; /* 80% de la largeur de l'écran */
            height: 70%; /* 70% de la hauteur de l'écran */
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Style des boutons */
        .btn-action {
            padding: 5px 10px;
            margin: 5px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .btn-action:disabled {
            background-color: #f1f1f1;
            cursor: not-allowed;
            color: #bbb;
        }

        .btn-action.refuse {
            color: red;
            font-weight: bold;
        }

        .btn-doc {
            padding: 5px 10px;
            margin: 5px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-doc:hover {
            background-color: #0b7dda;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Fonction pour ouvrir la modal avec le fichier PDF
        function openModal(pdfUrl) {
            document.getElementById('pdfIframe').src = pdfUrl;  // Charge l'URL du PDF dans l'iframe
            document.getElementById('pdfModal').style.display = "block";  // Affiche la modal
        }

        // Fonction pour fermer la modal
        function closeModal() {
            document.getElementById('pdfModal').style.display = "none";  // Cache la modal
            document.getElementById('pdfIframe').src = "";  // Vide l'iframe
        }

        // Lorsque l'utilisateur clique en dehors de la modal, la fermer
        window.onclick = function(event) {
            var modal = document.getElementById('pdfModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        function refuser(id) {
            fetch(`/dossiers/refuser/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    statut: 'Refusé'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Modification du bouton Refuser
                    document.getElementById('refuser-btn-' + id).innerText = 'Refusé';
                    document.getElementById('refuser-btn-' + id).style.color = 'red';
                    document.getElementById('refuser-btn-' + id).disabled = true;

                    // Désactiver le bouton Évaluer du même dossier
                    const buttonEvaluer = document.querySelector(`button[onclick="evaluerDossier(${id})"]`);
                    if (buttonEvaluer) {
                        buttonEvaluer.disabled = true;
                        buttonEvaluer.style.backgroundColor = '#f1f1f1';
                        buttonEvaluer.style.color = '#bbb';
                    }
                } else {
                    alert('Erreur lors de la mise à jour du dossier');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }
    </script>
@endpush
