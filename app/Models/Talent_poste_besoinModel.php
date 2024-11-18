<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Talent_poste_besoinModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'talent_poste_besoin';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_talent', 'id_besoin_poste', 'expmin', 'expmax', 'etude'];
	private $id;
	private $id_talent;
	private $id_besoin_poste;
	private $expmin;
	private $expmax;
	private $etude;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_talent = $attributes['id_talent'] ?? null;
			$this->id_besoin_poste = $attributes['id_besoin_poste'] ?? null;
			$this->expmin = $attributes['expmin'] ?? null;
			$this->expmax = $attributes['expmax'] ?? null;
			$this->etude = $attributes['etude'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_talent() {
		return $this->id_talent;
	}

	public function getId_besoin_poste() {
		return $this->id_besoin_poste;
	}

	public function getExpmin() {
		return $this->expmin;
	}

	public function getExpmax() {
		return $this->expmax;
	}

	public function getEtude() {
		return $this->etude;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_talent($id_talent) {
		$this->id_talent = $id_talent;
	}

	public function setId_besoin_poste($id_besoin_poste) {
		$this->id_besoin_poste = $id_besoin_poste;
	}

	public function setExpmin($expmin) {
		$this->expmin = $expmin;
	}

	public function setExpmax($expmax) {
		$this->expmax = $expmax;
	}

	public function setEtude($etude) {
		$this->etude = $etude;
	}

	public static function getAllTalent_poste_besoin() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getTalent_poste_besoinById($id) {
		return self::find($id);
	}

	public function createTalent_poste_besoin() {
		return self::create([
			'id_talent' => $this->id_talent,
			'id_besoin_poste' => $this->id_besoin_poste,
			'expmin' => $this->expmin,
			'expmax' => $this->expmax,
			'etude' => $this->etude,
		]);
	}

	public function updateTalent_poste_besoin() {
		return self::where('id', $this->id)
			->update([
				'id_talent' => $this->id_talent,
				'id_besoin_poste' => $this->id_besoin_poste,
				'expmin' => $this->expmin,
				'expmax' => $this->expmax,
				'etude' => $this->etude,
			]);
	}

	public function deleteTalent_poste_besoin() {
		return self::where('id', $this->id)->delete();
	}
	
	public function talent(){
		return $this->belongsTo(TalentModel::class, 'id_talent', 'id');
	}
}
