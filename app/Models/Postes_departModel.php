<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Postes_departModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'postes_depart';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_departement', 'id_poste'];
	private $id;
	private $id_departement;
	private $id_poste;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_departement = $attributes['id_departement'] ?? null;
			$this->id_poste = $attributes['id_poste'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_departement() {
		return $this->id_departement;
	}

	public function getId_poste() {
		return $this->id_poste;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_departement($id_departement) {
		$this->id_departement = $id_departement;
	}

	public function setId_poste($id_poste) {
		$this->id_poste = $id_poste;
	}

	public static function getAllPostes_depart() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getPostes_departById($id) {
		return self::find($id);
	}

	public function createPostes_depart() {
		return self::create([
			'id_departement' => $this->id_departement,
			'id_poste' => $this->id_poste,
		]);
	}

	public function updatePostes_depart() {
		return self::where('id', $this->id)
			->update([
				'id_departement' => $this->id_departement,
				'id_poste' => $this->id_poste,
			]);
	}

	public function deletePostes_depart() {
		return self::where('id', $this->id)->delete();
	}
}
