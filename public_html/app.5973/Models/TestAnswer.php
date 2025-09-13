<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
        use HasFactory;
    protected $guarded = [];

    public function result()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    public function question()
    {
        return $this->belongsTo(TestQuestion::class, 'question_id');
    }
}
