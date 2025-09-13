<?php

namespace App\Http\Controllers;

use App\Models\LibraryFile;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    /**
     * عرض جميع ملفات المكتبة والأسئلة الشائعة للمستخدمين
     */
    public function index(Request $request)
    {
        // استعلام ملفات المكتبة
        $query = LibraryFile::active()->with('uploader');

        // فلترة حسب التصنيف
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // البحث في العنوان والوصف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // ترتيب الملفات: الثابتة أولاً ثم الباقي
        $files = $query->orderBy('is_fixed', 'desc')->latest()->paginate(12);

        // الحصول على جميع التصنيفات المتاحة
        $categories = LibraryFile::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        // جلب جميع الأسئلة الشائعة
        $faqs = Faq::all();

        // إرسال المتغيرات للـ view
        return view('library.index', compact('files', 'categories', 'faqs'));
    }

    /**
     * تحميل ملف من المكتبة
     */
    public function download($id)
    {
        $file = LibraryFile::active()->findOrFail($id);

        if (!$file->fileExists()) {
            abort(404, 'الملف غير موجود');
        }

        $filePath = storage_path('app/public/library/' . $file->file_name);

        return response()->download($filePath, $file->original_name);
    }
}

    
    /**
     * معاينة ملف من المكتبة
     */
    public function preview($id)
    {
        $file = LibraryFile::active()->findOrFail($id);

        if (!$file->fileExists()) {
            abort(404, 'الملف غير موجود');
        }

        $filePath = storage_path('app/public/library/' . $file->file_name);
        $fileUrl = asset('storage/library/' . $file->file_name);
        
        // تحديد نوع الملف
        $extension = strtolower($file->file_type);
        $fileType = $this->getFileType($extension);
        
        return view('library.preview', compact('file', 'fileUrl', 'fileType'));
    }
    
    /**
     * تحديد نوع الملف للمعاينة
     */
    private function getFileType($extension)
    {
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
            return 'image';
        } elseif (in_array($extension, ['pdf'])) {
            return 'pdf';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            return 'document';
        } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv'])) {
            return 'video';
        } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
            return 'audio';
        } else {
            return 'other';
        }
    }

