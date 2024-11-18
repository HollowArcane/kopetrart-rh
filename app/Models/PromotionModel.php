<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PromotionModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'promotion';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_employe', 'id_poste', 'date', 'salaire', 'status'];
	private $id;
	private $id_employe;
	private $id_poste;
	private $date;
	private $salaire;
	private $status;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_employe = $attributes['id_employe'] ?? null;
			$this->id_poste = $attributes['id_poste'] ?? null;
			$this->date = $attributes['date'] ?? null;
			$this->salaire = $attributes['salaire'] ?? null;
			$this->status = $attributes['status'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_employe() {
		return $this->id_employe;
	}

	public function getId_poste() {
		return $this->id_poste;
	}

	public function getDate() {
		return $this->date;
	}

	public function getSalaire() {
		return $this->salaire;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_employe($id_employe) {
		$this->id_employe = $id_employe;
	}

	public function setId_poste($id_poste) {
		$this->id_poste = $id_poste;
	}

	public function setDate($date) {
		$this->date = $date;
	}

	public function setSalaire($salaire) {
		$this->salaire = $salaire;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public static function getAllPromotion() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getPromotionById($id) {
		return self::find($id);
	}

	public function createPromotion() {
		return self::create([
			'id_employe' => $this->id_employe,
			'id_poste' => $this->id_poste,
			'date' => $this->date,
			'salaire' => $this->salaire,
			'status' => $this->status,
		]);
	}

    public function updatePromotionStatus() {
		return self::where('id', $this->id)
			->update([
				'status' => $this->status,
			]);
	}

	public function deletePromotion() {
		return self::where('id', $this->id)->delete();
	}

    public static function getPromotion()
    {
        $query = DB::table('v_liste_promotions');

        $result = $query->get();

        if ($result->isNotEmpty()) {
            return $result->toArray();
        } else {
            return [];
        }
    }

}
