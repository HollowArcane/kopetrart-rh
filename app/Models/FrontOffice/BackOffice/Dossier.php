<?php

namespace App\Models\FrontOffice\BackOffice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dossier extends Model
{

	protected $table = 'dossiers';
	public $timestamps = false;

	protected $fillable = ['id', 'candidat', 'email', 'id_besoin_poste', 'date_reception', 'statut', 'cv', 'lettre_motivation','esttraduit'];

    public static function get($username, $email)
    {
        return DB::table('dossiers', 'd')
                ->join('besoin_poste AS bp', 'd.id_besoin_poste', '=', 'bp.id')
                ->join('postes AS p', 'bp.id_poste', '=', 'p.id')
                ->select('d.id', 'p.libelle AS poste', 'd.date_reception AS date_deposit', 'd.progression_status AS state', 'd.progression')
                ->where('d.candidat', $username)
                ->orWhere('d.email', $email)
                ->get();
    }

    public static function options()
    {
        return DB::table('besoin_poste', 'bp')
                ->join('postes AS p', 'bp.id_poste', '=', 'p.id')
                ->pluck('p.libelle', 'bp.id');
    }
}
