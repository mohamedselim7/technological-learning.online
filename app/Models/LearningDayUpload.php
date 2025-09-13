<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningDayUpload extends Model
{
    use HasFactory;

    // الحقول المسموح تعبئتها
    protected $fillable = [
        'user_id',
        'learning_day_id',
        'file_path',
        'original_name',
        'status',
    ];

    /**
     * علاقة مع المستخدم اللي رفع الملف
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة مع اليوم التعليمي اللي يخصه الرفع
     */
    public function learningDay()
    {
        return $this->belongsTo(LearningDay::class, 'learning_day_id');
    }

    /**
     * الحصول على حجم الملف بصيغة قابلة للقراءة
     */
    public function getFormattedFileSizeAttribute()
    {
        $filePath = storage_path('app/public/' . $this->file_path);
        
        if (!file_exists($filePath)) {
            return 'غير متاح';
        }
        
        $bytes = filesize($filePath);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * الحصول على نوع الملف
     */
    public function getFileTypeAttribute()
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    /**
     * الحصول على أيقونة الملف حسب النوع
     */
    public function getFileIconAttribute()
    {
        $extension = $this->file_type;
        
        $icons = [
            'pdf' => 'fas fa-file-pdf text-red-500',
            'doc' => 'fas fa-file-word text-blue-500',
            'docx' => 'fas fa-file-word text-blue-500',
            'ppt' => 'fas fa-file-powerpoint text-orange-500',
            'pptx' => 'fas fa-file-powerpoint text-orange-500',
            'xls' => 'fas fa-file-excel text-green-500',
            'xlsx' => 'fas fa-file-excel text-green-500',
            'txt' => 'fas fa-file-alt text-gray-500',
            'zip' => 'fas fa-file-archive text-purple-500',
            'rar' => 'fas fa-file-archive text-purple-500',
            'jpg' => 'fas fa-file-image text-green-500',
            'jpeg' => 'fas fa-file-image text-green-500',
            'png' => 'fas fa-file-image text-green-500',
            'gif' => 'fas fa-file-image text-green-500',
        ];

        return $icons[strtolower($extension)] ?? 'fas fa-file text-gray-500';
    }

    /**
     * التحقق من وجود الملف
     */
    public function fileExists()
    {
        $filePath = storage_path('app/public/' . $this->file_path);
        return file_exists($filePath);
    }
}
