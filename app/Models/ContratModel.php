<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ContratModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'contrat';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'libelle', 'minmois', 'maxmois'];
	private $id;
	private $libelle;
	private $minmois;
	private $maxmois;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->libelle = $attributes['libelle'] ?? null;
			$this->minmois = $attributes['minmois'] ?? null;
			$this->maxmois = $attributes['maxmois'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getLibelle() {
		return $this->libelle;
	}

	public function getMinmois() {
		return $this->minmois;
	}

	public function getMaxmois() {
		return $this->maxmois;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setLibelle($libelle) {
		$this->libelle = $libelle;
	}

	public function setMinmois($minmois) {
		$this->minmois = $minmois;
	}

	public function setMaxmois($maxmois) {
		$this->maxmois = $maxmois;
	}

	public static function getAllContrat() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getContratById($id) {
		return self::find($id);
	}

	public function createContrat() {
		return self::create([
			'libelle' => $this->libelle,
			'minmois' => $this->minmois,
			'maxmois' => $this->maxmois,
		]);
	}

	public function updateContrat() {
		return self::where('id', $this->id)
			->update([
				'libelle' => $this->libelle,
				'minmois' => $this->minmois,
				'maxmois' => $this->maxmois,
			]);
	}

	public function deleteContrat() {
		return self::where('id', $this->id)->delete();
	}
}
