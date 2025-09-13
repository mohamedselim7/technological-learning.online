<?php

namespace App\Http\Controllers;

use App\Models\LearningDay;
use App\Models\LearningDayUpload;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $days = LearningDay::all();
        $tests = Test::all();
        $user = Auth::user();
        $completedDays = $user->completed_days ? json_decode($user->completed_days, true) : [];
        
        // Check if user completed all days
        $totalDays = $days->count();
        $allDaysCompleted = count($completedDays) >= $totalDays;
        
        // Check if user has taken all tests
        $userTestResults = $user ? $user->testResults->pluck('test_id')->toArray() : [];
        $allTestsTaken = $tests->every(function($test) use ($userTestResults) {
            return in_array($test->id, $userTestResults);
        });
        
        return view('index', compact('days', 'tests', 'user', 'completedDays', 'allDaysCompleted', 'allTestsTaken'));
    }
    
    public function show($id)
    {
        $day = LearningDay::with('video')->findOrFail($id);
        $user = Auth::user();
        $completedDays = $user->completed_days ? json_decode($user->completed_days, true) : [];
        $isDayCompleted = in_array($id, $completedDays);
        
        // Get user's upload for this day
        $userUpload = LearningDayUpload::where('learning_day_id', $id)
                                      ->where('user_id', Auth::id())
                                      ->first();
        
        return view('day.show', compact('day', 'isDayCompleted', 'userUpload'));
    }

    public function storeUserUpload(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        // Delete previous upload if exists
        $existingUpload = LearningDayUpload::where('learning_day_id', $id)
                                          ->where('user_id', Auth::id())
                                          ->first();
        
        if ($existingUpload) {
            // Delete old file
            if (file_exists(storage_path('app/public/' . $existingUpload->file_path))) {
                unlink(storage_path('app/public/' . $existingUpload->file_path));
            }
            $existingUpload->delete();
        }

        $originalName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->store("uploads/day_$id", 'public');

        LearningDayUpload::create([
            'learning_day_id' => $id,
            'user_id' => Auth::id(),
            'file_path' => $path,
            'original_name' => $originalName,
        ]);

        return redirect()->route('day.show', $id)->with('success', 'تم رفع الملف بنجاح ✅');
    }
    
    public function completeDay(Request $request, $id)
    {
        $user = Auth::user();
        $completedDays = $user->completed_days ? json_decode($user->completed_days, true) : [];
        
        if (!in_array($id, $completedDays)) {
            $completedDays[] = (int)$id;
            $user->completed_days = json_encode($completedDays);
            $user->last_day_completed_at = now();
            $user->save();
        }
        
        return redirect()->route('day.show', $id)->with('success', 'تم إنهاء اليوم بنجاح! 🎉');
    }
}
