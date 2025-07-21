<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::updateOrCreate(['email'     => 'admin@app.com'],[
            'name'      => 'Super Admin',
            'email'     => 'admin@app.com',
            'password'  => bcrypt('password'),
        ]);

        //$admin->assignRole('Super Admin');
        $this->command->info('Admin Account Has Been Created Successfully');
    }
}
