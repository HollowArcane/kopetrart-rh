@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Liste des Besoins Talent</h1>
        
        @php
            $session = session();
        @endphp

        <!-- Formulaire de recherche -->
        <form method="GET" action="{{ route('besoin_poste.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="poste" class="form-label">Poste</label>
                    <select name="poste" id="poste" class="form-select">
                        <option value="">Sélectionnez un poste</option>
                        @foreach($postes as $poste)
                            <option value="{{ $poste->id }}" {{ request('poste') == $poste->id ? 'selected' : '' }}>
                                {{ $poste->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="departement" class="form-label">Département</label>
                    <select name="departement" id="departement" class="form-select">
                        <option value="">Sélectionnez un département</option>
                        @foreach($departements as $departement)
                            <option value="{{ $departement->id }}" {{ request('departement') == $departement->id ? 'selected' : '' }}>
                                {{ $departement->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </div>
        </form>

        <!-- Result Table -->
        @if(count($besoins_talent) == 0)
            <p class="text-center text-muted">Aucun besoin trouvé.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Poste</th>
                            <th>Département</th>
                            <th>Urgence</th>
                            <th>Date Requise</th>
                            <th>Status</th>
                            @if ($session->get('role') == 3) <!-- Vérifiez si le rôle est 'PDG' -->
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($besoins_talent as $besoin)
                            <tr>
                                <td>{{ $besoin->poste }}</td>
                                <td>{{ $besoin->departement }}</td>
                                <td>{{ $besoin->urgence }}</td>
                                <td>{{ $besoin->date_requis }}</td>
                                <td>
                                    <span class="badge {{ $besoin->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $besoin->status ? 'En cours' : 'Inactif' }}
                                    </span>
                                </td>
                                @if ($session->get('role') == 3)
                                    <td>
                                        <a href="{{ route('employe', $besoin->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-chart-line"></i> Analyse
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection