<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تحقق من وجود البيانات أولاً لتجنب التكرار
        if (Test::count() > 0) {
            return;
        }

        Test::insert([
            [
                'title' => 'اختبار الأساسيات',
                'description' => 'اختبار يقيس معرفتك بالمفاهيم الأساسية.',
                'duration_minutes' => 15,
                'question_count' => 8,
            ],
            [
                'title' => 'اختبار التطبيق العملي',
                'description' => 'يقيس قدرتك على التطبيق العملي في مواقف عملية.',
                'duration_minutes' => 30,
                'question_count' => 15,
            ],
        ]);
    }
}
