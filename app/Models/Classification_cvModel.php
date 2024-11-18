<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Classification_cvModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'classification_cv';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_cv', 'experience', 'id_talent'];
	private $id;
	private $id_cv;
	private $experience;
	private $id_talent;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_cv = $attributes['id_cv'] ?? null;
			$this->experience = $attributes['experience'] ?? null;
			$this->id_talent = $attributes['id_talent'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_cv() {
		return $this->id_cv;
	}

	public function getExperience() {
		return $this->experience;
	}

	public function getId_talent() {
		return $this->id_talent;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_cv($id_cv) {
		$this->id_cv = $id_cv;
	}

	public function setExperience($experience) {
		$this->experience = $experience;
	}

	public function setId_talent($id_talent) {
		$this->id_talent = $id_talent;
	}

	public static function getAllClassification_cv() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getClassification_cvById($id) {
		return self::find($id);
	}

	public function createClassification_cv() {
		return self::create([
			'id_cv' => $this->id_cv,
			'experience' => $this->experience,
			'id_talent' => $this->id_talent,
		]);
	}

	public function updateClassification_cv() {
		return self::where('id', $this->id)
			->update([
				'id_cv' => $this->id_cv,
				'experience' => $this->experience,
				'id_talent' => $this->id_talent,
			]);
	}

	public function deleteClassification_cv() {
		return self::where('id', $this->id)->delete();
	}

	public function cv()
    {
        return $this->belongsTo(CvModel::class, 'id_cv','id');
    }
    
    public function talent()
    {
        return $this->belongsTo(TalentModel::class, 'id_talent','id');
    }
}
