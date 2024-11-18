<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ExperienceModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'experience';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'anneemin', 'anneemax', 'libelle'];
	private $id;
	private $anneemin;
	private $anneemax;
	private $libelle;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->anneemin = $attributes['anneemin'] ?? null;
			$this->anneemax = $attributes['anneemax'] ?? null;
			$this->libelle = $attributes['libelle'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getAnneemin() {
		return $this->anneemin;
	}

	public function getAnneemax() {
		return $this->anneemax;
	}

	public function getLibelle() {
		return $this->libelle;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setAnneemin($anneemin) {
		$this->anneemin = $anneemin;
	}

	public function setAnneemax($anneemax) {
		$this->anneemax = $anneemax;
	}

	public function setLibelle($libelle) {
		$this->libelle = $libelle;
	}

	public static function getAllExperience() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getExperienceById($id) {
		return self::find($id);
	}

	public function createExperience() {
		return self::create([
			'anneemin' => $this->anneemin,
			'anneemax' => $this->anneemax,
			'libelle' => $this->libelle,
		]);
	}

	public function updateExperience() {
		return self::where('id', $this->id)
			->update([
				'anneemin' => $this->anneemin,
				'anneemax' => $this->anneemax,
				'libelle' => $this->libelle,
			]);
	}

	public function deleteExperience() {
		return self::where('id', $this->id)->delete();
	}
}
