<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $responses = json_decode(Storage::get('chatbot_responses.json'), true);

        // تنظيف الرسالة من علامات الترقيم والمسافات الزائدة
        $cleanMessage = trim(preg_replace('/[^\p{L}\p{N}\s]/u', '', $message));
        
        // البحث عن إجابة مطابقة تماماً
        foreach ($responses as $keyword => $response) {
            if (strcasecmp($cleanMessage, $keyword) === 0) {
                return response()->json(['reply' => $response]);
            }
        }

        // البحث عن إجابة تحتوي على كلمات مفتاحية
        $bestMatch = null;
        $highestScore = 0;
        
        foreach ($responses as $keyword => $response) {
            $score = $this->calculateSimilarity($cleanMessage, $keyword);
            if ($score > $highestScore && $score > 0.3) { // عتبة التشابه 30%
                $highestScore = $score;
                $bestMatch = $response;
            }
        }
        
        if ($bestMatch) {
            return response()->json(['reply' => $bestMatch]);
        }

        // إذا لم يتم العثور على إجابة، اقتراح أسئلة شائعة
        $suggestions = $this->getSuggestions();
        $suggestionText = "عذرًا، لم أفهم سؤالك. يمكنك تجربة أحد هذه الأسئلة الشائعة:\n\n";
        
        // عرض أول 5 اقتراحات
        $topSuggestions = array_slice($suggestions, 0, 5);
        foreach ($topSuggestions as $index => $suggestion) {
            $suggestionText .= ($index + 1) . ". " . $suggestion . "\n";
        }

        return response()->json(['reply' => $suggestionText]);
    }

    /**
     * حساب درجة التشابه بين نصين
     */
    private function calculateSimilarity($text1, $text2)
    {
        // تحويل النصوص إلى كلمات
        $words1 = explode(' ', mb_strtolower($text1));
        $words2 = explode(' ', mb_strtolower($text2));
        
        // إزالة الكلمات الشائعة (stop words)
        $stopWords = ['ما', 'هو', 'هي', 'في', 'من', 'إلى', 'على', 'عن', 'مع', 'أن', 'كان', 'كانت', 'يكون', 'تكون'];
        $words1 = array_diff($words1, $stopWords);
        $words2 = array_diff($words2, $stopWords);
        
        if (empty($words1) || empty($words2)) {
            return 0;
        }
        
        // حساب الكلمات المشتركة
        $commonWords = array_intersect($words1, $words2);
        $totalWords = array_unique(array_merge($words1, $words2));
        
        return count($commonWords) / count($totalWords);
    }

    public function getSuggestions()
    {
        try {
            $responses = json_decode(Storage::get('chatbot_responses.json'), true);
            
            // استخراج الأسئلة من ملف الموضوعات
            $questions = array_keys($responses);
            
            // ترتيب الأسئلة حسب الطول (الأسئلة الأقصر أولاً)
            usort($questions, function($a, $b) {
                return strlen($a) - strlen($b);
            });
            
            // إرجاع أول 10 أسئلة
            return array_slice($questions, 0, 10);
            
        } catch (\Exception $e) {
            return [
                "ما هي الثورة الصناعية الرابعة؟",
                "ما هو الذكاء الاصطناعي؟",
                "ما هو التعليم الأخضر؟",
                "ما هي التكنولوجيا الخضراء؟",
                "ما هي التنمية المستدامة؟"
            ];
        }
    }

    public function getQuestions()
    {
        try {
            $responses = json_decode(Storage::get('chatbot_responses.json'), true);
            $questions = [];
            
            foreach ($responses as $question => $answer) {
                $questions[] = [
                    'question' => $question,
                    'answer' => $answer
                ];
            }
            
            return response()->json($questions);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}
