<?php

namespace App\Models\Classification;
use App\Models\Classification\DenormalizedCv;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DenormalizedCvQuality extends Model
{
    use HasFactory;

    protected $table = 'denormalized_cv_qualities';
    public $timestamps = false;

    protected $fillable = [
        'id_cv_denormalized',
        'id_quality',
    ];

    public function denormalizedCv()
    {
        return $this->belongsTo(DenormalizedCv::class, 'id_cv_denormalized');
    }
}
