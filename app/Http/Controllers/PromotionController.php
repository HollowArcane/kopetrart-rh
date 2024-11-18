<?php

namespace App\Http\Controllers;
use App\Models\PromotionModel;
use App\Models\EmployeModel;
use App\Models\PostesModel;
use Illuminate\Http\Request;
class PromotionController extends Controller {
	public function index() {
		$promotion = PromotionModel::getPromotion();
		return view('promotion.index', compact('promotion'));
	}

	public function create() {
		$employe = EmployeModel::getAllEmploye();
		$postes = PostesModel::getAllPostes();
		return view('promotion.create', compact('employe', 'postes'));
	}

	public function store(Request $request) {
		$promotion = new PromotionModel();
		$promotion->setId($request->input('id'));
		$promotion->setId_employe($request->input('id_employe'));
		$promotion->setId_poste($request->input('id_poste'));
		$promotion->setDate($request->input('date'));
		$promotion->setSalaire($request->input('salaire'));
		$promotion->setStatus($request->input('status'));

		$promotion->createPromotion();
		return redirect()->route('promotion.index')->with('success', 'Promotion créé avec succès.');
	}

	public function edit($id) {
		$promotion = PromotionModel::getPromotionById($id);
		$employe = EmployeModel::getAllEmploye();
		$postes = PostesModel::getAllPostes();
		return view('promotion.edit', compact('promotion','employe','postes'));
	}
	public function update(Request $request, $id) {
		$promotion = new PromotionModel();
		$promotion->setId_Promotion($id);
		$promotion->setId($request->input('id'));
		$promotion->setId_employe($request->input('id_employe'));
		$promotion->setId_poste($request->input('id_poste'));
		$promotion->setDate($request->input('date'));
		$promotion->setSalaire($request->input('salaire'));
		$promotion->setStatus($request->input('status'));
		$promotion->updatePromotion();
		return redirect()->route('promotion.index')->with('success', 'Promotion mis à jour avec succès.');
	}
	public function destroy($id) {
		$promotionModel = new PromotionModel();
		$promotionModel->setId($id);
		$promotionModel->deletePromotion();
		return redirect()->route('promotion.index')->with('success', 'Promotion supprimé avec succès.');
	}

    public function updateStatus($id){
        $promotion = PromotionModel::findOrFail($id);

        $promotion->status = true;
        $promotion->save();

        return redirect()->route('promotion.index')->with('success', 'La promotion a été validée avec succès.');
    }
}
