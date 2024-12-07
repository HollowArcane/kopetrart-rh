@php
    use App\Models\Staff\Staff;
    use App\Utils\Numbers;
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation Pôle Emploi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .content {
            border: 1px solid #000;
            padding: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .section {
            margin-bottom: 20px;
        }
        .page-header {
            text-align: center;
            margin-bottom: 10mm;
        }
        @media print {
            .page-break {
                page-break-before: always;
                margin-top: 25mm;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ATTESTATION DESTINÉE À PÔLE EMPLOI</h1>
        <p><strong>POUR L'ATTRIBUTION DES ALLOCATIONS CHÔMAGE</strong></p>
    </div>

    <div class="content page-break">
        <div class="section">
            <h2>1. IDENTIFICATION DE L'ENTREPRISE</h2>
            <p><strong>Raison sociale :</strong> KOPETRART <br>
            <strong>N° SIRET :</strong> 123 456 789 00025<br>
        </div>

        <div class="section">
            <h2>2. IDENTIFICATION DU SALARIÉ</h2>
            <p><strong>Nom :</strong> {{ $staff->last_name }} <br>
            <strong>Prénom :</strong> {{ $staff->first_name }} <br>
            <strong>Date de naissance :</strong> {{ $staff->date_birth }} <br>
            <strong>Numéro de Sécurité Sociale :</strong> 2 90 03 75 123 456 78</p>
        </div>

        <div class="section">
            <h2>3. DÉTAILS DU CONTRAT DE TRAVAIL</h2>
            <p><strong>Nature du contrat :</strong> {{ $staff->staff_contract }} <br>
            <strong>Date d'entrée :</strong>  {{ $staff->d_date_contract_start }} <br>
            <strong>Date de sortie :</strong> {{ $staff->date_validated }} <br>
            <strong>Motif de la rupture :</strong> {{ $staff->contract_breach_type }}</p>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="content page-break">
        <div class="section">
            <h2>4. RÉMUNÉRATION</h2>
            <p><strong>Salaire brut des 12 derniers mois :</strong><br>
            <table style="width: 100%;">
            @foreach ($salaries as $date => $salary)
            <tr>
                <td>{{ $date }}: </td>
                <td align="right">{{ Numbers::format($salary) }} Ar</td>
            </tr>
            @endforeach
            </table>
        </div>

        <div class="section">
            <h2>5. PÉRIODE D'EMPLOI ET DURÉE DE TRAVAIL</h2>
            <p><strong>Durée totale :</strong> {{ Staff::format_seniority($staff, 'an', 'mois', 'jour') }} <br>
            <strong>Durée hebdomadaire :</strong> 40 heures<br>
            <strong>Type de temps de travail :</strong> Temps complet</p>
        </div>
    </div>

    <div class="footer">
        <p>Fait à Antananarivo, le {{ $staff->date_validated }}</p>
        <p>Signature et cachet de l'entreprise</p>
    </div>
</body>
</html>
