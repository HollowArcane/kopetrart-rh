<?php

namespace App\Http\Controllers;
use App\Models\Besoin_posteModel;
use App\Models\ContratModel;
use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\GenreModel;
use App\Models\Notification;
use App\Models\PrioriteModel;
use App\Models\PostesModel;
use App\Models\Talent_poste_besoinModel;
use App\Models\TalentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Besoin_posteController extends Controller {
	public function index(Request $request)
    {
        $posteId = $request->input('poste');
        $departementId = $request->input('departement');
        $besoins_talent = Besoin_posteModel::getBesoinsTalentWithFilter(
            $posteId ?: null,
            $departementId ?: null,
        );

        $departements = DepartementModel::getAllDepartement();
        $postes = PostesModel::getAllPostes();
        $talent = TalentModel::getAllTalent();

        // Retourner la vue avec les données
        return view('besoin_poste.index', compact('besoins_talent', 'departements', 'postes', 'talent'));
    }


	public function create() {
		$contrat = ContratModel::getAllContrat();
		$genre = GenreModel::getAllGenre();
		$priorite = PrioriteModel::getAllPriorite();
		$postes = PostesModel::getAllPostes();
        $talent = TalentModel::getAllTalent();

		return view('besoin_poste.create', compact('contrat', 'genre', 'priorite', 'postes','talent'));
	}

    public function store(Request $request)
    {
        try {
            // Récupérer les données de la requête
            $besoin_poste = new Besoin_posteModel();
            $besoin_poste->setId($request->input('id'));
            $besoin_poste->setId_poste($request->input('id_poste'));
            $besoin_poste->setId_genre($request->input('id_genre'));
            $besoin_poste->setId_contrat($request->input('id_contrat'));
            $besoin_poste->setAgemin($request->input('agemin'));
            $besoin_poste->setFinvalidite($request->input('finvalidite'));
            $besoin_poste->setPriorite($request->input('priorite'));
            $besoin_poste->setInfo_sup($request->input('info_sup'));
            $besoin_poste->setPersonneltrouver($request->input('personneltrouver'));

            $id_besoins = $besoin_poste->createBesoin_poste();

            // creating new notification
            $notification = new Notification();
            $notification->title = 'Nouveau Besoin';
            $notification->message = 'Un nouveau besoin à été mis en évidence';
            $notification->redirection = '/employe/'.$id_besoins;
            $notification->id_role = 3; // responsable d'equipe
            $notification->save();

            // Traiter chaque talent
            $talents = json_decode($request->input('talents_data'), true);
            foreach ($talents as $talent) {
                $talent_poste_besoin = new Talent_poste_besoinModel();
                $talent_poste_besoin->setId_talent($talent['id']);
                $talent_poste_besoin->setId_besoin_poste($id_besoins);
                $talent_poste_besoin->setExpmin($talent['expmin']);
                $talent_poste_besoin->setExpmax($talent['expmax']);
                $talent_poste_besoin->setEtude($talent['etude']);
                $talent_poste_besoin->createTalent_poste_besoin();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Besoin de poste créé avec succès',
                'redirect' => route('besoin_poste.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création du besoin de poste',
                'errors' => $e->getMessage()
            ], 422);
        }
    }


	public function edit($id) {
		$besoin_poste = Besoin_posteModel::getBesoin_posteById($id);
		$contrat = ContratModel::getAllContrat();
		$genre = GenreModel::getAllGenre();
		$priorite = PrioriteModel::getAllPriorite();
		$postes = PostesModel::getAllPostes();
		return view('besoin_poste.edit', compact('besoin_poste','contrat','genre','priorite','postes'));
	}

	public function update(Request $request, $id) {
		$besoin_poste = new Besoin_posteModel();
		$besoin_poste->setId_Besoin_poste($id);
		$besoin_poste->setId($request->input('id'));
		$besoin_poste->setId_poste($request->input('id_poste'));
		$besoin_poste->setId_genre($request->input('id_genre'));
		$besoin_poste->setId_contrat($request->input('id_contrat'));
		$besoin_poste->setAgemin($request->input('agemin'));
		$besoin_poste->setFinvalidite($request->input('finvalidite'));
		$besoin_poste->setPriorite($request->input('priorite'));
		$besoin_poste->setInfo_sup($request->input('info_sup'));
		$besoin_poste->setPersonneltrouver($request->input('personneltrouver'));
		$besoin_poste->updateBesoin_poste();
		return redirect()->route('besoin_poste.index')->with('success', 'Besoin_poste mis à jour avec succès.');
	}
	public function destroy($id) {
		$besoin_posteModel = new Besoin_posteModel();
		$besoin_posteModel->setId($id);
		$besoin_posteModel->deleteBesoin_poste();
		return redirect()->route('besoin_poste.index')->with('success', 'Besoin_poste supprimé avec succès.');
	}
}
