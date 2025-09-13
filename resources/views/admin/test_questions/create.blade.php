@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        
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
                    </div>

                   
                    <div class="mb-3">
                        <label class="form-label">  السؤال  </q></label>
                        <textarea name="question" class="form-control" rows="3" required placeholder="اكتب نص السؤال هنا..."></textarea>
                    </div>

                    
                    <div id="mcqOptions">
                        <h5 class="text-primary mb-3">خيارات الاختيار من متعدد</h5>
                        <div class="row">
                            @foreach (['a', 'b', 'c', 'd'] as $letter)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">الاختيار ({{ strtoupper($letter) }})</label>
                                        <input type="text" name="option_{{ $letter }}" class="form-control"
                                            required>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label">الإجابة الصحيحة</label>
                        <select name="your_answer" class="form-control" required>
                            <option disabled selected>-- اختر الإجابة الصحيحة --</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
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
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '{{ route('admin.test_questions.index') }}';
            }
        }
    </script>
@endsection
