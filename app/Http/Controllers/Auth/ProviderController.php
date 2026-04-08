<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            } else {
                // ES NUEVO: Guardamos sus datos en la memoria temporal (sesión)
                session([
                    'google_name' => $googleUser->getName(),
                    'google_email' => $googleUser->getEmail(),
                ]);
                
                // Lo mandamos a la pantalla rápida para crear su clínica
                return redirect()->route('register.google');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Hubo un error al conectar con Google.');
        }
    }
}