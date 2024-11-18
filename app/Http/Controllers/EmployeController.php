<?php

namespace App\Http\Controllers;
use App\Models\EmployeModel;
use App\Models\DepartementModel;
use App\Models\ContratModel;
use App\Models\PostesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeController extends Controller {
	public function index($id_besoins = null) {
        // $employe = EmployeModel::getEmpList($id_besoins);
		$employe = DB::table('v_contrats_cv_expiration')->get();

        return view('employe.index', compact('employe','id_besoins'));
    }

	public function create() {
		$departement = DepartementModel::getAllDepartement();
		$contrat = ContratModel::getAllContrat();
		$postes = PostesModel::getAllPostes();
		return view('employe.create', compact('departement', 'contrat', 'postes'));
	}

	public function store(Request $request) {
		$employe = new EmployeModel();
		$employe->setId($request->input('id'));
		$employe->setId_departement($request->input('id_departement'));
		$employe->setId_poste($request->input('id_poste'));
		$employe->setDate($request->input('date'));
		$employe->setId_contrat($request->input('id_contrat'));

		$employe->createEmploye();
		return redirect()->route('employe.index')->with('success', 'Employe créé avec succès.');
	}

	public function edit($id) {
		$employe = EmployeModel::getEmployeById($id);
		$departement = DepartementModel::getAllDepartement();
		$contrat = ContratModel::getAllContrat();
		$postes = PostesModel::getAllPostes();
		return view('employe.edit', compact('employe', 'departement', 'contrat', 'postes'));
	}
	public function update(Request $request, $id) {
		$employe = new EmployeModel();
		$employe->setId_Employe($id);
		$employe->setId($request->input('id'));
		$employe->setId_departement($request->input('id_departement'));
		$employe->setId_poste($request->input('id_poste'));
		$employe->setDate($request->input('date'));
		$employe->setId_contrat($request->input('id_contrat'));
		$employe->updateEmploye();
		return redirect()->route('employe.index')->with('success', 'Employe mis à jour avec succès.');
	}
	public function destroy($id) {
		$employeModel = new EmployeModel();
		$employeModel->setId($id);
		$employeModel->deleteEmploye();
		return redirect()->route('employe.index')->with('success', 'Employe supprimé avec succès.');
	}

    public function employePromotion($id_employe, $candidat,$postePromotion)
    {
        $postes = PostesModel::getAllPostes();
        return view('promotion.create', compact('id_employe', 'candidat', 'postes','postePromotion'));
    }

}
