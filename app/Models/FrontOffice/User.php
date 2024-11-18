<?php

namespace App\Models\FrontOffice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $connection = 'pgsql2';

    public $table = 'login';
    public $timestamps = false;
}
