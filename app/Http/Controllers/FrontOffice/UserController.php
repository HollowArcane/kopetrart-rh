<?php

namespace App\Http\Controllers\FrontOffice;

use App\Models\FrontOffice\User;
use Illuminate\Http\Request;
use stdClass;

class UserController
{
    private $login_view = 'pages.front-office.login.login';
    private $register_view = 'pages.front-office.login.register';

    public function index_login()
    {
        session(['user' => null]);

        return view($this->login_view)->with('url', '/front/login');
    }

    public function index_register()
    {
        session(['user' => null]);
        return view($this->register_view)->with('url', '/front/register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|alpha_dash',
            'password' => 'required'
        ]);

        $user = User::where([
            'username' => $request->input('username'),
            'password' => hash('sha256', $request->input('password'))
        ])->first();

        if(!$user)
        { return redirect('/front/login')->with('error', 'Compte inexistant'); }

        $session_user = new stdClass();
        $session_user->id = $user->id;
        $session_user->username = $user->username;
        $session_user->email = $user->email;

        session(['user' => $session_user]);
        return redirect('/front/home');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|alpha_dash',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = hash('sha256', $request->input('password'));

        $user->save();

        return redirect('/front/login')->with('success', 'Inscription effectué avec succès');
    }
}
