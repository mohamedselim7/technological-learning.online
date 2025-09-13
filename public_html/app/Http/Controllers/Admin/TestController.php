<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\TestResult;
use App\Models\UserTestReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::all();
        return view('tests.index', compact('tests'));
    }

    public function show($id)
    {
        $test = Test::with('questions')->findOrFail($id);

        $existingResult = null;
        if (auth()->check()) {
            $existingResult = $test->results()->where('user_id', auth()->id())->with('answers')->first();
        }

        return view('tests.show', compact('test', 'existingResult'));
    }
    public function submitTest(Request $request, $testId)
    {
        $test = Test::with('questions')->findOrFail($testId);

        $answers = $request->input('answers');
        $existing = TestResult::where('test_id', $testId)->where('user_id', auth()->id())->first();
        if ($existing) {
            return redirect()->route('tests.show', $testId)->with('success', 'لقد قمت بحل هذا الاختبار من قبل.');
        }

        $result = TestResult::create([
            'user_id' => auth()->id(),
            'test_id' => $test->id,
            'completed' => true,
        ]);

        $correctAnswers = 0;
        $answersData = [];

        foreach ($test->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->correct_answer;

            TestAnswer::create([
                'test_result_id' => $result->id,
                'question_id' => $question->id,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
            ]);

            if ($isCorrect) {
                $correctAnswers++;
            }

            // حفظ بيانات السؤال والإجابة في التقرير
            $answersData[] = [
                'question_id' => $question->id,
                'question_text' => $question->question,
                'option_a' => $question->option_a,
                'option_b' => $question->option_b,
                'option_c' => $question->option_c,
                'option_d' => $question->option_d,
                'correct_answer' => $question->correct_answer,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
            ];
        }

        // إنشاء تقرير إجابات المستخدم
        $report = UserTestReport::create([
            'user_id' => auth()->id(),
            'test_id' => $test->id,
            'answers_data' => $answersData,
            'total_questions' => count($test->questions),
            'correct_answers' => $correctAnswers,
            'completed_at' => now(),
        ]);

        // إنشاء ملف PDF للتقرير
        $this->generatePdfReport($report);

        return redirect()->route('tests.show', $test->id)->with('success', 'تم إرسال الاختبار بنجاح.');
    }

    /**
     * إنشاء ملف PDF لتقرير الاختبار
     */
    private function generatePdfReport($report)
    {
        try {
            $user = $report->user;
            $test = $report->test;
            
            // إنشاء محتوى HTML للتقرير
            $html = view('admin.reports.test-pdf', compact('report', 'user', 'test'))->render();
            
            // إنشاء PDF
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            
            // تحديد مسار الحفظ
            $fileName = 'test_report_' . $user->name . '_' . $test->id . '_' . time() . '.pdf';
            $filePath = 'reports/' . $fileName;
            
            // إنشاء مجلد التقارير إذا لم يكن موجوداً
            if (!Storage::disk('public')->exists('reports')) {
                Storage::disk('public')->makeDirectory('reports');
            }
            
            // حفظ الملف
            Storage::disk('public')->put($filePath, $pdf->output());
            
            // تحديث التقرير بمسار الملف
            $report->update(['pdf_report_path' => $filePath]);
            
        } catch (\Exception $e) {
            // في حالة فشل إنشاء PDF، نسجل الخطأ ولا نوقف العملية
            \Log::error('Failed to generate PDF report: ' . $e->getMessage());
        }
    }
}
