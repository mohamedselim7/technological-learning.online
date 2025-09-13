@extends('layouts.layout')

@section('content')
    <div class="container py-5">

        <div class="rounded p-4 mb-5 text-white" style="background: linear-gradient(90deg, #0ea5e9, #3b82f6);">
            <div class="mb-2 d-flex align-items-center gap-2 text-start">
                <i class="bi bi-person-check"></i>
                <h2 class="h5 fw-bold mb-0">مرحباً بك في المسار التعليمي!</h2>
            </div>
            <p class="mb-2">برنامج تدريبي شامل مكون من 5 أيام مخصص للمبتدئين والمتوسطين</p>
            <div class="d-flex flex-wrap gap-3 small">
                <div class="d-flex align-items-center gap-1">
                    <i class="bi bi-list-check"></i> <span>اختبارات 3</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <i class="bi bi-award"></i> <span>شهادة إتمام</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <i class="bi bi-clock-history"></i> <span>ساعة إجمالية 15-20</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <i class="bi bi-calendar3-week"></i> <span>أيام تعليمية</span>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <h3 class="h5 fw-semibold mb-3">الاختبارات المتاحة</h3>
            
            @if ($allDaysCompleted && !$allTestsTaken)
                <div class="alert alert-warning mb-4">
                    <h5 class="alert-heading">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        اختبارات إجبارية
                    </h5>
                    <p class="mb-0">
                        تهانينا! لقد أكملت جميع الأيام التعليمية. يجب عليك الآن إجراء الاختبارات التالية لإتمام البرنامج والحصول على الشهادة.
                    </p>
                </div>
            @endif
            
            <div class="row g-4">
                @foreach ($tests as $test)
                    @php
                        $hasUserTaken = $user->testResults->where('test_id', $test->id)->first();
                        $isTestRequired = $allDaysCompleted && !$allTestsTaken;
                    @endphp
                    <div class="col-md-6">
                        <div class="card h-100 {{ $hasUserTaken ? 'border-success' : ($isTestRequired ? 'border-warning' : '') }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <small class="text-muted d-block mb-1">اختبار {{ $test->id }}</small>
                                    @if ($hasUserTaken)
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    @elseif ($isTestRequired)
                                        <span class="badge bg-warning">إجباري</span>
                                    @endif
                                </div>
                                <h5 class="card-title text-primary">{{ $test->title }}</h5>
                                <p class="card-text">{{ $test->description }}</p>
                                <small class="text-muted">{{ $test->duration_minutes }} دقيقة • {{ $test->question_count }} سؤال</small>
                                
                                @if ($hasUserTaken)
                                    <div class="mt-3">
                                        <div class="alert alert-success mb-2">
                                            <strong>تم الإنجاز!</strong> النتيجة: {{ $hasUserTaken->score }}%
                                        </div>
                                        <a href="{{ route('tests.show', $test->id) }}" class="btn btn-outline-primary w-100">
                                            مراجعة الاختبار
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('tests.show', $test->id) }}" 
                                       class="btn btn-{{ $isTestRequired ? 'warning' : 'primary' }} w-100 mt-3">
                                        {{ $isTestRequired ? 'ابدأ الاختبار الإجباري' : 'ابدأ الاختبار الآن' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <div class="mb-5">
            <h3 class="h5 fw-semibold mb-2">تقدمك في البرنامج 📈</h3>
            @php
                $totalDays = count($days);
                $completedCount = count($completedDays);
                $progressPercentage = $totalDays > 0 ? ($completedCount / $totalDays) * 100 : 0;
            @endphp
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-primary" style="width: {{ $progressPercentage }}%;"></div>
            </div>
            <small class="text-muted d-block mt-1">{{ $completedCount }}/{{ $totalDays }} أيام مكتملة</small>
        </div>

        <div class="row g-4">
            @foreach ($days as $day)
                @php
                    $isDayCompleted = in_array($day->id, $completedDays);
                @endphp
                <div class="col-md-4">
                    <div class="card h-100 {{ $isDayCompleted ? 'border-success' : '' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title text-{{ $day->color }}">{{ $day->title }}</h5>
                                @if ($isDayCompleted)
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                @endif
                            </div>
                            <p class="card-text">{{ $day->desc }}</p>
                            <ul class="list-unstyled small">
                                @foreach ($day->skills as $skill)
                                    <li>• {{ $skill }}</li>
                                @endforeach
                            </ul>
                            <small class="text-muted d-block mb-2">المدة: {{ $day->duration }}</small>
                            @if ($day->status === 'متاح')
                                <a href="{{ route('day.show', $day->id) }}" 
                                   class="btn btn-{{ $isDayCompleted ? 'outline-' . $day->color : $day->color }} w-100">
                                    {{ $isDayCompleted ? 'مراجعة اليوم' : 'ابدأ الآن' }}
                                </a>
                            @else
                                <span class="text-muted small">{{ $day->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
