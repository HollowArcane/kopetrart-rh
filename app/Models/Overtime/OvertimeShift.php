<?php

namespace App\Models\Overtime;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeShift extends Model
{
    use HasFactory;

    protected $table = 'overtime_shift';

    protected $fillable = [
        'label',
    ];
}
