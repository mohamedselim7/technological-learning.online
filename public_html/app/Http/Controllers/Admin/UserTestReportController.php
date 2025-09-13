<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserTestReport;
use App\Models\User;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserTestReportController extends Controller
{
    /**
     * عرض جميع تقارير إجابات المستخدمين
     */
    public function index(Request $request)
    {
        $query = UserTestReport::with(['user', 'test']);

        // فلترة حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة حسب الاختبار
        if ($request->filled('test_id')) {
            $query->where('test_id', $request->test_id);
        }

        $reports = $query->latest()->paginate(20);
        $users = User::all();
        $tests = Test::all();

        return view('admin.user_test_reports.index', compact('reports', 'users', 'tests'));
    }

    /**
     * عرض تفاصيل تقرير إجابات مستخدم معين
     */
    public function show($id)
    {
        $report = UserTestReport::with(['user', 'test'])->findOrFail($id);
        
        return view('admin.user_test_reports.show', compact('report'));
    }

    /**
     * تحميل تقرير إجابات المستخدم كملف JSON
     */
    public function download($id)
    {
        $report = UserTestReport::with(['user', 'test'])->findOrFail($id);
        
        $data = [
            'user_name' => $report->user->name,
            'user_email' => $report->user->email,
            'test_title' => $report->test->title,
            'completed_at' => $report->completed_at->format('Y-m-d H:i:s'),
            'total_questions' => $report->total_questions,
            'correct_answers' => $report->correct_answers,
            'score_percentage' => $report->score_percentage,
            'answers' => $report->answers_data,
        ];

        $fileName = 'test_report_' . $report->user->name . '_' . $report->test->title . '_' . $report->completed_at->format('Y-m-d') . '.json';
        
        return Response::json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * تحميل جميع تقارير اختبار معين كملف مضغوط
     */
    public function downloadTestReports($testId)
    {
        $test = Test::findOrFail($testId);
        $reports = UserTestReport::with('user')->where('test_id', $testId)->get();

        if ($reports->isEmpty()) {
            return back()->with('error', 'لا توجد تقارير لهذا الاختبار.');
        }

        $zipFileName = 'test_reports_' . $test->title . '_' . now()->format('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // إنشاء مجلد مؤقت إذا لم يكن موجوداً
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($reports as $report) {
                $data = [
                    'user_name' => $report->user->name,
                    'user_email' => $report->user->email,
                    'test_title' => $test->title,
                    'completed_at' => $report->completed_at->format('Y-m-d H:i:s'),
                    'total_questions' => $report->total_questions,
                    'correct_answers' => $report->correct_answers,
                    'score_percentage' => $report->score_percentage,
                    'answers' => $report->answers_data,
                ];

                $fileName = 'report_' . $report->user->name . '_' . $report->completed_at->format('Y-m-d') . '.json';
                $zip->addFromString($fileName, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'حدث خطأ في إنشاء الملف المضغوط.');
    }
}

