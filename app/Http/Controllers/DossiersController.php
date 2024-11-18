<?php

namespace App\Http\Controllers;
use App\Models\DossiersModel;
use App\Models\Besoin_posteModel;
use App\Models\Notification;
use Illuminate\Http\Request;
class DossiersController extends Controller {
	public function index() {
		$dossiers = DossiersModel::with('besoinPoste')->get();
    	return view('dossiers.index', compact('dossiers'));
	}

	public function create() {
		$besoin_poste = Besoin_posteModel::with('poste')->get();
    	return view('dossiers.create', compact('besoin_poste'));
	}

	public function store(Request $request) {
		// Création d'un nouvel objet DossiersModel
		$dossier = new DossiersModel();
		$dossier->setCandidat($request->input('candidat'));
		$dossier->setEmail($request->input('email'));
		$dossier->setId_besoin_poste($request->input('id_besoin_poste'));
		$dossier->setDate_reception($request->input('date_reception'));
		$dossier->setStatut('Nouveau'); // Statut par défaut lors de la création du dossier

		// Gestion de l'upload du CV
		if ($request->hasFile('cv')) {
			$cvPath = $request->file('cv')->store('dossiers/cv', 'public'); // Stocke le fichier dans le dossier 'storage/app/public/dossiers/cv'
			$dossier->setCv($cvPath); // Enregistrement du chemin du fichier dans la base de données
		}

		// Gestion de l'upload de la lettre de motivation
		if ($request->hasFile('lettre_motivation')) {
			$lettrePath = $request->file('lettre_motivation')->store('dossiers/lettres', 'public'); // Stocke le fichier dans 'storage/app/public/dossiers/lettres'
			$dossier->setLettre_motivation($lettrePath); // Enregistrement du chemin du fichier dans la base de données
		}

		// Sauvegarde du dossier dans la base de données
		$dossier->createDossier();

        $notification = new Notification();
        $notification->title = 'Nouveau Dossiers';
        $notification->message = 'Un dossier à été reçu';
        $notification->redirection = '/dossiers';
        $notification->id_role = 5; // chargé de recrutement
        $notification->save();

		return redirect()->route('dossiers.index')->with('success', 'Dossier créé avec succès.');
	}


	public function edit($id) {
		$dossier = DossiersModel::getDossierById($id);
		$besoin_poste = Besoin_posteModel::getAllBesoin_poste();
		return view('dossiers.edit', compact('dossier', 'besoin_poste'));
	}
	public function update(Request $request, $id) {
		$dossiers = new DossiersModel();
		$dossiers->setId($request->input('id'));
		$dossiers->setCandidat($request->input('candidat'));
		$dossiers->setEmail($request->input('email'));
		$dossiers->setId_besoin_poste($request->input('id_besoin_poste'));
		$dossiers->setDate_reception($request->input('date_reception'));
		$dossiers->setStatut($request->input('statut'));
		$dossiers->setCv($request->input('cv'));
		$dossiers->setLettre_motivation($request->input('lettre_motivation'));
		$dossiers->updateDossier();
		return redirect()->route('dossiers.index')->with('success', 'Dossier mis à jour avec succès.');
	}
	public function destroy($id) {
		$dossierModel = new DossiersModel();
		$dossierModel->setId($id);
		$dossierModel->deleteDossier();
		return redirect()->route('dossiers.index')->with('success', 'Dossier supprimé avec succès.');
	}
	public function refuser(Request $request, $id)
    {
        $dossier = DossiersModel::find($id);

        if ($dossier) {
            $dossier->statut = 'Refusé';
            $dossier->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
