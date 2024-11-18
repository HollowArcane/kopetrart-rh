<?php

namespace App\Http\Controllers;

use App\Models\DossiersModel;
use App\Models\CvModel;
use App\Models\Classification_cvModel;
use App\Models\ContratModel;
use App\Models\Notification;
use App\Models\TalentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class Classification_cvController extends Controller {

    // Display list of contracts or other items (already defined)
    public function index() {
        $contrat = ContratModel::getAllContrat();
        return view('classification.index', compact('contrat'));
    }

    // Show the evaluation form for a specific dossier (CV to be evaluated)
    public function create($id)
    {
        // Fetch the dossier by ID
        $dossier = DossiersModel::find($id);

        // Debug: Log or dump the result
        if (!$dossier) {
            // If the dossier is not found, log it
            Log::debug('Dossier not found with ID: ' . $id);
        } else {
            Log::debug('Dossier found:', ['dossier' => $dossier]);
        }
        echo $dossier->candidat;
        return view('cv.evaluer', compact('dossier'));
    }


    public function store(Request $request, $id)
    {
        // try {
            DB::beginTransaction();

            // Decode and validate domaines as array
            $domaines = json_decode($request->input('domaines', '[]'), true);
            if (!is_array($domaines)) {
                $domaines = [];
            }

            // Calculate bonus based on number of domains
            $bonus = count($domaines);

            // 1. Create CV with required attributes
            $cvAttributes = [
                'id_dossier' => $id,
                'status' => 'en cours',
                'notes' => $request->input('notes'),
                'test' => null,
                'entretien' => null,
                'comparaisonvalider' => null,
                'bonus' => $bonus
            ];

            $cv = new CvModel($cvAttributes);
            $cv->save();

            $DsAttributes = [
                'esttraduit' => true
            ];

            $dossier = new DossiersModel($DsAttributes);
            $dossier->updateEstTraduit();

            // 2. Process cart items (competences)
            $cartItems = json_decode($request->input('cart_items', '[]'), true);
            if (!is_array($cartItems)) {
                $cartItems = [];
            }

            foreach ($cartItems as $item) {
                // Validate item structure
                if (!isset($item['competence']) || !isset($item['experience'])) {
                    continue;
                }

                // Find or create talent
                $talent = TalentModel::firstOrCreate(
                    ['libelle' => $item['competence']],
                    ['libelle' => $item['competence']]
                );

                // Create classification
                $classification = new Classification_cvModel();
                $classification->id_cv = $cv->id;
                $classification->experience = $item['experience'];
                $classification->id_talent = $talent->id;
                $classification->save();
            }

            DB::commit();

            $notification = new Notification();
            $notification->title = 'Nouvelle Classification';
            $notification->message = 'Un dossier à été classifié';
            $notification->redirection = '/cv/comparer/'.$cv->id;
            $notification->id_role = 5; // chargé de recrutement
            $notification->save();

            return redirect()
                ->route('dossiers.index')
                ->with('success', 'Classification enregistrée avec succès. Bonus: ' . $bonus);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Log::error('Erreur lors de la classification du CV: ' . $e->getMessage());

        //     return redirect()
        //         ->back()
        //         ->with('error', 'Une erreur est survenue lors de l\'enregistrement: ' . $e->getMessage())
        //         ->withInput();
        // }
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
}
