<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LibraryManagementController extends Controller
{
    /**
     * عرض جميع ملفات المكتبة في لوحة تحكم الأدمن
     */
    public function index(Request $request)
    {
        $query = LibraryFile::with('uploader');

        // فلترة حسب التصنيف
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // فلترة حسب الحالة (نشط/غير نشط)
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // البحث في العنوان والوصف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('original_name', 'like', "%{$search}%");
            });
        }

        $files = $query->latest()->paginate(15);
        
        // الحصول على جميع التصنيفات المتاحة
        $categories = LibraryFile::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('admin.library.index', compact('files', 'categories'));
    }

    /**
     * عرض صفحة إضافة ملف جديد
     */
    public function create()
    {
        // الحصول على التصنيفات الموجودة
        $categories = LibraryFile::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('admin.library.create', compact('categories'));
    }

    /**
     * حفظ ملف جديد في المكتبة
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'file' => 'required|file|max:20480', // 20MB max
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;

        // إنشاء مجلد المكتبة إذا لم يكن موجوداً
        if (!Storage::disk('public')->exists('library')) {
            Storage::disk('public')->makeDirectory('library');
        }

        // رفع الملف
        $file->storeAs('library', $fileName, 'public');

        // حفظ معلومات الملف في قاعدة البيانات
        LibraryFile::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_type' => strtolower($extension),
            'file_size' => $file->getSize(),
            'category' => $request->category,
            'is_fixed' => $request->has('is_fixed'),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.library.index')->with('success', 'تم رفع الملف بنجاح');
    }

    /**
     * عرض صفحة تعديل ملف
     */
    public function edit($id)
    {
        $file = LibraryFile::findOrFail($id);
        
        // الحصول على التصنيفات الموجودة
        $categories = LibraryFile::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('admin.library.edit', compact('file', 'categories'));
    }

    /**
     * تحديث معلومات ملف
     */
    public function update(Request $request, $id)
    {
        $file = LibraryFile::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $file->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
            'is_fixed' => $request->has('is_fixed'),
        ]);

        return redirect()->route('admin.library.index')->with('success', 'تم تحديث الملف بنجاح');
    }

    /**
     * حذف ملف من المكتبة
     */
    public function destroy($id)
    {
        $file = LibraryFile::findOrFail($id);

        // حذف الملف من التخزين
        $file->deleteFile();

        // حذف السجل من قاعدة البيانات
        $file->delete();

        return redirect()->route('admin.library.index')->with('success', 'تم حذف الملف بنجاح');
    }

    /**
     * تحميل ملف
     */
    public function download($id)
    {
        $file = LibraryFile::findOrFail($id);
        
        if (!$file->fileExists()) {
            return back()->with('error', 'الملف غير موجود');
        }

        $filePath = storage_path('app/public/library/' . $file->file_name);
        
        return response()->download($filePath, $file->original_name);
    }

    /**
     * تغيير حالة الملف (نشط/غير نشط)
     */
    public function toggleStatus($id)
    {
        $file = LibraryFile::findOrFail($id);
        $file->update(['is_active' => !$file->is_active]);

        $status = $file->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        
        return back()->with('success', $status . ' الملف بنجاح');
    }
}


    /**
     * إضافة ملف الموضوعات الثابت
     */
    public function addFixedFile()
    {
        // نسخ ملف الموضوعات إلى مجلد المكتبة
        $sourceFile = '/home/ubuntu/upload/الموضوعات.pdf';
        $fileName = 'الموضوعات.pdf';
        
        if (file_exists($sourceFile)) {
            // إنشاء مجلد المكتبة إذا لم يكن موجوداً
            if (!Storage::disk('public')->exists('library')) {
                Storage::disk('public')->makeDirectory('library');
            }
            
            // نسخ الملف
            Storage::disk('public')->put('library/' . $fileName, file_get_contents($sourceFile));
            
            // التحقق من وجود الملف في قاعدة البيانات
            $existingFile = LibraryFile::where('original_name', $fileName)->first();
            
            if (!$existingFile) {
                // إضافة الملف إلى قاعدة البيانات
                LibraryFile::create([
                    'title' => 'الموضوعات',
                    'description' => 'ملف الموضوعات الأساسي للمنهج',
                    'file_name' => $fileName,
                    'original_name' => $fileName,
                    'file_type' => 'pdf',
                    'file_size' => filesize($sourceFile),
                    'category' => 'منهج',
                    'is_active' => true,
                    'is_fixed' => true,
                    'uploaded_by' => auth()->id(),
                ]);
                
                return back()->with('success', 'تم إضافة ملف الموضوعات بنجاح');
            } else {
                // تحديث الملف الموجود ليصبح ثابت
                $existingFile->update(['is_fixed' => true]);
                return back()->with('success', 'تم تحديث ملف الموضوعات ليصبح ثابت');
            }
        }
        
        return back()->with('error', 'لم يتم العثور على ملف الموضوعات');
    }

