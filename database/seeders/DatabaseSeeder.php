<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the AdminSeeder
        $this->call([
            AdminSeeder::class,
            MobilSeeder::class,
        ]);

        // You can also create additional test users using factory
        // User::factory(10)->create();
    }
}
