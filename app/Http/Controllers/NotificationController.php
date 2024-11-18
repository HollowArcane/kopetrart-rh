<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function see($id_notification)
    {
        $id_role = session('role');
        $notification = Notification::get($id_role, $id_notification);

        if($notification->date_seen == null)
        {
            $admins = DB::table('admin')->select('*')->where('id_profil', $id_role)->get();

            $notification = Notification::find($id_notification);
            foreach($admins as $admin)
            {
                $id_admin = $admin->id_admin;

                DB::table('notification_seen')
                    ->insert([
                        'id_admin' => $id_admin,
                        'id_notification' => $id_notification
                    ]);
            }
        }


        return redirect($notification->redirection);
    }
}
