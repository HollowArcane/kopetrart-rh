<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
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
    public static $url = '/contrat';

    private $list_view = 'contrat.index';
    private $create_view = 'contrat.create';

	public function index() {
		$contrat = DB::table('v_contrats_cv_expiration')->get();
		return view($this->list_view, compact('contrat'));
	}

    public function showEssaiForm() {
        // Récupère les entretiens avec un statut 'valide'
        $entretiensValides = EntretienModel::where('status', 'valide')->with('cv.dossier.besoinPoste.poste')->get();
        $contrats = ContratModel::getAllContrat();

        // Passe ces entretiens à la vue
        return view('contrat.essai', compact('entretiensValides','contrats'));
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
}
