<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Besoin_posteModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'besoin_poste';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_poste', 'id_genre', 'id_contrat', 'agemin', 'finvalidite', 'priorite', 'info_sup', 'personneltrouver'];
	private $id;
	private $id_poste;
	private $id_genre;
	private $id_contrat;
	private $agemin;
	private $finvalidite;
	private $priorite;
	private $info_sup;
	private $personneltrouver;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_poste = $attributes['id_poste'] ?? null;
			$this->id_genre = $attributes['id_genre'] ?? null;
			$this->id_contrat = $attributes['id_contrat'] ?? null;
			$this->agemin = $attributes['agemin'] ?? null;
			$this->finvalidite = $attributes['finvalidite'] ?? null;
			$this->priorite = $attributes['priorite'] ?? null;
			$this->info_sup = $attributes['info_sup'] ?? null;
			$this->personneltrouver = $attributes['personneltrouver'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_poste() {
		return $this->id_poste;
	}

	public function getId_genre() {
		return $this->id_genre;
	}

	public function getId_contrat() {
		return $this->id_contrat;
	}

	public function getAgemin() {
		return $this->agemin;
	}

	public function getFinvalidite() {
		return $this->finvalidite;
	}

	public function getPriorite() {
		return $this->priorite;
	}

	public function getInfo_sup() {
		return $this->info_sup;
	}

	public function getPersonneltrouver() {
		return $this->personneltrouver;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_poste($id_poste) {
		$this->id_poste = $id_poste;
	}

	public function setId_genre($id_genre) {
		$this->id_genre = $id_genre;
	}

	public function setId_contrat($id_contrat) {
		$this->id_contrat = $id_contrat;
	}

	public function setAgemin($agemin) {
		$this->agemin = $agemin;
	}

	public function setFinvalidite($finvalidite) {
		$this->finvalidite = $finvalidite;
	}

	public function setPriorite($priorite) {
		$this->priorite = $priorite;
	}

	public function setInfo_sup($info_sup) {
		$this->info_sup = $info_sup;
	}

	public function setPersonneltrouver($personneltrouver) {
		$this->personneltrouver = $personneltrouver;
	}

	public static function getAllBesoin_poste() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getBesoin_posteById($id) {
		return self::find($id);
	}

	public function createBesoin_poste() {
        $inserted = self::create([
            'id_poste' => $this->id_poste,
            'id_genre' => $this->id_genre,
            'id_contrat' => $this->id_contrat,
            'agemin' => $this->agemin,
            'finvalidite' => $this->finvalidite,
            'priorite' => $this->priorite,
            'info_sup' => $this->info_sup,
            'personneltrouver' => $this->personneltrouver,
        ]);

        return $inserted->getKey();  // This returns the inserted ID
    }

	public function updateBesoin_poste() {
		return self::where('id', $this->id)
			->update([
				'id_poste' => $this->id_poste,
				'id_genre' => $this->id_genre,
				'id_contrat' => $this->id_contrat,
				'agemin' => $this->agemin,
				'finvalidite' => $this->finvalidite,
				'priorite' => $this->priorite,
				'info_sup' => $this->info_sup,
				'personneltrouver' => $this->personneltrouver,
			]);
	}

    public static function getBesoinsTalent()
    {
        $result = DB::table('v_besoins_talent')->get();

        if ($result->isNotEmpty()) {
            return $result->toArray();
        } else {
            return [];
        }
    }

    public static function getBesoinsTalentWithFilter($posteId = null, $departementId = null)
    {
        $query = DB::table('v_besoins_talent');

        if ($posteId) {
            $query->where('id_poste', $posteId);
        }

        if ($departementId) {
            $query->where('id_departement', $departementId);
        }

        $result = $query->get();

        if ($result->isNotEmpty()) {
            return $result->toArray();
        } else {
            return [];
        }
    }

    public static function getBesoinsById($id_besoin = null)
    {
        $query = DB::table('v_besoins_talent');

        if ($id_besoin) {
            $query->where('id', $id_besoin);
        }
        $result = $query->get();

        if ($result->isNotEmpty()) {
            return $result->toArray();
        } else {
            return [];
        }
    }



	public function deleteBesoin_poste() {
		return self::where('id', $this->id)->delete();
	}

	public function poste() {
		return $this->belongsTo(PostesModel::class, 'id_poste', 'id');
	}
}
