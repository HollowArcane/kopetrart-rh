<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CommunicationModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'communication';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_cv', 'id_model_communication', 'create_at'];
	private $id;
	private $id_cv;
	private $id_model_communication;
	private $create_at;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_cv = $attributes['id_cv'] ?? null;
			$this->id_model_communication = $attributes['id_model_communication'] ?? null;
			$this->create_at = $attributes['create_at'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_cv() {
		return $this->id_cv;
	}

	public function getId_model_communication() {
		return $this->id_model_communication;
	}

	public function getCreate_at() {
		return $this->create_at;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_cv($id_cv) {
		$this->id_cv = $id_cv;
	}

	public function setId_model_communication($id_model_communication) {
		$this->id_model_communication = $id_model_communication;
	}

	public function setCreate_at($create_at) {
		$this->create_at = $create_at;
	}

	public static function getAllCommunication() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getCommunicationById($id) {
		return self::find($id);
	}

	public function createCommunication() {
		return self::create([
			'id_cv' => $this->id_cv,
			'id_model_communication' => $this->id_model_communication,
			'create_at' => $this->create_at,
		]);
	}

	public function updateCommunication() {
		return self::where('id', $this->id)
			->update([
				'id_cv' => $this->id_cv,
				'id_model_communication' => $this->id_model_communication,
				'create_at' => $this->create_at,
			]);
	}

	public function deleteCommunication() {
		return self::where('id', $this->id)->delete();
	}
}
