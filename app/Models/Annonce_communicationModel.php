<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Annonce_communicationModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'annonce_communication';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_annonce', 'id_moyenne_comm', 'date'];
	private $id;
	private $id_annonce;
	private $id_moyenne_comm;
	private $date;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_annonce = $attributes['id_annonce'] ?? null;
			$this->id_moyenne_comm = $attributes['id_moyenne_comm'] ?? null;
			$this->date = $attributes['date'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_annonce() {
		return $this->id_annonce;
	}

	public function getId_moyenne_comm() {
		return $this->id_moyenne_comm;
	}

	public function getDate() {
		return $this->date;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_annonce($id_annonce) {
		$this->id_annonce = $id_annonce;
	}

	public function setId_moyenne_comm($id_moyenne_comm) {
		$this->id_moyenne_comm = $id_moyenne_comm;
	}

	public function setDate($date) {
		$this->date = $date;
	}

	public static function getAllAnnonce_communication() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getAnnonce_communicationById($id) {
		return self::find($id);
	}

	public function createAnnonce_communication() {
		return self::create([
			'id_annonce' => $this->id_annonce,
			'id_moyenne_comm' => $this->id_moyenne_comm,
			'date' => $this->date,
		]);
	}

	public function updateAnnonce_communication() {
		return self::where('id', $this->id)
			->update([
				'id_annonce' => $this->id_annonce,
				'id_moyenne_comm' => $this->id_moyenne_comm,
				'date' => $this->date,
			]);
	}

	public function deleteAnnonce_communication() {
		return self::where('id', $this->id)->delete();
	}

    public static function getplublicite()
    {
        $query = DB::table('v_getplublicite');
        $result = $query->get();

        if ($result->isNotEmpty()) {
            return $result->toArray();
        } else {
            return [];
        }
    }
}
