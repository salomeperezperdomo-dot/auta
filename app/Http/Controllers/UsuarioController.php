<?php
namespace App\Http\Controllers;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller {

    public function login(Request $request) {
        $usuario = Usuario::where('usuario', $request->usuario)
                          ->where('contrasena', $request->contrasena)
                          ->where('rol', $request->rol)
                          ->first();
        if (!$usuario) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
        return response()->json($usuario);
    }
}