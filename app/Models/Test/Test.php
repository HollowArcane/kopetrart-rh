<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $table = 'test';
    public $timestamps = false;

    protected $fillable = ['title', 'goal', 'requirements', 'id_need'];

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
