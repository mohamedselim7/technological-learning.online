<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LearningDayUpload;
use App\Models\UserTestReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['uploads.learningDay', 'testResults.test'])->get();
        
        // جلب تقارير الاختبارات لكل مستخدم
        foreach ($users as $user) {
            $user->testReports = UserTestReport::where('user_id', $user->id)
                ->with('test')
                ->get();
        }
        
        return view('admin.users.index', compact('users'));
    }
    
    public function previewFile($uploadId)
    {
        $upload = LearningDayUpload::with(['user', 'learningDay'])->findOrFail($uploadId);
        
        $filePath = storage_path('app/public/' . $upload->file_path);
        $fileExists = file_exists($filePath);
        $fileUrl = $fileExists ? asset('storage/' . $upload->file_path) : null;
        
        // Determine file type
        $extension = pathinfo($upload->file_path, PATHINFO_EXTENSION);
        $fileType = $this->getFileType($extension);
        
        return view('admin.users.file-preview', compact('upload', 'fileUrl', 'fileExists', 'fileType'));
    }
    
    private function getFileType($extension)
    {
        $extension = strtolower($extension);
        
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

    /**
     * تحميل ملف مرفوع من المستخدم
     */
    public function downloadFile($uploadId)
    {
        $upload = LearningDayUpload::findOrFail($uploadId);
        
        $filePath = storage_path('app/public/' . $upload->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'الملف غير موجود');
        }
        
        return response()->download($filePath, $upload->original_name);
    }
    
    /**
     * تحميل تقرير PDF للاختبار
     */
    public function downloadTestReport($reportId)
    {
        $report = UserTestReport::findOrFail($reportId);
        
        if (!$report->pdf_report_path) {
            return back()->with('error', 'تقرير PDF غير متوفر');
        }
        
        $filePath = storage_path('app/public/' . $report->pdf_report_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'ملف التقرير غير موجود');
        }
        
        return response()->download($filePath, $report->user->name . '_test_report.pdf');
    }
    
    /**
     * تصدير جميع بيانات المستخدمين
     */
    public function exportData()
    {
        $users = User::with(['uploads.learningDay', 'testResults.test'])->get();
        
        $csvData = [];
        $csvData[] = [
            'اسم المستخدم',
            'البريد الإلكتروني',
            'تاريخ التسجيل',
            'عدد الملفات المرفوعة',
            'عدد الاختبارات المكتملة',
            'متوسط الدرجات'
        ];
        
        foreach ($users as $user) {
            $testReports = UserTestReport::where('user_id', $user->id)->get();
            $avgScore = $testReports->count() > 0 ? $testReports->avg('score_percentage') : 0;
            
            $csvData[] = [
                $user->name,
                $user->email,
                $user->created_at->format('Y-m-d'),
                $user->uploads->count(),
                $testReports->count(),
                round($avgScore, 2) . '%'
            ];
        }
        
        $filename = 'users_data_' . date('Y-m-d_H-i-s') . '.csv';
        
        $handle = fopen('php://temp', 'w+');
        
        // إضافة BOM للدعم العربي
        fwrite($handle, "\xEF\xBB\xBF");
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
