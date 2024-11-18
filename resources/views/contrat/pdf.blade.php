<!DOCTYPE html>
<html>
<head>
    <title>Contract PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2> Détails de Contrat </h2>
    <p><strong> Date de Début: </strong> {{ $contratCv->date_debut }}</p>
    <p><strong> Période (months): </strong> {{ $contratCv->periode }}</p>
    <p><strong> Salaire Proposé: </strong> {{ number_format($contratCv->salaire_propose, 2) }} </p>
    <p><strong> Notes: </strong> {{ $contratCv->notes_sup }}</p>

    <h3> Détails de Candidat </h3>
    <p><strong> Nom: </strong> {{ $contratCv->cv->dossier->candidat }}</p>
    <p><strong> Email: </strong> {{ $contratCv->cv->dossier->email }}</p>
    <p><strong> Position: </strong> {{ $contratCv->cv->dossier->besoinPoste->poste->libelle }}</p>
</body>
</html>
