<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'key' => 'welcome-user',
                'locale' => 'en',
                'subject' => 'Welcome to {{appName}}, {{name}}!',
                'body_html' => <<<'HTML'
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Welcome to {{appName}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
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
          <h1>Welcome to {{appName}}, {{name}}!</h1>
        </div>
        <div class="body">
          <h2>Your community, organized at a glance</h2>
          <p>
            Thanks for signing up for <strong>{{appName}}</strong>. From now on, you will be able to:
          </p>
          <ul style="margin:12px 0 0 18px; padding:0; font-size:15px; line-height:1.6;">
            <li>Receive real-time announcements and updates from your condominium.</li>
            <li>Book common areas and manage your service requests.</li>
            <li>Check your dues, account statements, and important documents.</li>
          </ul>

          <div class="cta">
            <a class="btn" href="{{link}}">Activate my account</a>
          </div>

          <p class="note">
            If you didn’t create this account, please ignore this message or contact us at
            <a href="mailto:{{supportEmail}}">{{supportEmail}}</a>.
          </p>
        </div>
        <div class="footer">
          © {{appName}} · This is an automated message; please do not reply.
          If you need assistance, contact us at <a href="mailto:{{supportEmail}}">{{supportEmail}}</a>.
        </div>
      </div>
    </div>
  </body>
</html>
HTML,
                'version' => '1',
                'is_active' => true,
                'variables' => [
                    'name' => true,
                    'appName' => false,
                    'link' => false,
                    'supportEmail' => false,
                ],
                'notification_channel' => 'sms',
                'created_by' => null,
            ],
            [
                'key' => 'welcome-user',
                'locale' => 'es',
                'subject' => '¡Bienvenido a {{appName}}, {{name}}!',
                'body_html' => <<<'HTML'
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Bienvenido a {{appName}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
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
          <h2>Tu comunidad, organizada de un vistazo</h2>
          <p>
            Gracias por registrarte en <strong>{{appName}}</strong>. A partir de ahora podrás:
          </p>
          <ul style="margin:12px 0 0 18px; padding:0; font-size:15px; line-height:1.6;">
            <li>Recibir avisos y comunicados en tiempo real de tu condominio.</li>
            <li>Reservar áreas comunes y gestionar tus solicitudes de servicio.</li>
            <li>Consultar tus cuotas, estados de cuenta y documentos importantes.</li>
          </ul>

          <div class="cta">
            <a class="btn" href="{{link}}">Activar mi cuenta</a>
          </div>

          <p class="note">
            Si tú no creaste esta cuenta, ignora este mensaje o contáctanos en
            <a href="mailto:{{supportEmail}}">{{supportEmail}}</a>.
          </p>
        </div>
        <div class="footer">
          © {{appName}} · Este es un mensaje automático; por favor no respondas.
          Si necesitas ayuda, contáctanos en <a href="mailto:{{supportEmail}}">{{supportEmail}}</a>.
        </div>
      </div>
    </div>
  </body>
</html>
HTML,
                'version' => '1',
                'is_active' => true,
                'variables' => [
                    'name' => true,
                    'appName' => false,
                    'link' => false,
                    'supportEmail' => false,
                ],
                'notification_channel' => 'sms',
                'created_by' => null,
            ],
            // =========================
            // FORGET PASSWORD (ES)
            // =========================
            [
                'key' => 'forget-password',
                'locale' => 'es',
                'subject' => 'Restablece tu contraseña en {{appName}}, {{name}}',
                'body_html' => <<<'HTML'
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Restablecer contraseña - {{appName}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      .wrapper { background:#f6f7fb; padding:24px; }
      .card { max-width:620px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
      .header { background:#5b3cc4; color:#fff; padding:22px 28px; font-family:Arial,Helvetica,sans-serif; }
      .body { padding:24px 28px; color:#333; font-family:Arial,Helvetica,sans-serif; }
      .cta { text-align:center; margin:24px 0; }
      .btn { display:inline-block; padding:12px 18px; border-radius:8px; text-decoration:none; background:#5b3cc4; color:#fff !important; font-weight:bold; }
      .footer { padding:18px 28px; background:#fafafa; color:#666; font-size:12px; }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <div class="card">
        <div class="header">
          <h1>Restablece tu contraseña en {{appName}}</h1>
        </div>
        <div class="body">
          <p>Hola, {{name}}</p>
          <p>Recibimos una solicitud para restablecer tu contraseña.</p>

          <div class="cta">
            <a class="btn" href="{{link}}">Restablecer contraseña</a>
          </div>

          <p>Si no solicitaste esto, puedes ignorar este correo.</p>
        </div>
        <div class="footer">
          © {{appName}} · Este es un mensaje automático.
        </div>
      </div>
    </div>
  </body>
</html>
HTML,
                'version' => '1',
                'is_active' => true,
                'variables' => [
                    'name' => true,
                    'appName' => false,
                    'link' => false,
                    'supportEmail' => false,
                ],
                'notification_channel' => 'email',
                'created_by' => null,
            ],
            // =========================
            // FORGET PASSWORD (EN)
            // =========================
            [
                'key' => 'forget-password',
                'locale' => 'en',
                'subject' => 'Reset your password for {{appName}}, {{name}}',
                'body_html' => <<<'HTML'
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Reset password - {{appName}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      .wrapper { background:#f6f7fb; padding:24px; }
      .card { max-width:620px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
      .header { background:#5b3cc4; color:#fff; padding:22px 28px; font-family:Arial,Helvetica,sans-serif; }
      .body { padding:24px 28px; color:#333; font-family:Arial,Helvetica,sans-serif; }
      .cta { text-align:center; margin:24px 0; }
      .btn { display:inline-block; padding:12px 18px; border-radius:8px; text-decoration:none; background:#5b3cc4; color:#fff !important; font-weight:bold; }
      .footer { padding:18px 28px; background:#fafafa; color:#666; font-size:12px; }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <div class="card">
        <div class="header">
          <h1>Reset your password for {{appName}}</h1>
        </div>
        <div class="body">
          <p>Hello, {{name}}</p>
          <p>We received a request to reset your password.</p>

          <div class="cta">
            <a class="btn" href="{{link}}">Reset my password</a>
          </div>

          <p>If you didn’t request this, ignore this email.</p>
        </div>
        <div class="footer">
          © {{appName}} · This is an automated message.
        </div>
      </div>
    </div>
  </body>
</html>
HTML,
                'version' => '1',
                'is_active' => true,
                'variables' => [
                    'name' => true,
                    'appName' => false,
                    'link' => false,
                    'supportEmail' => false,
                ],
                'notification_channel' => 'email',
                'created_by' => null,
            ],
        ];

        foreach ($items as $template) {
            $template['variables'] = json_encode(
                $template['variables'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
            TemplateNotification::create($template);
        }
    }
}
