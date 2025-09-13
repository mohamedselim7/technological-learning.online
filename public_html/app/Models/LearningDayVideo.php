<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LearningDayVideo extends Model
{
    protected $fillable = [
        'learning_day_id',
        'video_path',
        'original_name',
        'is_active',
        'activation_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activation_date' => 'datetime',
    ];

    public function day()
    {
        return $this->belongsTo(LearningDay::class, 'learning_day_id');
    }

    /**
     * الحصول على رابط الفيديو
     */
    public function getVideoUrlAttribute()
    {
        if ($this->video_path) {
            return Storage::url($this->video_path);
        }
        return null;
    }

    /**
     * التحقق من وجود الفيديو
     */
    public function videoExists()
    {
        if (!$this->video_path) {
            return false;
        }
        return Storage::disk('public')->exists($this->video_path);
    }

    /**
     * الحصول على حجم الفيديو بصيغة قابلة للقراءة
     */
    public function getFormattedVideoSizeAttribute()
    {
        if (!$this->videoExists()) {
            return 'غير متاح';
        }
        
        $bytes = Storage::disk('public')->size($this->video_path);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * الحصول على نوع الفيديو
     */
    public function getVideoTypeAttribute()
    {
        if ($this->original_name) {
            return pathinfo($this->original_name, PATHINFO_EXTENSION);
        }
        return 'mp4'; // افتراضي
    }

    /**
     * التحقق من إمكانية عرض الفيديو للمستخدم
     */
    public function isAvailableForUser()
    {
        if (!$this->is_active) {
            return false;
        }
        
        if (!$this->activation_date) {
            return true;
        }
        
        return now()->gte($this->activation_date);
    }
    
    /**
     * scope للحصول على الفيديوهات المتاحة للمستخدمين
     */
    public function scopeAvailableForUsers($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('activation_date')
                          ->orWhere('activation_date', '<=', now());
                    });
    }
}
