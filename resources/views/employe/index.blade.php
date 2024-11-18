@extends('layouts.app')

@section('content')
    <h1>Liste des Employés</h1>

    @php
        $session = session();
    @endphp

    @if ($session->get('role') == 3 && isset($id_besoins))
        <p><a href="{{ route('annonce.create', $id_besoins) }}" class="btn btn-warning">Faire une Annonce</a></p>
    @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Département</th>
                    <th>Poste</th>
                    <th>Date d'Embauche</th>
                    <th>Contrat</th>
                    @if ($session->get('role') == 3 && isset($id_besoins))
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($employe as $liste_employe)
                    <tr>
                        <td>{{ $liste_employe->candidat }}</td>
                        <td>{{ $liste_employe->departement }}</td>
                        <td>{{ $liste_employe->poste }}</td> <!-- Correction ici -->
                        <td>{{ $liste_employe->date_embauche }}</td>
                        <td>{{ $liste_employe->type_contrat }}</td>

                        @if ($session->get('role') == 3 && isset($id_besoins))
                            <td>
                                <a href="{{ route('employe.promotion', ['id_employe' => $liste_employe->id_employe, 'candidat' => $liste_employe->candidat, 'postePromotion' => $liste_employe->id_poste]) }}" class="btn btn-link">Promotion</a>
                            </td>
                        @endif

                        @if ($session->get('role') == 2 && isset($id_besoins))

                        <td>
                            <a href="{{ route('employe.edit', $liste_employe->id_employe) }}" class="btn btn-warning">Renouvellement</a>
                        </td>

                        @endif

                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
