<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Http\Request;

class TestQuestionController extends Controller
{
    public function index()
    {
        $questions = TestQuestion::with('test')->latest()->get();
        return view('admin.test_questions.index', compact('questions'));
    }

    public function create()
    {
        $tests = Test::all();
        return view('admin.test_questions.create',['tests' => $tests]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'test_id' => 'required|exists:tests,id',
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'your_answer' => 'nullable|in:a,b,c,d'
        ]);

        TestQuestion::create([
            'test_id' => $request->test_id,
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'your_answer' => $request->your_answer ?? null,
            'question_type' => 'mcq',
        ]);

        return redirect()->route('admin.test_questions.index')->with('success', 'تم إضافة السؤال بنجاح');
    }
    public function destroy($id)
{
    $question = \App\Models\TestQuestion::findOrFail($id);
    $question->delete();

    return redirect()->route('admin.test_questions.index');
}
}
