<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPart extends Model
{
    use HasFactory;

    protected $table = 'test_part';
    public $timestamps = false;
}
