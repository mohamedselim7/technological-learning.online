@extends('layouts.admin')

@section('content')
    <div class="d-flex" style="min-height: 100vh;">
        <div class="flex-grow-1 p-4">
            <!-- Back Button -->
            <div class="mb-3">
                <button onclick="goBack()" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-right me-2"></i>رجوع
                </button>
            </div>

            <h4 class="mb-4">إدارة الأيام التعليمية</h4>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row g-4">
                @foreach ($days as $day)
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $day->title }}</h5>
                                <p class="card-text">{{ $day->desc }}</p>
                                <p class="text-muted small">
                                    الحالة الحالية: <strong>{{ $day->status }}</strong>
                                </p>


                                @if ($day->video && $day->video->video_path)
                                    <div class="mb-3">
                                        <video id="video-{{ $day->id }}" autoplay muted playsinline controls
                                            preload="metadata"
                                            style="width: 100%; height: 250px; border-radius: 10px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                                            <source src="{{ asset('storage/' . $day->video->video_path) }}"
                                                type="video/mp4">
                                            متصفحك لا يدعم تشغيل الفيديو.
                                        </video>
                                        <div class="mt-2 text-muted small">
                                            <i class="bi bi-camera-video me-2"></i>
                                            <strong>اسم الفيديو:</strong>
                                            {{ $day->video->original_name ?? 'فيديو اليوم' }}<br>
                                            <small>
                                                تم الرفع في: {{ $day->video->created_at->format('Y-m-d H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-3">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        لا يوجد فيديو مرفوع لهذا اليوم
                                    </div>
                                @endif


                                <form action="{{ route('admin.toggle.day', $day->id) }}" method="POST" class="mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        {{ $day->status === 'متاح' ? 'إغلاق اليوم' : 'فتح اليوم' }}
                                    </button>
                                </form>


                                <form action="{{ route('videos.upload', $day->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label">
                                            {{ $day->video ? 'استبدال الفيديو:' : 'رفع فيديو جديد:' }}
                                        </label>
                                        <input type="file" name="videopath" class="form-control" accept="video/*"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success">
                                        {{ $day->video ? 'استبدال الفيديو' : 'رفع الفيديو' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
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
