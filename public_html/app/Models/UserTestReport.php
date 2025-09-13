<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTestReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'answers_data',
        'total_questions',
        'correct_answers',
        'completed_at',
        'pdf_report_path',
    ];

    protected $casts = [
        'answers_data' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع الاختبار
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * حساب النسبة المئوية للإجابات الصحيحة
     */
    public function getScorePercentageAttribute()
    {
        if ($this->total_questions == 0) {
            return 0;
        }
        
        return round(($this->correct_answers / $this->total_questions) * 100, 2);
    }
}

