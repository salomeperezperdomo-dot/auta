<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller {

    public function login(Request $request) {
        $user = User::where('usuario', $request->usuario)
                     ->where('rol', $request->rol)
                     ->first();

        if (!$user || !Hash::check($request->contrasena, $user->password)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // Crea una sesión real de Laravel (antes solo se devolvían los
        // datos y el "login" vivía únicamente en sessionStorage del
        // navegador, sin que el servidor supiera quién había entrado).
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json($user);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['ok' => true]);
    }
}
