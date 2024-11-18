<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Model_exoModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'model_exo';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'contenu'];
	private $id;
	private $contenu;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->contenu = $attributes['contenu'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getContenu() {
		return $this->contenu;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setContenu($contenu) {
		$this->contenu = $contenu;
	}

	public static function getAllModel_exo() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getModel_exoById($id) {
		return self::find($id);
	}

	public function createModel_exo() {
		return self::create([
			'contenu' => $this->contenu,
		]);
	}

	public function updateModel_exo() {
		return self::where('id', $this->id)
			->update([
				'contenu' => $this->contenu,
			]);
	}

	public function deleteModel_exo() {
		return self::where('id', $this->id)->delete();
	}
}
