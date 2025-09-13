<?php

namespace App\Http\Controllers;

use App\Models\LearningDay;
use App\Models\LearningDayVideo;
use Illuminate\Http\Request;
use App\Models\Video;
class AdminController extends Controller
{
    public function index()
    {
        $days = LearningDay::with('video')->get();
        return view('admin.dashboard', ['days' => $days]);
    }

    public function uploadVideo(Request $request, $dayId)
{
    $request->validate([
        'videopath' => 'required|mimes:mp4,mov,avi|max:200000', 
    ]);

    $day = LearningDay::findOrFail($dayId);
    if ($request->hasFile('videopath')) {
        $file = $request->file('videopath');
        $videoName = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('videos', $videoName, 'public');
        if ($day->video) {
            $day->video->update([
                'video_path'     => $path,
                'original_name'  => $file->getClientOriginalName(),
                'is_active'      => true,
                'activation_date'=> now(),
            ]);
        } else {
            $day->video()->create([
                'video_path'     => $path,
                'original_name'  => $file->getClientOriginalName(),
                'is_active'      => true,
                'activation_date'=> now(),
            ]);
        }
    }
    return redirect();
}

    public function toggleDayStatus($day)
    {
        $day = LearningDay::findOrFail($day);
        $day->status = $day->status === 'متاح' ? 'غير متاح' : 'متاح';
        $day->save();

        return back()->with('success', 'تم تحديث حالة اليوم.');
    }
}
