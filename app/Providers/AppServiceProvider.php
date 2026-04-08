<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Models\LoginLog;

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
        // ... (cualquier código que ya tengas aquí) ...

        // Escuchar cada inicio de sesión exitoso y guardarlo en la base de datos
        Event::listen(function (Login $event) {
            LoginLog::create([
                'user_id' => $event->user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifica tu correo electrónico - Adminconsul')
                ->greeting('¡Hola!')
                ->line('Por favor, haz clic en el botón de abajo para verificar tu dirección de correo electrónico y acceder a tu panel.')
                ->action('Verificar Correo', $url)
                ->line('Si no creaste esta cuenta, no es necesario realizar ninguna acción.')
                ->salutation('Saludos, el equipo de Adminconsul');
        });
        });
    }
}
