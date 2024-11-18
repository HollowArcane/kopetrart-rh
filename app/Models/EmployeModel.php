<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'employe';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_departement', 'id_poste', 'date', 'id_contrat'];
	private $id;
	private $id_departement;
	private $id_poste;
	private $date;
	private $id_contrat;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_departement = $attributes['id_departement'] ?? null;
			$this->id_poste = $attributes['id_poste'] ?? null;
			$this->date = $attributes['date'] ?? null;
			$this->id_contrat = $attributes['id_contrat'] ?? null;
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

	public function getDate() {
		return $this->date;
	}

	public function getId_contrat() {
		return $this->id_contrat;
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

	public function setDate($date) {
		$this->date = $date;
	}

	public function setId_contrat($id_contrat) {
		$this->id_contrat = $id_contrat;
	}

	public static function getAllEmploye() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getEmployeById($id) {
		return self::find($id);
	}

	public function createEmploye() {
		return self::create([
			'id_departement' => $this->id_departement,
			'id_poste' => $this->id_poste,
			'date' => $this->date,
			'id_contrat' => $this->id_contrat,
		]);
	}

	public function updateEmploye() {
		return self::where('id', $this->id)
			->update([
				'id_departement' => $this->id_departement,
				'id_poste' => $this->id_poste,
				'date' => $this->date,
				'id_contrat' => $this->id_contrat,
			]);
	}

	public function deleteEmploye() {
		return self::where('id', $this->id)->delete();
	}


    public static function getEmpList($besoin_poste_id = null)
    {
        try {
            if ($besoin_poste_id === null) {
                $query = DB::select('SELECT * FROM get_matching_employees()');
            } else {
                $query = DB::select('SELECT * FROM get_matching_employees(?)', [$besoin_poste_id]);
            }

            return !empty($query) ? $query : [];

        } catch (\Exception $e) {
            return [];
        }
    }
    public static function insererDonneesEmploye()
    {
        try {
			Log::info("---------Executing Procedure");
            DB::select("CALL inserer_donnees_employe()");
            return true;
        } 
		
		catch (\Exception $e) {
            \Log::error('Erreur lors de l\'insertion des données employé: ' . $e->getMessage());
            return false;
        }
    }

}
