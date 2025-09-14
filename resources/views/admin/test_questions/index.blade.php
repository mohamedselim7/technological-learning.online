@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Back Button -->
        <div class="mb-3">
            <button onclick="goBack()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-2"></i>رجوع
            </button>
        </div>

        <h3 class="mb-4">قائمة الأسئلة</h3>

        <a href="{{ route('admin.test_questions.create') }}" class="btn btn-primary mb-3">
            إضافة سؤال جديد
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>The Exam</th>
                        <th>Question</th>
                        <th>Options</th>
                        @if (auth()->check() && auth()->user()->role === 'admin')
                            <th>The Correct Answer</th>
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $q)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $q->test->title ?? '-' }}</td>
                            <td>{{ $q->question }}</td>
                            <td>
                                A) {{ $q->option_a }} <br>
                                B) {{ $q->option_b }} <br>
                                C) {{ $q->option_c }} <br>
                                D) {{ $q->option_d }}
                            </td>
                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <td>
                                    {{ $q->correct_answer }}
                                </td>
                                <td>
                                   
                                    <form action="{{ route('admin.test_questions.destroy', $q->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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