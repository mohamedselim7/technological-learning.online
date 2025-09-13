@extends('layouts.admin')

@section('content')
    <!-- Back Button -->
    <div class="mb-3">
        <button onclick="goBack()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-2"></i>رجوع
        </button>
    </div>

    <h3 class="mb-4">قائمة الأسئلة</h3>

    <a href="{{ route('admin.test_questions.create') }}" class="btn btn-primary mb-3">إضافة سؤال جديد</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاختبار</th>
                    <th>السؤال</th>
                    <th>الاختيارات</th>
                    <th>الإجابة الصحيحة</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $q)
                    <tr>
                        <td>{{ $q->id }}</td>
                        <td>{{ $q->test->title }}</td>
                        <td>{{ $q->question }}</td>
                        <td>
                            أ) {{ $q->option_a }} <br>
                            ب) {{ $q->option_b }} <br>
                            ج) {{ $q->option_c }} <br>
                            د) {{ $q->option_d }}
                        </td>
                        
                        <td>{{ strtoupper($q->correct_answer) }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<script>
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = '/admin';
    }
}
</script>
@endsection
