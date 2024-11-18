<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPoint extends Model
{
    use HasFactory;

    protected $table = 'test_point';
    public $timestamps = false;

    protected $fillable = ['label', 'id_importance', 'id_test'];

    public function importance()
    {
        return $this->hasOne(TestPointImportance::class, 'id', 'id_importance');
    }
}
