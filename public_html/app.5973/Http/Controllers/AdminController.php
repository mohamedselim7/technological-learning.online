<?php

namespace App\Http\Controllers;

use App\Models\LearningDay;
use App\Models\LearningDayVideo;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $days = LearningDay::with('video')->get();
        return view('admin.dashboard', compact('days'));
    }

    public function uploadVideo(Request $request, $day)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi|max:20480', // max 20MB
        ]);

        $originalName = $request->file('video')->getClientOriginalName();
        $path = $request->file('video')->store("videos/day_$day", 'public');

        LearningDayVideo::updateOrCreate(
            ['learning_day_id' => $day],
            [
                'video_path' => $path,
                'original_name' => $originalName
            ]
        );

        return back()->with('success', 'تم رفع الفيديو بنجاح ✅');
    }

    public function toggleDayStatus($day)
    {
        $day = LearningDay::findOrFail($day);
        $day->status = $day->status === 'متاح' ? 'غير متاح' : 'متاح';
        $day->save();

        return back()->with('success', 'تم تحديث حالة اليوم.');
    }
}
