<?php

namespace App\Http\Controllers\Classification;

use App\Models\CvModel;
use App\Models\Classification\DenormalizedCv;
use App\Models\Data\Interest;
use App\Models\Data\Quality;
use App\Models\Data\Education;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

class DenormalizedCvController extends Controller
{
    public function index()
    {
        $denormalizedCvs = DenormalizedCv::with(['interests', 'qualities', 'educations', 'experiences'])->get();

        return view('classification.index', compact('denormalizedCvs'));
    }

    public function create()
    {
        $cvs = CvModel::all();

        $interests = Interest::all();
        $qualities = Quality::all();
        $educations = Education::all();

        return view('classification.create', compact('cvs', 'interests', 'qualities', 'educations'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_cv' => 'required|exists:cv,id',
            'adequate' => 'nullable|boolean',
            'potentiel' => 'nullable|boolean',
            'interests' => 'array',
            'qualities' => 'array',
            'educations' => 'array',
            'experiences' => 'array',
            'experiences.*.label' => 'required|string|max:50',
            'experiences.*.month_duration' => 'required|numeric|min:0',
        ]);

        $cv = CvModel::find($validatedData['id_cv']);

        $validatedData['candidat_name'] = $cv->dossier->candidat;
        $validatedData['poste'] = $cv->dossier->besoinPoste->poste->libelle;
        $validatedData['date_depot_dossier'] = $cv->dossier->date_reception;

        // Call the Python prediction API
        try {
            $response = Http::post('http://localhost:5000/predict', $validatedData);
            $predictions = $response->json();

            Log::info("predictions: ");
            Log::info($predictions);
    
            $validatedData['adequate'] = $predictions['adequate']['prediction'];
            $validatedData['potentiel'] = $predictions['potentiel']['prediction'];
        } 
        
        catch (\Exception $e) {
            Log::error('Error making prediction: ' . $e->getMessage());
            $validatedData['adequate'] = null;
            $validatedData['potentiel'] = null;
        }
    
        $denormalizedCv = DenormalizedCv::create($validatedData);

        if (!empty($request->interests)) {
            foreach ($request->interests as $interestId) {
                $denormalizedCv->interests()->create(['id_interest' => $interestId]);
            }
        }

        if (!empty($request->qualities)) {
            foreach ($request->qualities as $qualityId) {
                $denormalizedCv->qualities()->create(['id_quality' => $qualityId]);
            }
        }

        if (!empty($request->educations)) {
            foreach ($request->educations as $educationId) {
                $denormalizedCv->educations()->create(['id_education' => $educationId]);
            }
        }

        if (!empty($request->experiences)) {
            foreach ($request->experiences as $experience) {
                $denormalizedCv->experiences()->create([
                    'label' => $experience['label'],
                    'month_duration' => $experience['month_duration'],
                ]);
            }
        }

        return redirect()->route('classification.index')->with('success', 'Denormalized CV created successfully');
    }

}