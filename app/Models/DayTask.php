<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayTask extends Model
{
    protected $fillable = ['learning_day_id', 'experience_level', 'task'];

    public function day()
    {
        return $this->belongsTo(LearningDay::class, 'learning_day_id');
    }
}
