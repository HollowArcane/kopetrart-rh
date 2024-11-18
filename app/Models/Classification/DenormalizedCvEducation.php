<?php

namespace App\Models\Classification;
use App\Models\Classification\DenormalizedCv;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DenormalizedCvEducation extends Model
{
    use HasFactory;

    protected $table = 'denormalized_cv_education';
    public $timestamps = false;


    protected $fillable = [
        'id_cv_denormalized',
        'id_education',
    ];

    public function denormalizedCv()
    {
        return $this->belongsTo(DenormalizedCv::class, 'id_cv_denormalized');
    }
}
