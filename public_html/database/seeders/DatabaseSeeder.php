<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // لو عايز تعمل يوزرز تجريبية بالـ factory:
        // \App\Models\User::factory(10)->create();

        // استدعاء الـ Seeders الأساسية
        $this->call([
            AdminUserSeeder::class,
            LearningDaySeeder::class,
            TestSeeder::class,
        ]);
    }
}
