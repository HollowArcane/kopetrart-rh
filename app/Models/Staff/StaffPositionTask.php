<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaffPositionTask extends Model
{
    use HasFactory;

    public $table = 'staff_position_task';
    public $timestamps = false;

    public static function read_by_position($id_position)
    {
        return DB::table('staff_position_task')->where('id_staff_position', '=', $id_position);
    }
}
