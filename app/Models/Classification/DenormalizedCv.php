<?php

namespace App\Models\Classification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Classification\DenormalizedCvEducation;
use App\Models\Classification\DenormalizedCvInterest;
use App\Models\Classification\DenormalizedCvQuality;


class DenormalizedCv extends Model
{
    use HasFactory;

    protected $table = 'denormalized_cv';
    public $timestamps = false;


    protected $fillable = [
        'id_cv',
        'candidat_name',
        'poste',
        'date_depot_dossier',
        'adequate',
        'potentiel',
    ];

    public function interests()
    {
        return $this->hasMany(DenormalizedCvInterest::class, 'id_cv_denormalized')
            ->join('interests', 'denormalized_cv_interests.id_interest', '=', 'interests.id')
            ->select('denormalized_cv_interests.*', 'interests.label');
    }

    public function qualities()
    {
        return $this->hasMany(DenormalizedCvQuality::class, 'id_cv_denormalized')
            ->join('qualities', 'denormalized_cv_qualities.id_quality', '=', 'qualities.id')
            ->select('denormalized_cv_qualities.*', 'qualities.label');
    }

    public function educations()
    {
        return $this->hasMany(DenormalizedCvEducation::class, 'id_cv_denormalized')
            ->join('educations', 'denormalized_cv_education.id_education', '=', 'educations.id')
            ->select('denormalized_cv_education.*', 'educations.label');
    }

    public function experiences()
    {
        return $this->hasMany(DenormalizedCvExperience::class, 'id_cv_denormalized');
    }
}