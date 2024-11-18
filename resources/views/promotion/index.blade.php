@extends('layouts.app')

@section('content')
    <h1>Liste des Promotions</h1>
    @php
    $session = session();
    @endphp

    <table class="table">
        <thead>
            <tr>
                <th>nom_employe</th>
                <th>email_employe</th>
                <th>departement_actuel</th>
                <th>poste_actuel</th>
                <th>nouveau_poste</th>
                <th>salaire_actuel</th>
                <th>nouveau_salaire</th>
                <th>date_promotion</th>
                <th>status</th>
                @if ($session->get('role') == 1) <!-- Vérifiez si le rôle est 'PDG' -->
                <th>Action</th>
                @endif

            </tr>
        </thead>
        <tbody>
            @foreach($promotion as $liste_promotion)
                <tr>
                    <td>{{ $liste_promotion->nom_employe }}</td>
                    <td>{{ $liste_promotion->email_employe }}</td>
                    <td>{{ $liste_promotion->departement_actuel }}</td>
                    <td>{{ $liste_promotion->poste_actuel }}</td>
                    <td>{{ $liste_promotion->nouveau_poste }}</td>
                    <td>{{ $liste_promotion->salaire_actuel }}</td>
                    <td>{{ $liste_promotion->nouveau_salaire }}</td>
                    <td>{{ $liste_promotion->date_promotion }}</td>
                    <td>
                        @if($liste_promotion->statut_promotion)
                            Valide
                        @else
                            Non validé
                        @endif
                    </td>
                    @if ($session->get('role') == 1) <!-- Vérifiez si le rôle est 'PDG' -->

                    <td>

                        <!-- Bouton pour valider la promotion -->
                        @if(!$liste_promotion->statut_promotion) <!-- Si non validée -->
                            <form action="{{ route('promotion.updateStatus', $liste_promotion->id_promotion) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">Valider</button>
                            </form>
                        @endif

                    </td>

                    <td>
                        @if(!$liste_promotion->statut_promotion) <!-- Si non validée -->
                        <form action="{{ route('promotion.destroy', $liste_promotion->id_promotion) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce promotion?')">Refuser</button>
                        </form>
                        @endif

                    </td>

                    @endif

                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
