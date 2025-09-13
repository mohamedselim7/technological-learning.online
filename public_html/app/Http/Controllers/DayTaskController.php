<?php

namespace App\Http\Controllers;

use App\Models\DayTask;
use App\Models\LearningDay;
use Illuminate\Http\Request;

class DayTaskController extends Controller
{
    public function index()
    {
        $days = LearningDay::with('tasks')->get();
        return view('admin.tasks.index', compact('days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'learning_day_id' => 'required|exists:learning_days,id',
            'experience_level' => 'required|in:أقل من 15 سنة,أكثر من 15 سنة',
            'task' => 'required|string',
        ]);

        DayTask::updateOrCreate(
            [
                'learning_day_id' => $request->learning_day_id,
                'experience_level' => $request->experience_level,
            ],
            ['task' => $request->task]
        );

        return back()->with('success', 'تم حفظ المهمة بنجاح');
    }
}
