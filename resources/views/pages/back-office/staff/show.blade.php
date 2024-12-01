@extends('layouts.app')

@section('content')
    <h1> Détails Employé </h1>

    @php
        use \App\Models\Staff\Staff;
        $session = session();
    @endphp

    @include('includes.message')

    <h3> Informations Personnels </h3>
    <table class="table table-striped table-hover">
        <tbody>
                <tr>
                    <th> Nom </th>
                    <td> {{ $staff->last_name }} </td>
                </tr>
                <tr>
                    <th> Prénom </th>
                    <td> {{ $staff->first_name }} </td>
                </tr>

                <tr>
                    <th> Email </th>
                    <td> {{ $staff->email }} </td>
                </tr>

                <tr>
                    <th> Date de Naissance </th>
                    <td> {{ $staff->date_birth }} </td>
                </tr>

        </tbody>
    </table>

    <h3> Contrat </h3>
    <table class="table table-striped table-hover">
        <tbody>

                <tr>
                    <th> Poste </th>
                    <td> {{ $staff->staff_position }} </td>
                </tr>
                <tr>
                    <th> Département </th>
                    <td> {{ $staff->department }} </td>
                </tr>
                <tr>
                    <th> Date d'embauche </th>
                    <td> {{ $staff->d_date_contract_start }} </td>
                </tr>
                <tr>
                    <th> Type de Contrat </th>
                    <td> {{ $staff->staff_contract }} </td>
                </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div>
            <div>
                <h3> Rupture Contractuelle </h3>
            </div>
            <div>
                @if ($can_breach && in_array(session('role'), [1 /* PDG */ , 3 /* RE */ ]))
                    @if (session('role') == 3 /* RE */)
                        <a data-mdb-tooltip-init title="Démission" href="/staff/{{ $staff->id }}/contract-breach/create/1" class="btn btn-secondary"> <i class="fa fa-person-walking-arrow-right"></i> </a>
                    @endif
                    @if (session('role') == 1 /* PDG */)
                        <a data-mdb-tooltip-init title="Licencier" href="/staff/{{ $staff->id }}/contract-breach/create/2" class="btn btn-secondary"> <i class="fa fa-user-slash"></i> </a>
                    @endif
                    @if ((new \DateTime())->diff(new \DateTime($staff->date_birth))->y >= Staff::retire_age())
                        <a data-mdb-tooltip-init title="Mise à la Retraite" href="/staff/{{ $staff->id }}/contract-breach/create/3" class="btn btn-secondary"> <i class="fa fa-chair"></i> </a>
                        @endif
                    @if ($staff->d_id_staff_contract == 2 /* CDI */)
                        <a data-mdb-tooltip-init title="Rupture Conventionelle" href="/staff/{{ $staff->id }}/contract-breach/create/4" class="btn btn-secondary"> <i class="fa fa-handshake"></i> </a>
                    @endif
                @endif
            </div>
        </div>

        @if ($session->get('role') == 1 /* PDG */)
            <div>
                <div>
                    <h3> Progression Contractuelle </h3>
                </div>
                <div class="text-end">
                    <a data-mdb-tooltip-init title="Promouvoir" href="#" class="btn btn-secondary"> <i class="fa fa-star"></i> </a>
                    <a data-mdb-tooltip-init title="Renouveler Contrat" href="#" class="btn btn-secondary"> <i class="fa fa-file-contract"></i> </a>
                </div>
            </div>
        @endif
        </div>
@endsection
