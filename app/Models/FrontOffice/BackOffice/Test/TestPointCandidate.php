<?php

namespace App\Models\FrontOffice\BackOffice\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPointCandidate extends Model
{
    use HasFactory;

    protected $table = 'test_candidate_point';
    public $timestamps = false;
}
