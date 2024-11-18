<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CvModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'cv';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_dossier', 'status', 'notes', 'test', 'entretien','etude', 'comparaisonvalider','bonus','informer'];
	private $id;
	private $id_dossier;
	private $status;
	private $notes;
	private $test;
	private $entretien;
	private $comparaisonvalider;
	private $bonus;
	private $informer;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_dossier = $attributes['id_dossier'] ?? null;
			$this->status = $attributes['status'] ?? null;
			$this->notes = $attributes['notes'] ?? null;
			$this->test = $attributes['test'] ?? null;
			$this->entretien = $attributes['entretien'] ?? null;
			$this->comparaisonvalider = $attributes['comparaisonvalider'] ?? null;
			$this->bonus = $attributes['bonus'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_dossier() {
		return $this->id_dossier;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getNotes() {
		return $this->notes;
	}

	public function getTest() {
		return $this->test;
	}

	public function getEntretien() {
		return $this->entretien;
	}

	public function getComparaisonvalider() {
		return $this->comparaisonvalider;
	}

	public function getBonus() {
		return $this->bonus;
	}

    public function setId($id) {
        $this->id = $id;
    }

    public function setInformer($info) {
        $this->informer = $info;
    }

	public function setBonus($bonus) {
		$this->bonus = $bonus;
	}

	public function setId_dossier($id_dossier) {
		$this->id_dossier = $id_dossier;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setNotes($notes) {
		$this->notes = $notes;
	}

	public function setTest($test) {
		$this->test = $test;
	}

	public function setEntretien($entretien) {
		$this->entretien = $entretien;
	}

	public function setComparaisonvalider($comparaisonvalider) {
		$this->comparaisonvalider = $comparaisonvalider;
	}

	public static function getAllCv() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getCvById($id) {
		return self::find($id);
	}

	public function createCv() {
		return self::create([
			'id_dossier' => $this->id_dossier,
			'status' => $this->status,
			'notes' => $this->notes,
			'test' => $this->test,
			'entretien' => $this->entretien,
			'comparaisonvalider' => $this->comparaisonvalider,
		]);
	}

	public function updateCv() {
		return self::where('id', $this->id)
			->update([
				'id_dossier' => $this->id_dossier,
				'status' => $this->status,
				'notes' => $this->notes,
				'test' => $this->test,
				'entretien' => $this->entretien,
				'comparaisonvalider' => $this->comparaisonvalider,
			]);
	}

	public function deleteCv() {
		return self::where('id', $this->id)->delete();
	}

	protected $casts = [
        'bonus' => 'integer',
    ];
	public function dossier(){
		return $this->belongsTo(DossiersModel::class, 'id_dossier', 'id');
	}

    public static function getTalentsRequis($id){
		$cv = CvModel::findOrFail($id);
        $id_besoin_poste = $cv->dossier->id_besoin_poste;

        return Talent_poste_besoinModel::where('id_besoin_poste', $id_besoin_poste)
                                     ->with('talent')
                                     ->get();
    }

    public static function getTalentsAssocies($id) {
		return Classification_cvModel::where('id_cv', $id)->get();
	}

    public static function getCvData()
    {
        $query = DB::table('v_data_cv');
        $result = $query->get();

        if ($result->isNotEmpty()) {
            return $result->toArray();
        } else {
            return [];
        }
    }
    public function updateComparaisonStatus() {
		return self::where('id', $this->id)
			->update([
				'status' => $this->status,
			]);
	}
    public function updateInforme() {
		return self::where('id', $this->id)
			->update([
				'informer' => $this->informer,
			]);
	}
    public static function getValidTestCvs() {
        return self::where('test', '=', 'valide')
                   ->orderBy('id', 'desc')
                   ->get();
    }
}
