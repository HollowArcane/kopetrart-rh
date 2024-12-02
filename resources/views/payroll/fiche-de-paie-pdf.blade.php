<!DOCTYPE html>
<html>
<head>
    <title>Fiche de Paie - {{ $staff->first_name }} {{ $staff->last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Fiche de Paie - {{ \Carbon\Carbon::parse($ref_date)->formatLocalized('%B %Y') }}</h1>
        </div>

        <div class="section">
            <h3>Informations Personnelles</h3>
            <table class="table">
                <tr>
                    <td>Matricule</td>
                    <td>MAT-000{{ $staff->id }}</td>
                    <td>Nom et Prénoms</td>
                    <td>{{ $staff->first_name }} {{ $staff->last_name }}</td>
                </tr>
                <tr>
                    <td>Fonction</td>
                    <td>{{ $staff->staff_position }}</td>
                    <td>Date d'embauche</td>
                    <td>{{ $staff->d_date_contract_start }}</td>
                </tr>
                <tr>
                    <td>Ancienneté</td>
                    <td colspan="3">
                        {{ $seniority['years'] }} an(s) 
                        {{ $seniority['months'] }} mois 
                        {{ $seniority['days'] }} jour(s)
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Gains</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Nombre</th>
                        <th>Taux</th>
                        <th class="text-right">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalEarnings = 0;
                        $overtimeEarnings = 0;
                        $earnings = [
                            ['label' => 'Salaire de base', 'amount' => $staff->d_salary],
                            ['label' => 'Heures supplémentaires 30%', 'amount' => $monthly_overtime && $monthly_overtime->total_first_8_hours ? ($hourly_rate * 1.3) * $monthly_overtime->total_first_8_hours : 0],
                            ['label' => 'Heures supplémentaires 40%', 'amount' => $monthly_overtime && $monthly_overtime->total_last_12_hours ? ($hourly_rate * 1.4) * $monthly_overtime->total_last_12_hours : 0],
                            ['label' => 'Heures supplémentaires 50%', 'amount' => $monthly_overtime && $monthly_overtime->total_weekend ? ($hourly_rate * 1.5) * $monthly_overtime->total_weekend : 0],
                            ['label' => 'Heures supplémentaires 100%', 'amount' => $monthly_overtime && $monthly_overtime->total_holiday ? ($hourly_rate * 2) * $monthly_overtime->total_holiday : 0],
                        ];
                    @endphp
                    @foreach($earnings as $item)
                        <tr>
                            <td>{{ $item['label'] }}</td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{ number_format($item['amount'], 2, ',', ' ') }}</td>
                        </tr>
                        @php
                            $totalEarnings += $item['amount'];
                        @endphp
                    @endforeach
                    <tr class="fw-bold">
                        <td colspan="3" class="text-right">Total Gains</td>
                        <td class="text-right">{{ number_format($totalEarnings, 2, ',', ' ') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Retenues</h3>
            <table class="table">
                <tr>
                    <td>Retenue CNaPS</td>
                    <td class="text-right">{{ number_format($cnaps_ostie->res_cnaps_amount, 2, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td>Retenue IRSA</td>
                    <td class="text-right">{{ number_format($total_irsa, 2, ',', ' ') }}</td>
                </tr>
                <tr class="fw-bold">
                    <td>Total Retenues</td>
                    <td class="text-right">{{ number_format($total_retenue, 2, ',', ' ') }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Récapitulatif</h3>
            <table class="table">
                <tr>
                    <td>Salaire Brut</td>
                    <td class="text-right">{{ number_format($salary_brut->res_monthly_gross_salary, 2, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td>Total Retenues</td>
                    <td class="text-right">{{ number_format($total_retenue, 2, ',', ' ') }}</td>
                </tr>
                <tr class="fw-bold">
                    <td>Net à Payer</td>
                    <td class="text-right">{{ number_format($net_a_payer, 2, ',', ' ') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Document généré le {{ now()->formatLocalized('%d %B %Y') }}</p>
        </div>
    </div>
</body>
</html>