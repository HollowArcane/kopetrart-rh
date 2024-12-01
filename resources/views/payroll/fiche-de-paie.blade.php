@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h1 class="h4 mb-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Fiche de paie du {{ \Carbon\Carbon::parse($ref_date)->formatLocalized('%d %B %Y') }}
                    </h1>
                    <div class="btn-group" role="group">
                        <button class="btn btn-light btn-sm" onclick="window.location.href='{{ route('payroll.export_pdf', ['id' => $staff->id]) }}'">
                            <i class="fas fa-file-pdf me-1"></i>Exporter PDF
                        </button>
                        <button class="btn btn-light btn-sm">
                            <i class="fas fa-download me-1"></i>Télécharger
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded mb-3">
                                <h5 class="text-primary mb-3">Informations Personnelles</h5>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Matricule</div>
                                    <div class="col-7">MAT-000{{ $staff->id }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Nom et Prénoms</div>
                                    <div class="col-7 text-primary">{{ $staff->first_name }} {{ $staff->last_name }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Fonction</div>
                                    <div class="col-7">{{ $staff->staff_position }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">N° CNaPS</div>
                                    <div class="col-7">700-410-33{{ $staff->id }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Date d'embauche</div>
                                    <div class="col-7">
                                        <span class="badge bg-info">{{ $staff->d_date_contract_start }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5 fw-bold">Ancienneté</div>
                                    <div class="col-7">
                                        {{ $seniority['years'] }} an(s) 
                                        {{ $seniority['months'] }} mois 
                                        {{ $seniority['days'] }} jour(s)
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded mb-3">
                                <h5 class="text-primary mb-3">Détails de Rémunération</h5>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Salaire de base</div>
                                    <div class="col-7 text-danger fw-bold">
                                        {{ number_format($staff->d_salary, 2, ',', ' ') }} Ar
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Taux journaliers</div>
                                    <div class="col-7">
                                        {{ number_format($daily_rate, 2, ',', ' ') }} Ar
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Taux horaires</div>
                                    <div class="col-7">
                                        {{ number_format($hourly_rate, 2, ',', ' ') }} Ar
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5 fw-bold">Indice</div>
                                    <div class="col-7">
                                        {{ number_format($indice, 2, ',', ' ') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Désignations</th>
                                            <th>Nombre</th>
                                            <th>Taux</th>
                                            <th class="text-end">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalEarnings = 0;
                                            $overtimeEarnings = 0;
                                        @endphp
                                        
                                        @foreach([
                                            ['label' => 'Valeur', 'amount' => $staff->d_salary],
                                            ['label' => 'Absences déductibles', 'amount' => $daily_rate],
                                            ['label' => 'Primes de rendement', 'amount' => $daily_rate],
                                            ['label' => 'Primes d\'ancienneté', 'amount' => $daily_rate],
                                            [
                                                'label' => 'Heures supplémentaires majorées de 30%', 
                                                'hours' => $monthly_overtime && $monthly_overtime->total_first_8_hours ? $monthly_overtime->total_first_8_hours : 0,
                                                'rate' => $hourly_rate * 1.3,
                                                'amount' => $monthly_overtime && $monthly_overtime->total_first_8_hours ? ($hourly_rate * 1.3) * $monthly_overtime->total_first_8_hours : 0
                                            ],
                                            [
                                                'label' => 'Heures supplémentaires majorées de 40%', 
                                                'hours' => $monthly_overtime && $monthly_overtime->total_last_12_hours ? $monthly_overtime->total_last_12_hours : 0,
                                                'rate' => $hourly_rate * 1.4,
                                                'amount' => $monthly_overtime && $monthly_overtime->total_last_12_hours ? ($hourly_rate * 1.4) * $monthly_overtime->total_last_12_hours : 0
                                            ],
                                            [
                                                'label' => 'Heures supplémentaires majorées de 50%', 
                                                'hours' => $monthly_overtime && $monthly_overtime->total_weekend ? $monthly_overtime->total_weekend : 0,
                                                'rate' => $hourly_rate * 1.5,
                                                'amount' => $monthly_overtime && $monthly_overtime->total_weekend ? ($hourly_rate * 1.5) * $monthly_overtime->total_weekend : 0
                                            ],
                                            [
                                                'label' => 'Heures supplémentaires majorées de 100%', 
                                                'hours' => $monthly_overtime && $monthly_overtime->total_holiday ? $monthly_overtime->total_holiday : 0,
                                                'rate' => $hourly_rate * 2,
                                                'amount' => $monthly_overtime && $monthly_overtime->total_holiday ? ($hourly_rate * 2) * $monthly_overtime->total_holiday : 0
                                            ],
                                            [
                                                'label' => 'Majoration pour heures de nuit',
                                                'hours' => 0,
                                                'rate' => $hourly_rate * 0.3,
                                                'amount' => 0
                                            ],
                                            ['label' => 'Rappels sur période antérieure', 'amount' => $salary_brut->res_rappel_salary ?? 0],
                                            ['label' => 'Droits de congés', 'amount' => $daily_rate],
                                            ['label' => 'Droits de préavis', 'amount' => $daily_rate],
                                            ['label' => 'Indemnités de licenciement', 'amount' => $daily_rate]
                                        ] as $item)
                                            <tr>
                                                <td>{{ $item['label'] }}</td>
                                                <td>
                                                    @if(isset($item['hours']))
                                                        {{ $item['hours'] }}
                                                    @else
                                                        @if($item['label'] === 'Valeur')
                                                            1 mois
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($item['rate']))
                                                        {{ number_format($item['rate'], 2, ',', ' ') }}
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($item['amount'], 2, ',', ' ') }}
                                                </td>
                                                @php
                                                    if(isset($item['amount'])) {
                                                        $totalEarnings += $item['amount'];
                                                        if(strpos($item['label'], 'Heures supplémentaires') !== false) {
                                                            $overtimeEarnings += $item['amount'];
                                                        }
                                                    }
                                                @endphp
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <td colspan="3" class="fw-bold text-end">Total Gains</td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($totalEarnings, 2, ',', ' ') }}
                                            </td>
                                        </tr>
                                        <tr class="table-info">
                                            <td colspan="3" class="fw-bold text-end">Dont Heures Supplémentaires</td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($overtimeEarnings, 2, ',', ' ') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Previous table and sections remain the same -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    Retenues
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Retenue CNaPS 1%</td>
                                            <td class="text-end">
                                                {{ number_format(min($cnaps_ostie->res_cnaps_amount, 20000), 2, ',', ' ') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Retenue sanitaire</td>
                                            <td class="text-end">
                                                {{ number_format($cnaps_ostie->res_ostie_amount, 2, ',', ' ') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-light">
                                                <strong>Tranches IRSA</strong>
                                            </td>
                                        </tr>
                                        @php
                                            $irsaTransches = [
                                                ['label' => 'INF 350 000', 'rate' => '0%', 'amount' => $tranche1],
                                                ['label' => 'DE 350 001 à 400 000', 'rate' => '5%', 'amount' => $tranche2],
                                                ['label' => 'DE 400 001 à 500 000', 'rate' => '10%', 'amount' => $tranche3],
                                                ['label' => 'DE 500 001 à 600 000', 'rate' => '15%', 'amount' => $tranche4],
                                                ['label' => 'SUP 600 000', 'rate' => '20%', 'amount' => $tranche5]
                                            ];
                                        @endphp
                                        @foreach($irsaTransches as $tranche)
                                            <tr>
                                                <td>Tranche IRSA {{ $tranche['label'] }}</td>
                                                <td class="text-end">
                                                    {{ number_format($tranche['amount'], 2, ',', ' ') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-danger">
                                            <td><strong>TOTAL IRSA</strong></td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($total_irsa, 2, ',', ' ') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    Récapitulatif
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-8">Salaire Brut</div>
                                        <div class="col-4 text-end fw-bold">
                                            {{ number_format($salary_brut->res_monthly_gross_salary, 2, ',', ' ') }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-8">Total des Retenues</div>
                                        <div class="col-4 text-end fw-bold text-danger">
                                            {{ number_format($total_retenue, 2, ',', ' ') }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-8">Autres Indemnités</div>
                                        <div class="col-4 text-end fw-bold text-success">
                                            {{ number_format($compensation, 2, ',', ' ') }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-8">
                                            <h4 class="text-primary">Net à Payer</h4>
                                        </div>
                                        <div class="col-4 text-end">
                                            <h4 class="text-success fw-bold">
                                                {{ number_format($net_a_payer, 2, ',', ' ') }} Ar
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-muted small mt-2">
                                                Net à payer calculé : Salaire Brut - Total des Retenues + Autres Indemnités
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer text-muted text-center">
                    <small>Document généré le {{ now()->formatLocalized('%d %B %Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection