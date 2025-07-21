<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate(
            ['email' => 'user@app.com'], // Condition pour vérifier si l'utilisateur existe déjà
            [
                'name' => 'Regular User',
                'email' => 'user@app.com',
                'password' => bcrypt('password123'), // Mot de passe hashé
            ]
        );

        // Si tu utilises des rôles, tu peux assigner un rôle à l'utilisateur :
        $user->assignRole('User'); // Assigne un rôle si Spatie Role est utilisé

        $this->command->info('User Account Has Been Created Successfully');
    }
}
