<?php

namespace App\Models\Impot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Staff\Staff;

class ImpotDue extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'impot_due';

    protected $fillable = [
        'id_staff',
        'date_due',
        'amount',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }
}