<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCandidateResult extends Model
{
    use HasFactory;

    protected $table = 'test_candidate_result';
    public $timestamps = false;

    static function get_valid()
    { return TestCandidateResult::find(1); }

    static function get_invalid()
    { return TestCandidateResult::find(2); }

    static function get_empty()
    { return TestCandidateResult::find(3); }
}
