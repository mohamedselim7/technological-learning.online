<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningDay extends Model
{
    protected $guarded = [];
    protected $casts = [
        'skills' => 'array',
    ];
    
    public function video()
    {
        return $this->hasOne(LearningDayVideo::class);
    }
    
    public function tasks()
    {
        return $this->hasMany(DayTask::class);
    }
    
    public function uploads()
    {
        return $this->hasMany(LearningDayUpload::class);
    }
}
