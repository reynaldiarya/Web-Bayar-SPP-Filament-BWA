<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(ShieldSeeder::class);
        $superAdmin = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('admin1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $superAdmin->assignRole('Super Admin');
    }
}
