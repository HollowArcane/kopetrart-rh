<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GenreModel extends Model {
	// Table name is assumed to be pluralized by Laravel
	protected $table = 'genre';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['id', 'libelle'];
	private $id;
	private $libelle;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (!empty($attributes)) {
			$this->id = $attributes['id'] ?? null;
			$this->libelle = $attributes['libelle'] ?? null;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getLibelle() {
		return $this->libelle;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setLibelle($libelle) {
		$this->libelle = $libelle;
	}

	public static function getAllGenre() {
		return self::orderBy('id', 'DESC')->get();
	}

	public static function getGenreById($id) {
		return self::find($id);
	}

	public function createGenre() {
		return self::create([
			'libelle' => $this->libelle,
		]);
	}

	public function updateGenre() {
		return self::where('id', $this->id)
			->update([
				'libelle' => $this->libelle,
			]);
	}

	public function deleteGenre() {
		return self::where('id', $this->id)->delete();
	}
}
