<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الاختبار - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
        }
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .question {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .question-header {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .options {
            margin: 10px 0;
        }
        .option {
            margin: 5px 0;
            padding: 5px;
        }
        .correct {
            background-color: #dcfce7;
            color: #166534;
        }
        .incorrect {
            background-color: #fef2f2;
            color: #dc2626;
        }
        .user-answer {
            font-weight: bold;
        }
        .summary {
            background-color: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
        }
        .score {
            font-size: 24px;
            font-weight: bold;
            color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير نتائج الاختبار</h1>
        <p>النظام التعليمي الإلكتروني</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <strong>اسم الطالب:</strong>
            <span>{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <strong>البريد الإلكتروني:</strong>
            <span>{{ $user->email }}</span>
        </div>
        <div class="info-row">
            <strong>اسم الاختبار:</strong>
            <span>{{ $test->title }}</span>
        </div>
        <div class="info-row">
            <strong>تاريخ الاختبار:</strong>
            <span>{{ $report->completed_at->format('Y-m-d H:i') }}</span>
        </div>
    </div>

    <div class="summary">
        <div class="score">
            النتيجة: {{ $report->correct_answers }} من {{ $report->total_questions }}
            ({{ $report->score_percentage }}%)
        </div>
    </div>

    <h2>تفاصيل الإجابات:</h2>

    @foreach($report->answers_data as $index => $answer)
    <div class="question">
        <div class="question-header">
            السؤال {{ $index + 1 }}: {{ $answer['question_text'] }}
        </div>
        
        <div class="options">
            <div class="option {{ $answer['correct_answer'] == 'A' ? 'correct' : '' }}">
                أ) {{ $answer['option_a'] }}
                @if($answer['user_answer'] == 'A')
                    <span class="user-answer">(إجابتك)</span>
                @endif
            </div>
            <div class="option {{ $answer['correct_answer'] == 'B' ? 'correct' : '' }}">
                ب) {{ $answer['option_b'] }}
                @if($answer['user_answer'] == 'B')
                    <span class="user-answer">(إجابتك)</span>
                @endif
            </div>
            <div class="option {{ $answer['correct_answer'] == 'C' ? 'correct' : '' }}">
                ج) {{ $answer['option_c'] }}
                @if($answer['user_answer'] == 'C')
                    <span class="user-answer">(إجابتك)</span>
                @endif
            </div>
            <div class="option {{ $answer['correct_answer'] == 'D' ? 'correct' : '' }}">
                د) {{ $answer['option_d'] }}
                @if($answer['user_answer'] == 'D')
                    <span class="user-answer">(إجابتك)</span>
                @endif
            </div>
        </div>

        <div style="margin-top: 10px;">
            <strong>الإجابة الصحيحة:</strong> {{ $answer['correct_answer'] }}
            <br>
            <strong>إجابتك:</strong> {{ $answer['user_answer'] ?? 'لم تجب' }}
            <br>
            <strong>النتيجة:</strong> 
            <span class="{{ $answer['is_correct'] ? 'correct' : 'incorrect' }}">
                {{ $answer['is_correct'] ? 'صحيح' : 'خطأ' }}
            </span>
        </div>
    </div>
    @endforeach

    <div style="margin-top: 30px; text-align: center; color: #6b7280; font-size: 12px;">
        تم إنشاء هذا التقرير تلقائياً في {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>

