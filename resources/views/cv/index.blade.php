@extends('layouts.app')

@section('content')
@php
$session = session();
@endphp
    <div class="container">
        <h1 class="text-center mb-4">Liste des CVs</h1>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Candidat</th>
                        <th>Poste</th>
                        <th>Status</th>
                        <th>Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cv as $liste_cv)
                        @if ($liste_cv->status != 'rejete')
                            <tr>
                                <td>{{ $liste_cv->dossier->candidat ?? 'N/A' }}</td>
                                <td>{{ $liste_cv->dossier->besoinPoste->poste->libelle ?? 'N/A' }}</td>
                                <td>
                                    <!-- Add a badge for the status -->
                                    @if($liste_cv->status == 'valide')
                                        <span class="badge bg-success">Valide</span>
                                    @elseif($liste_cv->status == 'rejete')
                                        <span class="badge bg-danger">Rejeté</span>
                                    @elseif($liste_cv->status == 'en_attente')
                                        <span class="badge bg-warning">En Attente</span>
                                    @else
                                        <span class="badge bg-secondary">Inconnu</span>
                                    @endif
                                </td>
                                <td> {{ $cvScores[$liste_cv->id] }} </td>
                                <td>
                                    <!-- Bouton Voir CV -->
                                    <button class="btn btn-info btn-sm" onclick="openModal('{{ Storage::url($liste_cv->dossier->cv) }}')">
                                        Voir CV
                                    </button>

                                    @if ($liste_cv->test == 'valide' && $session->get('role') == 2) <!-- Afficher le bouton Passer entretien si le CV est valide -->
                                        <a href="{{ route('entretien.create', $liste_cv->id) }}" class="btn btn-success btn-sm">Passer entretien</a>
                                    @elseif ($liste_cv->status != 'valide')
                                        @if ($session->get('role') == 5)
                                            <a href="{{ route('cv.sendToCompare', $liste_cv->id) }}" class="btn btn-warning btn-sm">Evaluer</a>
                                        @endif
                                        @if ($session->get('role') == 4)
                                            @if (!$liste_cv->informer)
                                                <a href="{{ route('cv.informer', $liste_cv->id) }}" class="btn btn-primary btn-sm">Informer</a>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal pour afficher les fichiers PDF -->
        <div id="pdfModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <iframe id="pdfIframe" style="width:100%; height: 500px;"></iframe>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Style pour la modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        transition: 0.3s ease;
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 20px;
        width: 80%;
        height: 70%;
        position: relative;
        border-radius: 10px;
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

    .btn-action {
        padding: 5px 10px;
        margin: 5px;
        cursor: pointer;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
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

    /* Modal close button hover effect */
    .close-btn:hover {
        color: #ff6347;
    }
</style>
@endpush

@push('scripts')
<script>
    // Fonction pour ouvrir la modal avec le fichier PDF
    function openModal(pdfUrl) {
        document.getElementById('pdfIframe').src = pdfUrl;
        document.getElementById('pdfModal').style.display = "block";
    }

    // Fonction pour fermer la modal
    function closeModal() {
        document.getElementById('pdfModal').style.display = "none";
        document.getElementById('pdfIframe').src = "";
    }

    // Fermer la modal en cliquant en dehors
    window.onclick = function(event) {
        var modal = document.getElementById('pdfModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endpush
