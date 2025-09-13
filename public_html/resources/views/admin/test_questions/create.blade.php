@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Back Button -->
        <div class="mb-3">
            <button onclick="goBack()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-2"></i>رجوع
            </button>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">إضافة سؤال جديد</h3>
            <a href="{{ route('admin.test_questions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>العودة
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.test_questions.store') }}" id="questionForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">اختر الاختبار</label>
                                <select name="test_id" class="form-control" required>
                                    <option disabled selected>-- اختر الاختبار --</option>
                                    @foreach ($tests as $test)
                                        <option value="{{ $test->id }}">{{ $test->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">نوع السؤال</label>
                                <select name="question_type" id="questionType" class="form-control" required>
                                    <option disabled selected>-- اختر نوع السؤال --</option>
                                    <option value="mcq">اختيار من متعدد (MCQ)</option>
                                    <option value="likert">مقياس ليكرت (Likert Scale)</option>
                                    <option value="scenario">أسئلة المواقف (Scenario-based)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">السؤال</label>
                        <textarea name="question" class="form-control" rows="3" required placeholder="اكتب نص السؤال هنا..."></textarea>
                    </div>

                    <!-- MCQ Options -->
                    <div id="mcqOptions" style="display: none;">
                        <h5 class="text-primary mb-3">خيارات الاختيار من متعدد</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الاختيار (أ)</label>
                                    <input type="text" name="option_a" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الاختيار (ب)</label>
                                    <input type="text" name="option_b" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الاختيار (ج)</label>
                                    <input type="text" name="option_c" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الاختيار (د)</label>
                                    <input type="text" name="option_d" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الإجابة الصحيحة</label>
                            <select name="correct_answer" class="form-control">
                                <option value="">-- اختر الإجابة الصحيحة --</option>
                                <option value="a">أ</option>
                                <option value="b">ب</option>
                                <option value="c">ج</option>
                                <option value="d">د</option>
                            </select>
                        </div>
                    </div>

                    <!-- Likert Scale Info -->
                    <div id="likertInfo" style="display: none;">
                        <div class="alert alert-info">
                            <h5 class="alert-heading">مقياس ليكرت</h5>
                            <p class="mb-0">سيتم استخدام مقياس ليكرت الخماسي التالي:</p>
                            <ul class="mt-2 mb-0">
                                <li>1 - غير موافق بشدة</li>
                                <li>2 - غير موافق</li>
                                <li>3 - محايد</li>
                                <li>4 - موافق</li>
                                <li>5 - موافق بشدة</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Scenario Info -->
                    <div id="scenarioInfo" style="display: none;">
                        <div class="alert alert-warning">
                            <h5 class="alert-heading">أسئلة المواقف</h5>
                            <p class="mb-0">هذا النوع من الأسئلة يتطلب من المستخدم كتابة إجابة نصية مفتوحة بناءً على الموقف المطروح.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>حفظ السؤال
                        </button>
                        <a href="{{ route('admin.test_questions.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('questionType').addEventListener('change', function() {
            const questionType = this.value;
            const mcqOptions = document.getElementById('mcqOptions');
            const likertInfo = document.getElementById('likertInfo');
            const scenarioInfo = document.getElementById('scenarioInfo');
            
            // Hide all sections first
            mcqOptions.style.display = 'none';
            likertInfo.style.display = 'none';
            scenarioInfo.style.display = 'none';
            
            // Show relevant section
            if (questionType === 'mcq') {
                mcqOptions.style.display = 'block';
                // Make MCQ fields required
                mcqOptions.querySelectorAll('input, select').forEach(field => {
                    field.required = true;
                });
            } else if (questionType === 'likert') {
                likertInfo.style.display = 'block';
            } else if (questionType === 'scenario') {
                scenarioInfo.style.display = 'block';
            }
            
            // Remove required from MCQ fields if not MCQ
            if (questionType !== 'mcq') {
                mcqOptions.querySelectorAll('input, select').forEach(field => {
                    field.required = false;
                });
            }
        });

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '{{ route("admin.test_questions.index") }}';
            }
        }
    </script>
@endsection
