@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">قائمة المستخدمين</h3>
            <span class="badge bg-primary fs-6">{{ count($users) }} مستخدم</span>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>المستخدم</th>
                                <th>معلومات الاتصال</th>
                                <th>الخبرة</th>
                                <th>الملفات المرفوعة</th>
                                <th>نتائج الاختبارات</th>
                                <th>التقدم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->full_name }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $user->national_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                انضم في {{ $user->created_at->format('Y-m-d') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $user->experience_years }} سنة</span>
                                    </td>
                                    <td>
                                        @if ($user->uploads->count() > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @foreach ($user->uploads as $upload)
                                                    <div class="d-flex align-items-center justify-content-between border rounded p-2">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-file-earmark text-primary me-2"></i>
                                                            <div>
                                                                <small class="fw-bold">{{ $upload->learningDay->title ?? 'اليوم ' . $upload->learning_day_id }}</small>
                                                                <br>
                                                                <small class="text-muted">{{ $upload->original_name ?? 'ملف مرفوع' }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('admin.users.file-preview', $upload->id) }}" 
                                                               class="btn btn-outline-primary btn-sm" 
                                                               target="_blank">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="{{ asset('storage/' . $upload->file_path) }}" 
                                                               class="btn btn-outline-success btn-sm" 
                                                               download="{{ $upload->original_name }}">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">لا توجد ملفات</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->testResults->count() > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @foreach ($user->testResults as $result)
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <small>{{ $result->test->title }}</small>
                                                        <span class="badge bg-{{ $result->score >= 70 ? 'success' : 'warning' }}">
                                                            {{ $result->score }}%
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">لا توجد نتائج</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $completedDays = $user->completed_days ? json_decode($user->completed_days, true) : [];
                                            $completedCount = count($completedDays);
                                            $totalDays = 5; // Assuming 5 days total
                                            $progressPercentage = $totalDays > 0 ? ($completedCount / $totalDays) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $progressPercentage }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $completedCount }}/{{ $totalDays }} أيام</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
