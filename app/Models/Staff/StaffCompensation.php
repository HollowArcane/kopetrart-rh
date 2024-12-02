<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffCompensation extends Model
{
    use HasFactory;

    protected $table = 'staff_compensation';

    public $timestamps = false;

    protected $fillable = [
        'id_staff',
        'motif',
        'date_compensation',
        'amount',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }
}
