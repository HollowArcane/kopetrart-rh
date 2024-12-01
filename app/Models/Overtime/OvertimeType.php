<?php

namespace App\Models\Overtime;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeType extends Model
{
    use HasFactory;

    protected $table = 'overtime_type';

    protected $fillable = [
        'label',
        'rate',
    ];
}
