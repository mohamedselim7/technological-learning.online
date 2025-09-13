<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningDayUpload;
use App\Models\LearningDay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserUploadsController extends Controller
{
    /**
     * عرض جميع ملفات المستخدمين المرفوعة
     */
    public function index(Request $request)
    {
        $query = LearningDayUpload::with(['user', 'learningDay']);

        // فلترة حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة حسب اليوم التعليمي
        if ($request->filled('learning_day_id')) {
            $query->where('learning_day_id', $request->learning_day_id);
        }

        // البحث في اسم الملف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('original_name', 'like', "%{$search}%");
        }

        $uploads = $query->latest()->paginate(20);
        
        // الحصول على جميع المستخدمين والأيام التعليمية للفلترة
        $users = User::all();
        $learningDays = LearningDay::all();

        return view('admin.user_uploads.index', compact('uploads', 'users', 'learningDays'));
    }

    /**
     * تحميل ملف مرفوع من قبل مستخدم
     */
    public function download($id)
    {
        $upload = LearningDayUpload::findOrFail($id);
        
        $filePath = storage_path('app/public/' . $upload->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'الملف غير موجود');
        }

        return response()->download($filePath, $upload->original_name);
    }

    /**
     * معاينة ملف (للملفات النصية والصور)
     */
    public function preview($id)
    {
        $upload = LearningDayUpload::with(['user', 'learningDay'])->findOrFail($id);
        
        $filePath = storage_path('app/public/' . $upload->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'الملف غير موجود');
        }

        $fileExtension = pathinfo($upload->original_name, PATHINFO_EXTENSION);
        $fileSize = filesize($filePath);
        $fileUrl = Storage::url($upload->file_path);

        return view('admin.user_uploads.preview', compact('upload', 'fileExtension', 'fileSize', 'fileUrl'));
    }

    /**
     * حذف ملف مرفوع من قبل مستخدم
     */
    public function destroy($id)
    {
        $upload = LearningDayUpload::findOrFail($id);
        
        // حذف الملف من التخزين
        $filePath = storage_path('app/public/' . $upload->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // حذف السجل من قاعدة البيانات
        $upload->delete();

        return back()->with('success', 'تم حذف الملف بنجاح');
    }

    /**
     * تحميل جميع ملفات يوم تعليمي معين كملف مضغوط
     */
    public function downloadDayFiles($dayId)
    {
        $day = LearningDay::findOrFail($dayId);
        $uploads = LearningDayUpload::with('user')->where('learning_day_id', $dayId)->get();

        if ($uploads->isEmpty()) {
            return back()->with('error', 'لا توجد ملفات مرفوعة لهذا اليوم');
        }

        $zipFileName = 'day_' . $dayId . '_uploads_' . now()->format('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // إنشاء مجلد مؤقت إذا لم يكن موجوداً
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($uploads as $upload) {
                $filePath = storage_path('app/public/' . $upload->file_path);
                if (file_exists($filePath)) {
                    $fileName = $upload->user->name . '_' . $upload->original_name;
                    $zip->addFile($filePath, $fileName);
                }
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'حدث خطأ في إنشاء الملف المضغوط');
    }

    /**
     * إحصائيات ملفات المستخدمين
     */
    public function statistics()
    {
        $totalUploads = LearningDayUpload::count();
        $totalUsers = User::whereHas('uploads')->count();
        $totalDays = LearningDay::whereHas('uploads')->count();
        
        // إحصائيات حسب اليوم
        $uploadsByDay = LearningDayUpload::selectRaw('learning_day_id, COUNT(*) as count')
            ->with('learningDay')
            ->groupBy('learning_day_id')
            ->get();

        // إحصائيات حسب المستخدم
        $uploadsByUser = LearningDayUpload::selectRaw('user_id, COUNT(*) as count')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.user_uploads.statistics', compact(
            'totalUploads', 
            'totalUsers', 
            'totalDays', 
            'uploadsByDay', 
            'uploadsByUser'
        ));
    }
}

