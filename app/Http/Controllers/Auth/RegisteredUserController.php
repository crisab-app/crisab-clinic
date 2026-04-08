<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Clinic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validamos los 7 datos que le pedimos al cliente
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'clinic_name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'timezone' => ['required', 'string', 'max:100'],
        ]);

        // 2. Usamos la Transacción: O se guarda todo, o se cancela todo
        $user = DB::transaction(function () use ($request) {
            
            // A) Creamos la Clínica y generamos su UUID Visual
            $clinic = Clinic::create([
                'name' => $request->clinic_name,
                'visual_id' => 'CLINIC-' . strtoupper(Str::random(6)),
                'country' => $request->country,
                'timezone' => $request->timezone,
            ]);

            // B) Creamos al dueño y lo amarramos a la clínica que acabamos de crear
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'clinic_id' => $clinic->id,
            ]);

            // C) Le asignamos los poderes de Administrador de su clínica
            $user->assignRole('Administrador de Clinica');

            return $user;
        });
     
        // 3. DISPARAMOS EL EVENTO DE CORREO:
        event(new Registered($user)); // Usando la clase importada arriba

        // 4. Autenticamos al usuario recién creado y lo mandamos a su panel
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
    // --- NUEVAS FUNCIONES PARA EL REGISTRO CON GOOGLE ---

    // 1. Mostrar el formulario corto
    public function createGoogle(): View|\Illuminate\Http\RedirectResponse
    {
        // Si alguien intenta entrar aquí sin venir de Google, lo pateamos al registro normal
        if (!session()->has('google_email')) {
            return redirect()->route('register');
        }
        return view('auth.register-google');
    }

    // 2. Guardar la clínica y al usuario de Google
    public function storeGoogle(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'clinic_name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'timezone' => ['required', 'string', 'max:100'],
        ]);

        $user = DB::transaction(function () use ($request) {
            
            $clinic = Clinic::create([
                'name' => $request->clinic_name,
                'visual_id' => 'CLINIC-' . strtoupper(Str::random(6)),
                'country' => $request->country,
                'timezone' => $request->timezone,
            ]);

            $user = User::create([
                'name' => session('google_name'),
                'email' => session('google_email'),
                'phone' => $request->phone,
                // Como entra con Google, le generamos una contraseña larguísima y aleatoria que nunca usará
                'password' => Hash::make(Str::random(24)), 
                'clinic_id' => $clinic->id,
                // ¡Magia! Como Google ya verificó que el correo es real, lo marcamos como verificado automáticamente
                'email_verified_at' => now(), 
            ]);

            $user->assignRole('Administrador de Clinica');

            return $user;
        });
     
        // Borramos los datos temporales
        session()->forget(['google_name', 'google_email']);

        // Lo dejamos entrar
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}