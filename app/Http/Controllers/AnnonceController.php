<?php

namespace App\Http\Controllers;
use App\Models\AnnonceModel;
use App\Models\Besoin_posteModel;
use App\Models\Moyenne_commModel;
use App\Models\Notification;
use Illuminate\Http\Request;
class AnnonceController extends Controller {
	public function index() {
		$annonce = AnnonceModel::getAnnonce();
        $moyenne_comm = Moyenne_commModel::getAllMoyenne_comm();

		return view('annonce.index', compact('annonce', 'moyenne_comm'));
	}

	public function create($id_besoins) {
        $besoins  = Besoin_posteModel::getBesoinsById($id_besoins);
		return view('annonce.create', compact('besoins'));
	}

	public function store(Request $request) {
		$annonce = new AnnonceModel();
		$annonce->setId($request->input('id'));
		$annonce->setId_besoin_poste($request->input('id_besoin_poste'));
        $annonce->setIs_validate($request->input('is_validate'));
		$annonce->createAnnonce();

        $notification = new Notification();
        $notification->title = 'Nouvelle Demande de Communication de Recrutement';
        $notification->message = 'Une communication de recrutement à été demandé';
        $notification->redirection = '/annonce';
        $notification->id_role = 4; // responsable de communication
        $notification->save();

		return redirect()->route('dashboard')->with('success', 'Annonce créé avec succès.');
	}

	public function edit($id) {
		$annonce = AnnonceModel::getAnnonceById($id);
		$besoin_poste = Besoin_posteModel::getAllBesoin_poste();
		return view('annonce.edit', compact('annonce'));
	}
	public function update(Request $request, $id) {
		$annonce = new AnnonceModel();
		$annonce->setId_Annonce($id);
		$annonce->setId($request->input('id'));
		$annonce->setId_besoin_poste($request->input('id_besoin_poste'));
		$annonce->updateAnnonce();
		return redirect()->route('annonce.index')->with('success', 'Annonce mis à jour avec succès.');
	}
	public function destroy($id) {
		$annonceModel = new AnnonceModel();
		$annonceModel->setId($id);
		$annonceModel->deleteAnnonce();
		return redirect()->route('annonce.index')->with('success', 'Annonce supprimé avec succès.');
	}
}
