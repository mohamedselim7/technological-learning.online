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
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $day->title }}</h5>
                                <p class="card-text">{{ $day->desc }}</p>
                                <p class="text-muted small">الحالة الحالية: <strong>{{ $day->status }}</strong></p>

                                <!-- Video Status -->
                                @if ($day->video && $day->video->video_path)
                                    <div class="alert alert-success mb-3">
                                        <i class="bi bi-camera-video me-2"></i>
                                        <strong>فيديو مرفوع:</strong> {{ $day->video->original_name ?? 'فيديو اليوم' }}
                                        <br>
                                        <small class="text-muted">
                                            تم الرفع في: {{ $day->video->created_at->format('Y-m-d H:i') }}
                                        </small>
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

                                <form action="{{ route('admin.upload.video', $day->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label">
                                            {{ $day->video ? 'استبدال الفيديو:' : 'رفع فيديو جديد:' }}
                                        </label>
                                        <input type="file" name="video" class="form-control" accept="video/*" required>
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
