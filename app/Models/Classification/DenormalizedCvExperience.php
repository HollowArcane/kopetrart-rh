<?php

namespace App\Models\Classification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Classification\DenormalizedCv;

class DenormalizedCvExperience extends Model
{
    use HasFactory;

    protected $table = 'denormalized_cv_experiences';
    public $timestamps = false;


    protected $fillable = [
        'id_cv_denormalized',
        'label',
        'month_duration',
    ];

    public function denormalizedCv()
    {
        return $this->belongsTo(DenormalizedCv::class, 'id_cv_denormalized');
    }
}
