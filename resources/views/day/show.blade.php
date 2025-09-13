@extends('layouts.layout')

@section('content')
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-3">
            <button onclick="goBack()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-2"></i>رجوع
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <!-- Day Completion Status -->
        @if ($isDayCompleted)
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>تم إنهاء هذا اليوم بنجاح! 🎉</strong>
            </div>
        @endif
        
        <h3 class="text-primary mb-4">{{ $day->title }}</h3>
        <p>{{ $day->desc }}</p>

        <div class="mb-4">
            @if ($day->video && $day->video->video_path)
                <video controls width="100%" height="auto">
                    <source src="{{ Storage::url($day->video->video_path) }}" type="video/mp4">
                    المتصفح لا يدعم تشغيل الفيديو.
                </video>
            @else
                <div class="alert alert-warning">لا يوجد فيديو متاح لهذا اليوم حالياً.</div>
            @endif
        </div>
        
        @if (Auth::check())
            @php
                $userExperience = auth()->user()->experience_years;
                $task = $day->tasks->where('experience_level', $userExperience)->first();
            @endphp

            @if ($task)
                <div class="alert alert-info mt-4">
                    <h5 class="fw-bold">مهمتك لليوم</h5>
                    <p>{{ $task->task }}</p>
                </div>
            @endif
        @endif

        <!-- File Upload Section -->
        @if (!$isDayCompleted)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">ارفع ملفك لهذا اليوم</h5>
                    
                    @if ($userUpload)
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-file-earmark-check me-2"></i>
                            تم رفع الملف: 
                            <strong>{{ $userUpload->original_name ?? 'ملف مرفوع' }}</strong>
                            <a href="{{ asset('storage/' . $userUpload->file_path) }}" 
                               class="btn btn-sm btn-outline-primary ms-2" 
                               download="{{ $userUpload->original_name }}">
                                <i class="bi bi-download me-1"></i>تحميل
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('day.upload', $day->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                {{ $userUpload ? 'استبدال الملف:' : 'ارفع الملف الخاص بك:' }}
                            </label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            {{ $userUpload ? 'استبدال الملف' : 'رفع الملف' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Complete Day Button -->
            @if ($userUpload)
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success">
                            <i class="bi bi-check-circle me-2"></i>
                            هل أنت مستعد لإنهاء هذا اليوم؟
                        </h5>
                        <p class="text-muted mb-3">
                            بعد إنهاء اليوم لن تتمكن من رفع ملفات جديدة أو تعديل المحتوى
                        </p>
                        <form action="{{ route('day.complete', $day->id) }}" method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من إنهاء هذا اليوم؟ لن تتمكن من التراجع عن هذا الإجراء.')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-flag-fill me-2"></i>
                                إنهاء اليوم
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @else
            <!-- Day Completed - Show Upload Info -->
            @if ($userUpload)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">الملف المرفوع</h5>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-check text-success me-2 fs-4"></i>
                            <div>
                                <strong>{{ $userUpload->original_name ?? 'ملف مرفوع' }}</strong>
                                <br>
                                <small class="text-muted">تم الرفع في: {{ $userUpload->created_at->format('Y-m-d H:i') }}</small>
                            </div>
                            <a href="{{ asset('storage/' . $userUpload->file_path) }}" 
                               class="btn btn-outline-primary ms-auto" 
                               download="{{ $userUpload->original_name }}">
                                <i class="bi bi-download me-1"></i>تحميل
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

<script>
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = '/';
    }
}
</script>
@endsection
