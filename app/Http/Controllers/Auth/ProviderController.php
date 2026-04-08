<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    // 1. Redirigir al usuario a la pantalla de Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Cuando Google nos devuelve al usuario aprobado
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Buscamos si el correo de Google ya existe en nuestro sistema
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Si el usuario ya existe, lo dejamos entrar
                Auth::login($user);
                return redirect()->route('dashboard');
            } else {
                // Si es nuevo, lo mandamos a registrar su clínica
                return redirect()->route('register')
                                 ->with('error', 'Tu correo no está registrado en ninguna clínica. Por favor llena este formulario para crear tu cuenta.');
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Hubo un error al conectar con Google.');
        }
    }
}