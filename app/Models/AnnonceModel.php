<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnnonceModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'annonce';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'id_besoin_poste','is_validate'];
	private $id;
	private $id_besoin_poste;
    private $is_validate;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->id_besoin_poste = $attributes['id_besoin_poste'] ?? null;
            $this->is_validate = $attributes['is_validate']?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getId_besoin_poste() {
		return $this->id_besoin_poste;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setId_besoin_poste($id_besoin_poste) {
		$this->id_besoin_poste = $id_besoin_poste;
	}

    public function setIs_validate($is_validate) {
        $this->is_validate = $is_validate;
    }

    public function getIs_validate() {
        return $this->is_validate;
    }

	public static function getAllAnnonce() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getAnnonceById($id) {
		return self::find($id);
	}

	public function createAnnonce() {
		return self::create([
			'id_besoin_poste' => $this->id_besoin_poste,
            'is_validate' => $this->is_validate
		]);
	}

	public function updateAnnonce() {
		return self::where('id', $this->id)
			->update([
				'id_besoin_poste' => $this->id_besoin_poste,
                'is_validate' => $this->is_validate
			]);
	}

	public function deleteAnnonce() {
		return self::where('id', $this->id)->delete();
	}


    public static function getAnnonce()
    {
        $query = DB::table('v_get_annonce');
        $result = $query->get();

        if ($result->isNotEmpty()) {
            return $result->toArray();
        } else {
            return [];
        }
    }


}
