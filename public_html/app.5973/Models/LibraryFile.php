<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LibraryFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_name',
        'original_name',
        'file_type',
        'file_size',
        'category',
        'is_active',
        'is_fixed',
        'uploaded_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_fixed' => 'boolean',
    ];

    /**
     * العلاقة مع المستخدم الذي رفع الملف
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * الحصول على رابط تحميل الملف
     */
    public function getDownloadUrlAttribute()
    {
        return route('library.download', $this->id);
    }

    /**
     * الحصول على حجم الملف بصيغة قابلة للقراءة
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * الحصول على أيقونة الملف حسب النوع
     */
    public function getFileIconAttribute()
    {
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
        ];

        return $icons[$this->file_type] ?? 'fas fa-file text-gray-500';
    }

    /**
     * التحقق من وجود الملف في التخزين
     */
    public function fileExists()
    {
        return Storage::disk('public')->exists('library/' . $this->file_name);
    }

    /**
     * حذف الملف من التخزين
     */
    public function deleteFile()
    {
        if ($this->fileExists()) {
            Storage::disk('public')->delete('library/' . $this->file_name);
        }
    }

    /**
     * Scope للملفات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للفلترة حسب التصنيف
     */
    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }
}

