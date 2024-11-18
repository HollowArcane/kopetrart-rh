<?php

namespace App\Http\Controllers\FrontOffice;

use App\Models\FrontOffice\Message;
use Illuminate\Http\Request;

class MessageController
{
    public function create(Request $request)
    {
        if(session('user') == null)
        { return redirect('/front'); }

        $request->validate([
            'id_login_sender' => 'nullable|exists:pgsql2.login,id',
            'id_login_target' => 'nullable|exists:pgsql2.login,id',
            'created_at' => 'required',
            'content' => 'required|string'
        ]);

        $message = new Message();
        $message->id_login_sender = $request->input('id_login_sender');
        $message->id_login_target = $request->input('id_login_target');
        $message->created_at = $request->input('created_at');
        $message->content = $request->input('content');

        $message->save();

        return response()->json([
            'message' => 'Message created successfully',
           'message_id' => $message->id
        ], 201);
    }
}
