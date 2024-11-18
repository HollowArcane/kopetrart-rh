<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCriterionCandidate extends Model
{
    use HasFactory;

    protected $table = 'test_candidate_criteria';
    public $timestamps = false;
}
