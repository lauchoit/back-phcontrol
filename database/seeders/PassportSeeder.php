<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\ClientRepository;

class PassportSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Asegura APP_KEY (opcional pero útil en entornos limpios)
        if (empty(config('app.key'))) {
            Artisan::call('key:generate', ['--force' => true]);
        }
        $clientRepo = new ClientRepository;
        $clientRepo->createPersonalAccessGrantClient(
            'user-personal-access-client',
        );
    }
}
