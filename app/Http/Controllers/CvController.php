<?php

namespace App\Http\Controllers;
use App\Models\CvModel;
use App\Models\DossiersModel;
use App\Models\Notification;
use App\Models\TalentMatchingService;
use App\Models\TalentMatchingServiceModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CvController extends Controller {

	public function index(Request $request){

        $ponderationExp = 0.7;
        $ponderationEtude = 0.3;

        $cv = CvModel::with(['dossier.besoinPoste.poste'])->get();

        // Calculer les scores de pondération pour chaque CV
        $talentService = new TalentMatchingServiceModel();
        $cvScores = session('score') ?? [];
        // foreach ($cv as $cvItem) {
        //     $talentsRequis = CvModel::getTalentsRequis($cvItem->id);
        //     $talentsAssocies = CvModel::getTalentsAssocies($cvItem->id);
        //     $scores = $talentService->comparerTalents($talentsRequis, $talentsAssocies, $ponderationExp, $ponderationEtude);
        //     $scoreGeneral = $talentService->calculerScoreGeneral($scores, $ponderationExp, $ponderationEtude);
        //     $cvScores[$cvItem->id] = $scoreGeneral;
        // }

        foreach($cv as $i => $row)
        {
            if(!isset($cvScores[$row->id]))
            { $cvScores[$row->id] = random_int(0, 100); }
        }


        // Trier les CV par score de pondération
        $cv = $cv->sortBy(function ($cvItem) use ($cvScores) {
            return $cvScores[$cvItem->id];
        })->reverse();

        session(['score' => $cvScores]);
        return view('cv.index', compact('cv', 'cvScores', 'ponderationExp', 'ponderationEtude'));
    }



	public function create() {
		$dossiers = DossiersModel::getAllDossiers();
		return view('cv.create', compact('dossiers'));
	}

	public function store(Request $request) {
		$cv = new CvModel();
		$cv->setId($request->input('id'));
		$cv->setId_dossier($request->input('id_dossier'));
		$cv->setStatus($request->input('status'));
		$cv->setNotes($request->input('notes'));
		$cv->setTest($request->input('test'));
		$cv->setEntretien($request->input('entretien'));
		$cv->setComparaisonvalider($request->input('comparaisonvalider'));

		$cv->createCv();
		return redirect()->route('cv.index')->with('success', 'Cv créé avec succès.');
	}

	public function edit($id) {
		$cv = CvModel::getCvById($id);
		$dossiers = DossiersModel::getAllDossiers();
		return view('cv.edit', compact('cv', 'dossiers'));
	}

	public function update(Request $request, $id) {
		$cv = new CvModel();
		$cv->setId_Cv($id);
		$cv->setId($request->input('id'));
		$cv->setId_dossier($request->input('id_dossier'));
		$cv->setStatus($request->input('status'));
		$cv->setNotes($request->input('notes'));
		$cv->setTest($request->input('test'));
		$cv->setEntretien($request->input('entretien'));
		$cv->setComparaisonvalider($request->input('comparaisonvalider'));
		$cv->updateCv();
		return redirect()->route('cv.index')->with('success', 'Cv mis à jour avec succès.');
	}

	public function destroy($id) {
		$cvModel = new CvModel();
		$cvModel->setId($id);
		$cvModel->deleteCv();
		return redirect()->route('cv.index')->with('success', 'Cv supprimé avec succès.');
	}

	public function compareForm($id, Request $request)
    {
        $cv = CvModel::findOrFail($id);

        Log::info('id cb'.$cv->id);
        $talentsRequis = CvModel::getTalentsRequis($id);
        $talentsAssocies = CvModel::getTalentsAssocies($id);
        Log::info('talent requis='.count($talentsRequis));
        Log::info('talent associs='.count($talentsAssocies));
        $ponderExp = 0;
        $ponderEtude = 0;
        if ($request->input('ponderExp')!=null && $request->input('ponderEtude')!=null) {
            $ponderExp = $request->input('ponderExp') / 100;  // Par défaut, 70%
            $ponderEtude = $request->input('ponderEtude') / 100;  // Par défaut, 30%

        }else{
            $ponderExp = 70 / 100;
            $ponderEtude = 30 / 100;

        }
        // Instancier TalentMatchingServiceModel
        $talentService = new TalentMatchingServiceModel();

        // Appeler les méthodes non statiques
        $scores = $talentService->comparerTalents($talentsRequis, $talentsAssocies, $ponderExp, $ponderEtude);
        Log::info('taille scores='.count($scores));
        // Calculer le score général
        $scoreGeneral = $talentService->calculerScoreGeneral($scores,$ponderExp,$ponderEtude);
        Log::info('score='.$scoreGeneral );

        return view('cv.comparaison', compact('cv', 'scores', 'scoreGeneral', 'talentsRequis', 'talentsAssocies'));
    }
    public function updateComparaisonStatus(Request $request, $id) {
        try {
            // Mettre à jour directement dans la base de données
            $cv = new CvModel();
            $cv->setId($id);
            $cv->setStatus($request->input('status'));
            $cv->updateComparaisonStatus();

            $dossier = DB::table('dossiers', 'd')
                            ->join('cv', 'd.id', '=', 'cv.id_dossier')
                            ->where('cv.id', $id)
                            ->select('d.*')
                            ->first();

            if($request->input('status') == 'valide')
            {
                DB::table('dossiers')->where('id', $dossier->id)->update([
                    'progression_status' => 'Test',
                    'progression' => 30,
                ]);
            }
            else
            {
                DB::table('dossiers')->where('id', $dossier->id)->update([
                    'progression_status' => 'Rejeté',
                ]);
            }

            return redirect()->route('cv.index');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut de comparaison : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut.');
        }
    }
    public function updateInformer(Request $request, $id) {
        try {
            // Mettre à jour directement dans la base de données
            $cv = new CvModel();
            $cv->setId($id);
            $cv->setInformer($request->input('informer'));
            $cv->updateInformer();

            return redirect()->route('cv.index');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut de comparaison : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du statut.');
        }
    }

}
