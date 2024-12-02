<?php

namespace App\Models\Bonus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Staff\Staff;

class PerformanceBonus extends Model
{
    use HasFactory;

    protected $table = 'performance_bonus';

    public $timestamps = false;

    protected $fillable = [
        'id_staff',
        'date_bonus',
        'performance',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }
}
