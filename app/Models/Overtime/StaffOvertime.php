<?php

namespace App\Models\Overtime;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Overtime\OvertimeShift;
use App\Models\Overtime\OvertimeType;
use App\Models\Staff\Staff;

class StaffOvertime extends Model
{
    use HasFactory;

    protected $table = 'staff_overtime';

    public $timestamps = false; 

    protected $fillable = [
        'id_staff',
        'id_overtime_type',
        'id_overtime_shift',
        'date_overtime',
        'quantity_overtime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }

    public function overtimeType()
    {
        return $this->belongsTo(OvertimeType::class, 'id_overtime_type');
    }

    public function overtimeShift()
    {
        return $this->belongsTo(OvertimeShift::class, 'id_overtime_shift');
    }
}
