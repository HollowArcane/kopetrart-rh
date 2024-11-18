<?php

namespace App\Models\FrontOffice\BackOffice\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Test extends Model
{
    use HasFactory;


    protected $table = 'test';
    public $timestamps = false;

    protected $fillable = ['title', 'goal', 'requirements', 'id_need'];

    public static function get_relevant($username, $email): Collection
    {
        return DB::table('dossiers AS d')
                    ->join('besoin_poste AS bp', 'd.id_besoin_poste', '=', 'bp.id')
                    ->join('cv', 'd.id', '=', 'cv.id_dossier')
                    ->leftJoin('test_candidate AS tc', 'tc.id_cv_candidate', '=', 'cv.id')
                    ->leftJoin('test_candidate_result AS tcr', 'tc.id_result', '=', 'tcr.id')
                    ->leftJoin('v_test AS t', 't.id_need', '=', 'bp.id')
                    ->select('t.id', 'cv.id AS id_cv', 't.title', 't.goal', 't.need', 't.duration', 'tc.id AS id_test','tc.score', 'tc.id_result', 'tcr.label AS result')
                    ->where('d.candidat', $username)
                    ->where('d.email', $email)
                    ->get();
    }

    public function parts()
    {
        return $this->hasMany(TestPart::class, 'id_test');
    }

    public function criteria()
    {
        return $this->hasMany(TestCriterion::class, 'id_test');
    }

    public function points()
    {
        return $this->hasMany(TestPoint::class, 'id_test');
    }
}
