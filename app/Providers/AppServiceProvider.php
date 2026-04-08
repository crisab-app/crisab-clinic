<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Models\LoginLog;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Candado Anti-Clones y Registro de Logs
        Event::listen(function (Login $event) {
            
            // A) Guardamos el Log
            LoginLog::create([
                'user_id' => $event->user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // B) Revisamos si NO es un paciente para aplicar el candado
            if ($event->user->hasAnyRole(['Superadmin', 'Administrador de Clinica', 'Recepcionista', 'Medico'])) {
                
                // Borramos cualquier otra sesión activa en la base de datos
                DB::table('sessions')
                    ->where('user_id', $event->user->id)
                    ->where('id', '!=', request()->session()->getId())
                    ->delete();
            }
        });

        // 2. Traducir el correo de verificación al español
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifica tu correo electrónico - Adminconsul')
                ->greeting('¡Hola!')
                ->line('Por favor, haz clic en el botón de abajo para verificar tu dirección de correo electrónico y acceder a tu panel.')
                ->action('Verificar Correo', $url)
                ->line('Si no creaste esta cuenta, no es necesario realizar ninguna acción.')
                ->salutation('Saludos, el equipo de Adminconsul');
        });
    }
}