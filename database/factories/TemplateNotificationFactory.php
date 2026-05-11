<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as TemplateNotificationModel;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

/**
 * @extends Factory<TemplateNotificationModel>
 */
class TemplateNotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TemplateNotificationModel>
     */
    protected $model = TemplateNotificationModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => 'welcome-user',
            'locale' => 'en',
            'subject' => '¡Bienvenido a {{appName}}, {{name}}!',
            'body_html' => '<!doctype html>
            <html lang="es">
              <head>
                <meta charset="utf-8">
                <title>Bienvenido a {{appName}}</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <style>
                  /* Estilos básicos inline-friendly para email */
                  .wrapper { background:#f6f7fb; padding:24px; }
                  .card { max-width:620px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
                  .header { background:#5b3cc4; color:#fff; padding:22px 28px; font-family:Arial,Helvetica,sans-serif; }
                  .header h1 { margin:0; font-size:20px; line-height:1.3; }
                  .body { padding:24px 28px; color:#333; font-family:Arial,Helvetica,sans-serif; }
                  .body h2 { margin:0 0 8px; font-size:18px; color:#111; }
                  .body p { margin:12px 0; font-size:15px; line-height:1.6; }
                  .cta { text-align:center; margin:24px 0; }
                  .btn { display:inline-block; padding:12px 18px; border-radius:8px; text-decoration:none; background:#5b3cc4; color:#fff !important; font-weight:bold; }
                  .note { font-size:12px; color:#666; }
                  .footer { padding:18px 28px; background:#fafafa; color:#666; font-size:12px; font-family:Arial,Helvetica,sans-serif; }
                  a { color:#5b3cc4; }
                  @media (prefers-color-scheme: dark) {
                    .wrapper { background:#0f1115; }
                    .card { background:#151822; }
                    .header { background:#6b52ff; }
                    .body { color:#e6e6e6; }
                    .footer { background:#10131a; color:#a9b0bd; }
                    a { color:#9aa7ff; }
                  }
                </style>
              </head>
              <body>
                <div class="wrapper">
                  <div class="card">
                    <div class="header">
                      <h1>¡Bienvenido a {{appName}}, {{name}}!</h1>
                    </div>
                    <div class="body">
                      <h2>Tu comunidad, organizada y a un clic</h2>
                      <p>
                        Gracias por registrarte en <strong>{{appName}}</strong>. Desde ahora podrás:
                      </p>
                      <ul style="margin:12px 0 0 18px; padding:0; font-size:15px; line-height:1.6;">
                        <li>Recibir avisos y comunicados del condominio en tiempo real.</li>
                        <li>Reservar áreas comunes y gestionar tus solicitudes.</li>
                        <li>Consultar cuotas, estados de cuenta y documentos importantes.</li>
                      </ul>

                      <div class="cta">
                        <a class="btn" href="{{activationLink}}">Activar mi cuenta</a>
                      </div>

                      <p class="note">
                        Si no fuiste tú quien creó esta cuenta, ignora este mensaje o escríbenos a
                        <a href="mailto:{{supportEmail}}">{{supportEmail}}</a>.
                      </p>
                    </div>
                    <div class="footer">
                      © {{appName}} · Este es un correo automático, no respondas a este mensaje.
                      Si necesitas ayuda, contáctanos en <a href="mailto:{{supportEmail}}">{{supportEmail}}</a>.
                    </div>
                  </div>
                </div>
              </body>
            </html>
            ',
            'version' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
            'variables' => '{"name":true,"appName":false,"activationLink":false,"supportEmail":true}',
            'notification_channel' => 'email',
            'created_by' => UserModel::factory(),
        ];
    }
}
