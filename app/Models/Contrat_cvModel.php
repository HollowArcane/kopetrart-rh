<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Contrat_cvModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'contrat_cv';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_cv', 'id_contrat', 'date_debut', 'periode', 'salaire_propose', 'notes_sup'];
	private $id;
	private $id_cv;
	private $id_contrat;
	private $date_debut;
	private $periode;
	private $salaire_propose;
	private $notes_sup;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_cv = $attributes['id_cv'] ?? null;
			$this->id_contrat = $attributes['id_contrat'] ?? null;
			$this->date_debut = $attributes['date_debut'] ?? null;
			$this->periode = $attributes['periode'] ?? null;
			$this->salaire_propose = $attributes['salaire_propose'] ?? null;
			$this->notes_sup = $attributes['notes_sup'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_cv() {
		return $this->id_cv;
	}

	public function getId_contrat() {
		return $this->id_contrat;
	}

	public function getDate_debut() {
		return $this->date_debut;
	}

	public function getPeriode() {
		return $this->periode;
	}

	public function getSalaire_propose() {
		return $this->salaire_propose;
	}

	public function getNotes_sup() {
		return $this->notes_sup;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_cv($id_cv) {
		$this->id_cv = $id_cv;
	}

	public function setId_contrat($id_contrat) {
		$this->id_contrat = $id_contrat;
	}

	public function setDate_debut($date_debut) {
		$this->date_debut = $date_debut;
	}

	public function setPeriode($periode) {
		$this->periode = $periode;
	}

	public function setSalaire_propose($salaire_propose) {
		$this->salaire_propose = $salaire_propose;
	}

	public function setNotes_sup($notes_sup) {
		$this->notes_sup = $notes_sup;
	}

	public static function getAllContrat_cv() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getContrat_cvById($id) {
		return self::find($id);
	}

	public function createContrat_cv() {
		return self::create([
			'id_cv' => $this->id_cv,
			'id_contrat' => $this->id_contrat,
			'date_debut' => $this->date_debut,
			'periode' => $this->periode,
			'salaire_propose' => $this->salaire_propose,
			'notes_sup' => $this->notes_sup,
		]);
	}

	public function updateContrat_cv() {
		return self::where('id', $this->id)
			->update([
				'id_cv' => $this->id_cv,
				'id_contrat' => $this->id_contrat,
				'date_debut' => $this->date_debut,
				'periode' => $this->periode,
				'salaire_propose' => $this->salaire_propose,
				'notes_sup' => $this->notes_sup,
			]);
	}

	public function deleteContrat_cv() {
		return self::where('id', $this->id)->delete();
	}
    public function cv(){
		return $this->belongsTo(CvModel::class, 'id_cv', 'id');
	}
}
