@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Etat de Paie</h1>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-calendar me-1"></i>
            Générer un état de paie
        </div>
        <div class="card-body">
            <form action="{{ route('payroll.generate') }}" method="post">
                @csrf
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="date-reference" class="form-label">Date de référence</label>
                            <input type="date" name="date-reference" id="date-reference" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-export me-1"></i>Générer l'état
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($payrollData))
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Détail de la paie
        </div>
        <div class="card-body" style="position: relative;">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th>Date</th>
                            <th>Matricule</th>
                            <th>Num CNAPS</th>
                            <th>Nom et Prénom</th>
                            <th>Date d'embauche</th>
                            <th>Absence</th>
                            <th>Categorie</th>
                            <th>Fonction</th>
                            <th>Salaire de base</th>
                            <th>Retenue absence</th>
                            <th>Salaire base mensuel</th>
                            <th>Indemnité</th>
                            <th>Rappel</th>
                            <th>Autres</th>
                            <th>Heure Sup</th>
                            <th>Salaire brut</th>
                            <th>CNAPS 1%</th>
                            <th>CNAPS 8%</th>
                            <th>OSTIE 1%</th>
                            <th>OSTIE 8%</th>
                            <th>Revenu imposable</th>
                            <th>Impot Du</th>
                            <th>Enfant</th>
                            <th>Montant</th>
                            <th>IGR Net</th>
                            <th>Autres retenues</th>
                            <th>Total retenues</th>
                            <th>Salaire net</th>
                            <th>Avance</th>
                            <th>Net a payer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrollData as $data)
                            <tr>
                                <td>{{ $referenceDate }}</td>
                                <td>{{ $data['staff']->id }}</td>
                                <td>700.410.33{{ $data['staff']->id }}</td>
                                <td>
                                    <a href="{{ route('payroll.staff_payroll', ['id' => $data['staff']->id]) }}" class="text-decoration-none">
                                        {{ $data['staff']->first_name }} {{ $data['staff']->last_name }}
                                    </a>                        
                                </td>
                                <td>{{ $data['staff']->d_date_contract_start }}</td>
                                <td>{{ $data['absence_details']->total_absence_days ?? 0 }}</td>
                                <td>{{ $data['staff']->department}}</td>
                                <td>{{ $data['staff']->staff_position}}</td>
                                <td>{{ number_format($data['staff']->d_salary, 2) }}</td>
                                <td>{{ number_format($data['absence_details']->detention_amount ?? 0, 2) }}</td>
                                <td>{{ number_format($data['salary_brut']->res_base_salary, 2) }}</td>
                                <td>{{ number_format($data['compensation'], 2) }}</td>
                                <td>{{ number_format($data['salary_brut']->res_rappel_salary, 2) }}</td>
                                <td>0.00</td>
                                <td>{{ number_format($data['monthly_overtime']->monthly_total_overtime_amount ?? 0, 2) }}</td>
                                <td>{{ number_format($data['salary_brut']->res_monthly_gross_salary, 2) }}</td>
                                <td>{{ number_format($data['cnaps_ostie']->res_cnaps_amount, 2) }}</td>
                                <td>{{ number_format($data['salary_brut']->res_monthly_gross_salary * 0.08, 2) }}</td>
                                <td>{{ number_format($data['cnaps_ostie']->res_ostie_amount, 2) }}</td>
                                <td>{{ number_format($data['salary_brut']->res_monthly_gross_salary * 0.08, 2) }}</td>
                                <td>{{ number_format($data['revenue_imposable'], 2) }}</td>
                                <td>{{ number_format($data['impot_du'], 2) }}</td>
                                <td>0</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>{{ number_format($data['total_irsa'], 2) }}</td>
                                <td>{{ number_format($data['total_retenue'], 2) }}</td>
                                <td>{{ number_format($data['salary_net'], 2) }}</td>
                                <td>{{ number_format($data['avance'], 2) }}</td>
                                <td>{{ number_format($data['net_a_payer'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: rgba(0,0,0,.2) transparent;
    }
    .table-responsive::-webkit-scrollbar {
        width: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: transparent;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,.2);
        border-radius: 4px;
    }
</style>
@endpush