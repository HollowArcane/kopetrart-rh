<?php

namespace App\Models\FrontOffice\BackOffice\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCriterion extends Model
{
    use HasFactory;

    protected $table = 'test_criterion';
    public $timestamps = false;
}
