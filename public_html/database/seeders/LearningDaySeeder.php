<?php

namespace Database\Seeders;

use App\Models\LearningDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LearningDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تحقق من وجود البيانات أولاً لتجنب التكرار
        if (LearningDay::count() > 0) {
            return;
        }

        $days = [
            [
                'title' => 'First Day: Introduction and Basics',
                'desc' => 'تعلم الأساسيات والمفاهيم الاولية للموضوع',
                'status' => 'متاح',
                'skills' => ['المسؤولية المهنية', 'العلوم الأساسية', 'الأمان والسلامة'],
                'duration' => '2-3 ساعات',
                'color' => 'primary',
            ],
            [
                'title' => 'Second Day: Practical Application',
                'desc' => 'تطبيق المهارات المكتسبة في مواقف واقعية',
                'status' => 'غير متاح',
                'skills' => ['المسؤولية المهنية', 'المهام التطبيقية'],
                'duration' => '3-4 ساعات',
                'color' => 'success',
            ],
            [
                'title' => 'Third Day: Advanced Skills',
                'desc' => 'تطوير مهارات متقدمة وحل المشكلات',
                'status' => 'غير متاح',
                'skills' => ['حل المشكلات', 'التفكير النقدي'],
                'duration' => '3-4 ساعات',
                'color' => 'warning',
            ],
            [
                'title' => 'Fourth Day: Applied Projects',
                'desc' => 'العمل على مشروع تطبيقي شامل',
                'status' => 'غير متاح',
                'skills' => ['تنفيذ المهام', 'تحليل البيانات'],
                'duration' => '3-4 ساعات',
                'color' => 'secondary',
            ],
            [
                'title' => 'Fifth Day: Final Evaluation and Feedback',
                'desc' => 'تقييم شامل للمهارات المكتسبة',
                'status' => 'غير متاح',
                'skills' => ['التقييم الذاتي', 'الاختبار النهائي'],
                'duration' => '4-5 ساعات',
                'color' => 'secondary',
            ],
        ];

        foreach ($days as $day) {
            LearningDay::create([
                'title' => $day['title'],
                'desc' => $day['desc'],
                'status' => $day['status'],
                'duration' => $day['duration'],
                'color' => $day['color'],
                'skills' => $day['skills'],
            ]);
        }
    }
}
