<?php

namespace App\Http\Controllers;
use App\Models\TestModel;
use App\Models\TypetestModel;
use App\Models\CvModel;
use App\Models\Model_exoModel;
use App\Models\Test_noteModel;
use Illuminate\Http\Request;
class TestController extends Controller {

    public function testBatch(Request $request)
    {
        // Récupérer les résultats envoyés dans la requête (testResults)
        $results = $request->input('results');

        // Vérifier si les données existent
        if (empty($results)) {
            return response()->json(['error' => 'Aucun résultat de test envoyé.'], 400);
        }

        // Tableau pour stocker les IDs des tests insérés
        $testIds = [];
        // Tableau pour stocker les erreurs
        $errors = [];

        // Tableau pour garder la trace des tests déjà insérés
        $existingTests = [];

        // Parcourir les résultats reçus pour effectuer des actions si nécessaire
        foreach ($results as $result) {
            try {
                // Vérifier si le test existe déjà en fonction du cvId et testId
                $testKey = $result['cvId'] . '-' . $result['testId'];

                // Initialiser la somme des scores
                $totalScore = 0;

                // Si le test n'a pas encore été inséré, on l'insère
                if (!isset($existingTests[$testKey])) {
                    // Parcourir tous les résultats pour sommer les scores du même test
                    foreach ($results as $r) {
                        if ($r['cvId'] == $result['cvId'] && $r['testId'] == $result['testId']) {
                            $totalScore += $r['score'];
                        }
                    }

                    // Créer un nouveau test avec la somme des scores
                    $test = new TestModel();
                    $test->setId_cv($result['cvId']);
                    $test->setId_typetest($result['testId']);
                    $test->setDate($result['date']);
                    $test->setPoint($totalScore);  // Utilisation de la somme des scores
                    $test->setObservation($result['observation']);
                    $test->setStatus($result['status']);
                    $testId = $test->createTest();  // Cette méthode retourne l'ID généré

                    // Sauvegarder l'ID du test inséré et marquer ce test comme inséré
                    $testIds[] = $testId;
                    $existingTests[$testKey] = $testId;
                } else {
                    // Si le test existe déjà, on récupère l'ID existant
                    $testId = $existingTests[$testKey];
                }

                // Insérer les notes associées à ce test
                $test_note = new Test_noteModel();
                $test_note->setId_test($testId);
                $test_note->setId_model_exo($result['id_model_exo']);
                $test_note->setNote($result['score']);
                $test_note->createTest_note();

            } catch (\Exception $e) {
                // En cas d'erreur d'enregistrement, on ajoute un message d'erreur
                $errors[] = "Erreur lors de l'enregistrement du test avec testId {$result['testId']}: " . $e->getMessage();
            }
        }

        // Retourner une réponse JSON de succès avec les résultats reçus et les testIds insérés
        return response()->json([
            'status' => 'success',
            'message' => 'Les résultats des tests ont été enregistrés avec succès.',
            'results' => $results, // Ajouter les résultats dans la réponse JSON
            'test_ids' => $testIds, // Ajouter les IDs des tests
            'errors' => $errors // Ajouter les erreurs si elles existent
        ]);
    }



    public function index() {
		$test = TestModel::getTestData();
		return view('test.index', compact('test'));
	}

    public function testDataSend() {
		$test = TestModel::getTestDataSend();
		return view('test.testSend', compact('test'));
	}

	public function create() {
		$typetest = TypetestModel::getAllTypetest();
		$cv = CvModel::getCvData();
        $test_note = TypetestModel::getAllTypetest();
        $model_exo = Model_exoModel::getAllModel_exo();
		return view('test.create', compact('typetest', 'cv','test_note','model_exo'));
	}

	public function store(Request $request) {
		$test = new TestModel();
		$test->setId($request->input('id'));
		$test->setId_cv($request->input('id_cv'));
		$test->setId_typetest($request->input('id_typetest'));
		$test->setDate($request->input('date'));
		$test->setPoint($request->input('point'));
		$test->setObservation($request->input('observation'));

		$test->createTest();
		return redirect()->route('test.index')->with('success', 'Test créé avec succès.');
	}

	public function edit($id) {
		$test = TestModel::getTestById($id);
		$typetest = TypetestModel::getAllTypetest();
		$cv = CvModel::getAllCv();
		return view('test.edit', compact('test','typetest','cv'));
	}
	public function update($id) {
		$test = new TestModel();
        $test->setId($id);
		$test->setis_communication_send(true);
		$test->updateTest();
		return redirect()->route('test.index')->with('success', 'Test mis à jour avec succès.');
	}
	public function destroy($id) {
		$testModel = new TestModel();
		$testModel->setId($id);
		$testModel->deleteTest();
		return redirect()->route('test.index')->with('success', 'Test supprimé avec succès.');
	}
}
