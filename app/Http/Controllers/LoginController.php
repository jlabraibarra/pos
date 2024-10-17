<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    function login(Request $request)
    {
        $username = $request->input("username");
        $password = $request->input("password");

        $user = UserModel::where("username",$username)->first();
        if(is_null($user)){
            return [
                "status" => "error",
                "msg" => "El usuario no existe"
            ];
        }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            return [
                "status" => "success",
                "msg" => "Inicio de sesion correcto"
            ];
        }
    }

    function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
