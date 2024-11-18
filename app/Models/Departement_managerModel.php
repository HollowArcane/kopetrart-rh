<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Departement_managerModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'departement_manager';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_departement', 'id_employe'];
	private $id;
	private $id_departement;
	private $id_employe;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_departement = $attributes['id_departement'] ?? null;
			$this->id_employe = $attributes['id_employe'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_departement() {
		return $this->id_departement;
	}

	public function getId_employe() {
		return $this->id_employe;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_departement($id_departement) {
		$this->id_departement = $id_departement;
	}

	public function setId_employe($id_employe) {
		$this->id_employe = $id_employe;
	}

	public static function getAllDepartement_manager() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getDepartement_managerById($id) {
		return self::find($id);
	}

	public function createDepartement_manager() {
		return self::create([
			'id_departement' => $this->id_departement,
			'id_employe' => $this->id_employe,
		]);
	}

	public function updateDepartement_manager() {
		return self::where('id', $this->id)
			->update([
				'id_departement' => $this->id_departement,
				'id_employe' => $this->id_employe,
			]);
	}

	public function deleteDepartement_manager() {
		return self::where('id', $this->id)->delete();
	}
}
