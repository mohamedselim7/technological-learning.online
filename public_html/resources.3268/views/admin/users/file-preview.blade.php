@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">معاينة الملف</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">المستخدمين</a></li>
                        <li class="breadcrumb-item active">معاينة الملف</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-group">
                @if ($fileExists && $fileUrl)
                    <a href="{{ $fileUrl }}" class="btn btn-success" download="{{ $upload->original_name }}">
                        <i class="bi bi-download me-1"></i>تحميل الملف
                    </a>
                @endif
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>العودة
                </a>
            </div>
        </div>

        <!-- File Information Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">معلومات الملف</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>اسم الملف:</strong></td>
                                <td>{{ $upload->original_name ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td><strong>المستخدم:</strong></td>
                                <td>{{ $upload->user->full_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>اليوم التعليمي:</strong></td>
                                <td>{{ $upload->learningDay->title ?? 'اليوم ' . $upload->learning_day_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ الرفع:</strong></td>
                                <td>{{ $upload->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title">معلومات المستخدم</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>البريد الإلكتروني:</strong></td>
                                <td>{{ $upload->user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>الرقم القومي:</strong></td>
                                <td>{{ $upload->user->national_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>سنوات الخبرة:</strong></td>
                                <td>{{ $upload->user->experience_years }} سنة</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Preview Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-eye me-2"></i>معاينة الملف
                </h5>
            </div>
            <div class="card-body">
                @if (!$fileExists)
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        الملف غير موجود أو تم حذفه من الخادم.
                    </div>
                @elseif ($fileType === 'image')
                    <div class="text-center">
                        <img src="{{ $fileUrl }}" class="img-fluid" style="max-height: 600px;" alt="معاينة الصورة">
                    </div>
                @elseif ($fileType === 'pdf')
                    <div style="height: 600px;">
                        <iframe src="{{ $fileUrl }}" width="100%" height="100%" style="border: none;">
                            <p>متصفحك لا يدعم عرض ملفات PDF. 
                                <a href="{{ $fileUrl }}" target="_blank">اضغط هنا لفتح الملف</a>
                            </p>
                        </iframe>
                    </div>
                @elseif ($fileType === 'video')
                    <div class="text-center">
                        <video controls style="max-width: 100%; max-height: 600px;">
                            <source src="{{ $fileUrl }}" type="video/mp4">
                            متصفحك لا يدعم تشغيل الفيديو.
                        </video>
                    </div>
                @elseif ($fileType === 'audio')
                    <div class="text-center">
                        <audio controls style="width: 100%;">
                            <source src="{{ $fileUrl }}" type="audio/mpeg">
                            متصفحك لا يدعم تشغيل الصوت.
                        </audio>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="bi bi-file-earmark text-primary" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">{{ $upload->original_name ?? 'ملف' }}</h5>
                        <p class="text-muted">لا يمكن معاينة هذا النوع من الملفات في المتصفح.</p>
                        @if ($fileUrl)
                            <a href="{{ $fileUrl }}" class="btn btn-primary" download="{{ $upload->original_name }}">
                                <i class="bi bi-download me-1"></i>تحميل الملف
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('page_css')
<style>
    .table-borderless td {
        border: none;
        padding: 0.25rem 0.5rem;
    }
</style>
@endsection

