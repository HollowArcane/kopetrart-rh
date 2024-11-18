<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\LoginModel;
use App\Models\Role;
use App\Models\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    // Show login page
    public function loginPage()
    {
        return view('auth.login');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $user = LoginModel::authenticate($credentials['username'], $credentials['password']);

        if ($user) {
            $role = RoleModel::getRoleById($user->id_profil);
            Session::put([
                'id_admin' => $user->id_admin,
                'username' => $user->username,
                'role' => $user->id_profil,
                'role_name' => $role->libelle,
                'logged_in' => true,
            ]);

            return redirect()->route('dashboard')->with('success', 'Connexion réussie');
        } else {
            // Retourner en arrière avec un message d'erreur si les identifiants sont incorrects
            return redirect()->back()->withErrors(['error' => 'Identifiants incorrects']);
        }
    }


    // Handle logout request
    public function logout()
    {
        Session::flush(); // Clear session data
        return redirect('/'); // Redirect to the homepage or login page
    }
}
