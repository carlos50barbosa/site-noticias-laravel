<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Configurações do site (linha única, id = 1).
        SiteSetting::firstOrCreate(
            ['id' => 1],
            ['site_name' => config('app.name', 'Site de Notícias')],
        );

        // Usuário ADMIN inicial (credenciais via .env — troque em produção).
        $admin = User::firstOrCreate(
            ['email' => env('SEED_ADMIN_EMAIL', 'admin@noticias.local')],
            [
                'name' => 'Administrador',
                'password' => env('SEED_ADMIN_PASSWORD', 'admin12345'), // cast 'hashed' → bcrypt
                'role' => Role::ADMIN,
            ],
        );

        $this->call([
            CategorySeeder::class,
            PostSeeder::class,
        ]);

        $this->command->info("Admin: {$admin->email}");
    }
}
