<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat de Travail</title>
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
        }
        .content {
            border: 1px solid #000;
            padding: 20px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CERTIFICAT DE TRAVAIL</h1>
    </div>

    <div class="content">
        <p>Je soussigné, Monsieur Jean Dupont, Directeur des Ressources Humaines de la société KOPETRART, Antananarivo, certifie que :</p>

        <p><strong>Monsieur/Madame {{ $staff->first_name }} {{ $staff->last_name }}</strong><br>
        Née le {{ $staff->date_birth }}<br>
        {{-- Demeurant : 42 avenue des Champs, 75015 Paris</p> --}}

        <p>A été employée au sein de notre entreprise :</p>

        <ul>
            <li>Date d'entrée : {{ $staff->d_date_contract_start }}</li>
            <li>Date de sortie :  {{ $staff->date_validated }} </li>
            <li>Poste occupé : {{ $staff->staff_position }}</li>
        </ul>

        <p>Pendant cette période, Monsieur/Madame {{ $staff->first_name }} {{ $staff->last_name }} a exercé les missions suivantes :</p>

        <ul>
        @foreach ($tasks as $task)
            <li> {{ $task->label }} </li>
        @endforeach
        </ul>

        <p>Le contrat de travail a pris fin d'un(e) {{ $staff->contract_breach_type }}.</p>

        <p>Ce certificat lui est remis pour faire valoir ce que de droit.</p>

        <div class="signature">
            <p>Fait à Antananarivo, le {{ $staff->date_validated }}</p>
            <p>Jean Dupont<br>
            Directeur des Ressources Humaines</p>
        </div>
    </div>
</body>
</html>
