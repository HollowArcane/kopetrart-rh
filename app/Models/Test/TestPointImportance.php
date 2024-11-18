<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPointImportance extends Model
{
    use HasFactory;

    protected $table = 'test_point_importance';
    public $timestamps = false;
}
