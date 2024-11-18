<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCandidate extends Model
{
    use HasFactory;

    protected $table = 'test_candidate';
    public $timestamps = false;
}
