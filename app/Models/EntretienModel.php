<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EntretienModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'entretien';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_cv', 'date_entretien', 'commentaire', 'status','informer','valide'];
	private $id;
	private $id_cv;
	private $date_entretien;
	private $commentaire;
	private $status;
	private $informer;
	private $valide;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_cv = $attributes['id_cv'] ?? null;
			$this->date_entretien = $attributes['date_entretien'] ?? null;
			$this->commentaire = $attributes['commentaire'] ?? null;
			$this->status = $attributes['status'] ?? null;
			$this->informer = $attributes['informer'] ?? null;
			$this->valide = $attributes['valide'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_cv() {
		return $this->id_cv;
	}

	public function getDate_entretien() {
		return $this->date_entretien;
	}

	public function getCommentaire() {
		return $this->commentaire;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_cv($id_cv) {
		$this->id_cv = $id_cv;
	}

	public function setInformer($informer) {
		$this->informer = $informer;
	}

	public function setDate_entretien($date_entretien) {
		$this->date_entretien = $date_entretien;
	}

	public function setCommentaire($commentaire) {
		$this->commentaire = $commentaire;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public static function getAllEntretien() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getEntretienById($id) {
		return self::find($id);
	}

	public function createEntretien() {
		return self::create([
			'id_cv' => $this->id_cv,
			'date_entretien' => $this->date_entretien,
			'commentaire' => $this->commentaire,
			'status' => $this->status,
		]);
	}

	public function updateEntretien() {
		return self::where('id', $this->id)
			->update([
				'id_cv' => $this->id_cv,
				'date_entretien' => $this->date_entretien,
				'commentaire' => $this->commentaire,
				'status' => $this->status,
				'status' => $this->status,
			]);
	}

	public function updateInformer() {
		return self::where('id', $this->id)
			->update([
				'informer' => $this->informer,
			]);
	}
	public function updateValide() {
		return self::where('id', $this->id)
			->update([
				'valide' => $this->valide,
			]);
	}

	public function deleteEntretien() {
		return self::where('id', $this->id)->delete();
	}

    public function cv(){
		return $this->belongsTo(CvModel::class, 'id_cv', 'id');
	}

}
