<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Model_communicationModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'model_communication';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'type', 'status', 'model'];
	private $id;
	private $type;
	private $status;
	private $model;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->type = $attributes['type'] ?? null;
			$this->status = $attributes['status'] ?? null;
			$this->model = $attributes['model'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getType() {
		return $this->type;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getModel() {
		return $this->model;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setModel($model) {
		$this->model = $model;
	}

	public static function getAllModel_communication() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getModel_communicationById($id) {
		return self::find($id);
	}

	public function createModel_communication() {
		return self::create([
			'type' => $this->type,
			'status' => $this->status,
			'model' => $this->model,
		]);
	}

	public function updateModel_communication() {
		return self::where('id', $this->id)
			->update([
				'type' => $this->type,
				'status' => $this->status,
				'model' => $this->model,
			]);
	}

	public function deleteModel_communication() {
		return self::where('id', $this->id)->delete();
	}
}
