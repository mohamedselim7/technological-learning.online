<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // حذف الاختبار الثالث (الاختبار النهائي) وجميع البيانات المرتبطة به
        $thirdTest = DB::table('tests')->where('title', 'الاختبار النهائي')->first();
        
        if ($thirdTest) {
            // حذف إجابات الاختبار
            DB::table('test_answers')
                ->whereIn('test_result_id', function($query) use ($thirdTest) {
                    $query->select('id')
                          ->from('test_results')
                          ->where('test_id', $thirdTest->id);
                })
                ->delete();
            
            // حذف نتائج الاختبار
            DB::table('test_results')->where('test_id', $thirdTest->id)->delete();
            
            // حذف أسئلة الاختبار
            DB::table('test_questions')->where('test_id', $thirdTest->id)->delete();
            
            // حذف الاختبار نفسه
            DB::table('tests')->where('id', $thirdTest->id)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة إنشاء الاختبار الثالث
        DB::table('tests')->insert([
            'title' => 'الاختبار النهائي',
            'description' => 'اختبار شامل لجميع المهارات والمعارف المكتسبة.',
            'duration_minutes' => 60,
            'question_count' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};

