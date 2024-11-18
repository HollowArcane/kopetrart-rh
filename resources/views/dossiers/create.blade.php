@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
    }
    .header {
        border-bottom: 2px solid black;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
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
        margin-right: 10px;
    }
    .upload-zone {
        border: 2px dashed #ddd;
        padding: 20px;
        text-align: center;
        margin: 20px 0;
        border-radius: 8px;
        background-color: #f9f9f9;
        transition: background-color 0.3s ease;
    }
    .upload-zone:hover {
        background-color: #f0f0f0;
    }
    .cancel-btn {
        background: white;
        color: black;
        border: 1px solid black;
    }
    .upload-zone input[type="file"] {
        display: none;
    }
    .upload-zone button {
        background: #ddd;
        color: #333;
        padding: 10px 15px;
        border-radius: 5px;
    }
</style>

<div class="header">
    <h1>Réception du dossier de candidat</h1>
</div>

<form action="{{ route('dossiers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    {{-- ID caché --}}
    <input type="hidden" id="id" name="id">

    <div class="form-group">
        <label for="candidat">Nom du candidat</label>
        <input 
            type="text" 
            class="form-control" 
            id="candidat" 
            name="candidat" 
            placeholder="Entrez le nom"
            required
        >
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input 
            type="email" 
            class="form-control" 
            id="email" 
            name="email" 
            placeholder="Entrez l'email"
            required
        >
    </div>

    <div class="form-group">
        <label for="id_besoin_poste">Poste concerné</label>
        <select class="form-control" id="id_besoin_poste" name="id_besoin_poste" required>
            <option value="">Sélectionnez un poste</option>
            @foreach($besoin_poste as $besoin)
                <option value="{{ $besoin->id }}">
                    {{ $besoin->poste ? $besoin->poste->libelle : 'Poste non défini' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <div class="upload-zone" id="cv-upload-zone">
            <p>Déposez le CV ici ou</p>
            <input 
                type="file" 
                class="form-control" 
                id="cv" 
                name="cv" 
                accept=".pdf,.doc,.docx"
                required
            >
            <button type="button" id="cv-browse">Parcourir les fichiers</button>
        </div>
    </div>

    <div class="form-group">
        <div class="upload-zone" id="lettre-upload-zone">
            <p>Déposez la lettre de motivation ici ou</p>
            <input 
                type="file" 
                class="form-control" 
                id="lettre_motivation" 
                name="lettre_motivation" 
                accept=".pdf,.doc,.docx"
                required
            >
            <button type="button" id="lettre-browse">Parcourir les fichiers</button>
        </div>
    </div>

    {{-- Date de réception automatique --}}
    <input type="hidden" id="date_reception" name="date_reception" value="{{ date('Y-m-d') }}">
    
    {{-- Statut initial --}}
    <input type="hidden" id="statut" name="statut" value="Nouveau">

    <div class="actions">
        <button type="submit">Enregistrer le dossier</button>
        <button type="button" class="cancel-btn" onclick="window.history.back()">Annuler</button>
    </div>
</form>

<script>
    // Lier les boutons "Parcourir les fichiers" aux champs de fichier
    document.getElementById('cv-browse').addEventListener('click', function() {
        document.getElementById('cv').click();
    });

    document.getElementById('lettre-browse').addEventListener('click', function() {
        document.getElementById('lettre_motivation').click();
    });

    // Optionnel : Validation côté client si des fichiers sont bien sélectionnés
    document.querySelector('form').addEventListener('submit', function(event) {
        var cvFile = document.getElementById('cv').files[0];
        var lettreFile = document.getElementById('lettre_motivation').files[0];
        
        if (!cvFile || !lettreFile) {
            event.preventDefault();
            alert('Veuillez télécharger les fichiers CV et lettre de motivation.');
        }
    });
</script>

@endsection
