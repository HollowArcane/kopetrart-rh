<?php

namespace App\Models\Advance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Staff\Staff;

class SalaryAdvance extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'salary_advance';

    protected $fillable = [
        'id_staff',
        'date_advance',
        'amount',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }
}