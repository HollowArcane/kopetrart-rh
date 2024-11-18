<?php

namespace App\Http\Controllers;


use App\Models\Contrat_cvModel;
use App\Models\ContratModel;
use App\Models\EntretienModel;
use App\Models\EmployeModel;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ContratController extends Controller {
	public function index() {
		$contrat = DB::table('v_contrats_cv_expiration')->get();
		return view('contrat.index', compact('contrat'));
	}

	public function create() {
		return view('contrat.create');
	}

	public function showRenewalForm($id) {
        // Récupère les entretiens avec un statut 'valide'
        $entretiensValides = Contrat_cvModel::get();
        $contrats = ContratModel::getAllContrat();

        // Passe ces entretiens à la vue
        return view('contrat.renouveler', compact('entretiensValides','contrats'));
	}

    public function showEssaiForm() {
        // Récupère les entretiens avec un statut 'valide'
        $entretiensValides = EntretienModel::where('status', 'valide')->with('cv.dossier.besoinPoste.poste')->get();
        $contrats = ContratModel::getAllContrat();

        // Passe ces entretiens à la vue
        return view('contrat.essai', compact('entretiensValides','contrats'));
    }

	public function store(Request $request) {
		$contrat = new ContratModel();
		$contrat->setId($request->input('id'));
		$contrat->setLibelle($request->input('libelle'));
		$contrat->setMinmois($request->input('minmois'));
		$contrat->setMaxmois($request->input('maxmois'));

		$contrat->createContrat();
		return redirect()->route('contrat.index')->with('success', 'Contrat créé avec succès.');
	}

	public function edit($id) {
		$contrat = ContratModel::getContratById($id);
		return view('contrat.edit', compact('contrat'));
	}
	public function update(Request $request, $id) {
		$contrat = new ContratModel();
		$contrat->setId_Contrat($id);
		$contrat->setId($request->input('id'));
		$contrat->setLibelle($request->input('libelle'));
		$contrat->setMinmois($request->input('minmois'));
		$contrat->setMaxmois($request->input('maxmois'));
		$contrat->updateContrat();
		return redirect()->route('contrat.index')->with('success', 'Contrat mis à jour avec succès.');
	}
	public function destroy($id) {
		$contratModel = new ContratModel();
		$contratModel->setId($id);
		$contratModel->deleteContrat();
		return redirect()->route('contrat.index')->with('success', 'Contrat supprimé avec succès.');
	}
    public function storeEssai(Request $request) {
        // Valider les données du formulaire
        $request->validate([
            'employee' => 'required|string',
            'type_contrat' => 'required|string',
            'periode' => 'required|integer',
            'salaire_propose' => 'required|numeric',
            'notes_sup' => 'nullable|string',
        ]);

        // Récupérer les informations à insérer
        $candidat_id = $request->input('employee');
        $typeContrat = $request->input('type_contrat');
        $periode = $request->input('periode');
        $salairePropose = $request->input('salaire_propose');
        $notesSup = $request->input('notes_sup');

        // Récupérer le candidat correspondant au nom sélectionné
        $candidat = \App\Models\EntretienModel::whereHas('cv.dossier', function($query) use ($candidat_id) {
            $query->where('id', $candidat_id);
        })->first();

        Log::info("candidat name: " . $candidat_id);

        if ($candidat) {

            // Récupérer l'ID du CV et du contrat
            $idCv = $candidat->cv->id;
            $contrat = \App\Models\ContratModel::where('libelle', $typeContrat)->first();
            $idContrat = $contrat ? $contrat->id : null;

            // Créer une nouvelle entrée dans la table contrat_cv
            $contratCv = new \App\Models\Contrat_cvModel();
            $contratCv->id_cv = $idCv;
            $contratCv->id_contrat = $idContrat;
            $contratCv->date_debut = now(); // Utilisez la date actuelle pour la date de début
            $contratCv->periode = $periode;
            $contratCv->salaire_propose = $salairePropose;
            $contratCv->notes_sup = $notesSup;

            $dossier = DB::table('dossiers', 'd')
                            ->join('cv', 'd.id', '=', 'cv.id_dossier')
                            ->where('cv.id', $idCv)
                            ->select('d.*')
                            ->first();

            DB::table('dossiers')->where('id', $dossier->id)->update([
                'progression_status' => 'Approuvé',
                'progression' => 100,
            ]);

            $notification = new Notification();
            $notification->title = 'Nouveau Contrat';
            $notification->message = 'Un nouveau contrat d\'essai à été signé';
            $notification->redirection = '/contrat';
            $notification->id_role = 2; // responsable rh
            $notification->save();

            // Sauvegarder l'entrée dans la base de données
            $contratCv->save();

            return redirect()->route('contrat.showEssaiForm')->with('success', 'Le contrat d\'essai a été créé avec succès.');
        } else {
            return redirect()->route('contrat.showEssaiForm')->with('error', 'Candidat non trouvé.');
        }
    }

    public function display_pdf($id)
    {
        // Retrieve contrat_cv data with related models
        $contratCv = Contrat_cvModel::with([
            'cv.dossier.besoinPoste.poste'
        ])->findOrFail($id);

        // Pass data to the view
        $data = [
            'contratCv' => $contratCv
        ];

        // Load the view and render as PDF
        $pdf = Pdf::loadView('contrat.pdf', $data);

        // Return the PDF as download
        return $pdf->download('contract_' . $contratCv->id . '.pdf');
    }

    public function storeContrat(Request $request) {
        // Valider les données du formulaire
        $request->validate([
            'employee' => 'required|string',
            'type_contrat' => 'required|string',
            'periode' => 'required|integer',
            'salaire_propose' => 'required|numeric',
            'notes_sup' => 'nullable|string',
        ]);

        // Récupérer les informations à insérer
        $candidat_id = $request->input('employee');
        $typeContrat = $request->input('type_contrat');
        $periode = $request->input('periode');
        $salairePropose = $request->input('salaire_propose');
        $notesSup = $request->input('notes_sup');

        // Récupérer le candidat correspondant au nom sélectionné
        $candidat = \App\Models\EntretienModel::whereHas('cv.dossier', function($query) use ($candidat_id) {
            $query->where('candidat', $candidat_id);
        })->first();


        Log::info("table: ");
        Log::info($candidat);


        Log::info("*-------- before if");
        Log::info("candidat name: " . $candidat_id);

        if ($candidat) {
            Log::info("######## if trueee");
            // Si entretien.valider est null, le valider
            if (is_null($candidat->valide)) {
                // Mettre à jour la propriété 'status' ou 'valider' pour valider l'entretien
                $candidat->valide = true;  // Vous pouvez ajuster cette valeur en fonction de vos besoins
                $candidat->save();
            }

            // call procedure
            EmployeModel::insererDonneesEmploye();

            // Récupérer l'ID du CV et du contrat
            $idCv = $candidat->cv->id;
            $contrat = \App\Models\ContratModel::where('libelle', $typeContrat)->first();
            $idContrat = $contrat ? $contrat->id : null;

            // Créer une nouvelle entrée dans la table contrat_cv
            $contratCv = new \App\Models\Contrat_cvModel();
            $contratCv->id_cv = $idCv;
            $contratCv->id_contrat = $idContrat;
            var_dump($request->input('date_entree'));
            $contratCv->date_debut = $request->input('date_entree'); // Utilisez la date actuelle pour la date de début
            $contratCv->periode = $periode;
            $contratCv->salaire_propose = $salairePropose;
            $contratCv->notes_sup = $notesSup;

            // Sauvegarder l'entrée dans la base de données
            $contratCv->save();

            return redirect()->route('contrat.showEssaiForm')->with('success', 'Le contrat d\'essai a été créé avec succès. modif test');
        }

        else {
            return redirect()->route('contrat.showEssaiForm')->with('error', 'Candidat non trouvé. modif test' );
        }
    }
}
