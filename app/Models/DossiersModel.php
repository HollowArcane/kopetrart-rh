<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DossiersModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'dossiers';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'candidat', 'email', 'id_besoin_poste', 'date_reception', 'statut', 'cv', 'lettre_motivation','esttraduit'];
	private $id;
	private $candidat;
	private $email;
	private $estTraduit;
	private $id_besoin_poste;
	private $date_reception;
	private $statut;
	private $cv;
	private $lettre_motivation;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->candidat = $attributes['candidat'] ?? null;
			$this->email = $attributes['email'] ?? null;
			$this->id_besoin_poste = $attributes['id_besoin_poste'] ?? null;
			$this->date_reception = $attributes['date_reception'] ?? null;
			$this->statut = $attributes['statut'] ?? null;
			$this->cv = $attributes['cv'] ?? null;
			$this->lettre_motivation = $attributes['lettre_motivation'] ?? null;
			$this->estTraduit = $attributes['esttraduit'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getCandidat() {
		return $this->candidat;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getId_besoin_poste() {
		return $this->id_besoin_poste;
	}

	public function getDate_reception() {
		return $this->date_reception;
	}

	public function getStatut() {
		return $this->statut;
	}

	public function getCv() {
		return $this->cv;
	}

	public function getLettre_motivation() {
		return $this->lettre_motivation;
	}

	public function setId($id) {
		$this->id = $id;
	}
	public function setEstTraduit($bol) {
		$this->estTraduit = $bol;
	}

	public function setCandidat($candidat) {
		$this->candidat = $candidat;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setId_besoin_poste($id_besoin_poste) {
		$this->id_besoin_poste = $id_besoin_poste;
	}

	public function setDate_reception($date_reception) {
		$this->date_reception = $date_reception;
	}

	public function setStatut($statut) {
		$this->statut = $statut;
	}

	public function setCv($cv) {
		$this->cv = $cv;
	}

	public function setLettre_motivation($lettre_motivation) {
		$this->lettre_motivation = $lettre_motivation;
	}

	public static function getAllDossiers() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getDossierById($id) {
		return self::find($id);
	}

	public function createDossier() {
		return self::create([
			'candidat' => $this->candidat,
			'email' => $this->email,
			'id_besoin_poste' => $this->id_besoin_poste,
			'date_reception' => $this->date_reception,
			'statut' => $this->statut,
			'cv' => $this->cv,
			'lettre_motivation' => $this->lettre_motivation,
		]);
	}

	public function updateDossier() {
		return self::where('id', $this->id)
			->update([
				'candidat' => $this->candidat,
				'email' => $this->email,
				'id_besoin_poste' => $this->id_besoin_poste,
				'date_reception' => $this->date_reception,
				'statut' => $this->statut,
				'cv' => $this->cv,
				'lettre_motivation' => $this->lettre_motivation,
			]);
	}

    public function updateEstTraduit() {
		return self::where('id', $this->id)
			->update([
				'esttraduit' => $this->estTraduit,
			]);
	}

	public function deleteDossier() {
		return self::where('id', $this->id)->delete();
	}
	public function besoinPoste()
    {
        return $this->belongsTo(Besoin_posteModel::class, 'id_besoin_poste', 'id');
    }

}
