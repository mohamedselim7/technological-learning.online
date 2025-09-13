<?php

namespace App\Http\Controllers;

use App\Models\LibraryFile;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    /**
     * عرض جميع ملفات المكتبة والأسئلة الشائعة للمستخدمين
     */
    public function index(Request $request)
    {
        $query = LibraryFile::active()->with('uploader');

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $files = $query->orderBy('is_fixed', 'desc')->latest()->paginate(12);

        $categories = LibraryFile::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $faqs = Faq::all();

        return view('library.index', compact('files', 'categories', 'faqs'));
    }

    /**
     * رفع ملف جديد إلى المكتبة
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mov,wmv,mp3,wav,ogg|max:20480',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_fixed' => 'boolean',
        ]);

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $fileExtension = $uploadedFile->getClientOriginalExtension();
        $fileSize = $uploadedFile->getSize();

        $fileName = Str::uuid() . '.' . $fileExtension;

        $uploadedFile->storeAs('library', $fileName, 'public');

        LibraryFile::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_type' => strtolower($fileExtension),
            'file_size' => $fileSize,
            'category' => $request->category,
            'is_active' => $request->has('is_active') ? true : false,
            'is_fixed' => $request->has('is_fixed') ? true : false,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('library.index')->with('success', 'تم رفع الملف بنجاح!');
    }

    /**
     * تحميل ملف
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

    /**
     * معاينة ملف
     */
    public function preview($id)
    {
        $file = LibraryFile::active()->findOrFail($id);

        if (!$file->fileExists()) {
            abort(404, 'الملف غير موجود');
        }

        $fileUrl = asset('storage/library/' . $file->file_name);
        $fileType = $this->getFileType($file->file_type);

        return view('library.preview', compact('file', 'fileUrl', 'fileType'));
    }

    /**
     * تحديد نوع الملف
     */
    private function getFileType($extension)
    {
        $extension = strtolower($extension);

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
            return 'image';
        } elseif ($extension === 'pdf') {
            return 'pdf';
        } elseif (in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            return 'document';
        } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv'])) {
            return 'video';
        } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
            return 'audio';
        } else {
            return 'other';
        }
    }
}
