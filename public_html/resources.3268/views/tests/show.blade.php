@extends('layouts.layout')

@section('content')
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-3">
            <button onclick="goBack()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-2"></i>رجوع
            </button>
        </div>

        <h3 class="mb-4">{{ $test->title }}</h3>
        <p>{{ $test->description }}</p>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($existingResult)
            <div class="alert alert-info">
                لقد قمت بإجراء هذا الاختبار مسبقًا ✅<br>
                نتيجتك: <strong>{{ $existingResult->score }}</strong> من {{ $test->questions->count() }}
            </div>

            <h5 class="mt-4">إجاباتك:</h5>
            @foreach ($test->questions as $index => $question)
                @php
                    $userAnswer = $existingResult->answers->firstWhere('question_id', $question->id);
                @endphp
                <div class="card mb-3">
                    <div class="card-body">
                        <strong>السؤال {{ $index + 1 }}:</strong> {{ $question->question }}
                        <ul class="mt-2 list-unstyled">
                            <li @if ($userAnswer?->user_answer === 'a') class="fw-bold text-primary" @endif>أ)
                                {{ $question->option_a }}</li>
                            <li @if ($userAnswer?->user_answer === 'b') class="fw-bold text-primary" @endif>ب)
                                {{ $question->option_b }}</li>
                            <li @if ($userAnswer?->user_answer === 'c') class="fw-bold text-primary" @endif>ج)
                                {{ $question->option_c }}</li>
                            <li @if ($userAnswer?->user_answer === 'd') class="fw-bold text-primary" @endif>د)
                                {{ $question->option_d }}</li>
                        </ul>
                        <p class="mt-2">✅ الإجابة الصحيحة: <strong>
                                @switch($question->correct_answer)
                                    @case('a')
                                        أ
                                    @break

                                    @case('b')
                                        ب
                                    @break

                                    @case('c')
                                        ج
                                    @break

                                    @case('d')
                                        د
                                    @break
                                @endswitch
                            </strong></p>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Form to submit the test --}}
            <form method="POST" action="{{ route('tests.submit', $test->id) }}">
                @csrf
                @foreach ($test->questions as $index => $question)
                    <div class="card mb-3">
                        <div class="card-body">
                            <strong>السؤال {{ $index + 1 }}:</strong> {{ $question->question }}
                            <div class="mt-2">
                                @foreach (['a' => 'أ', 'b' => 'ب', 'c' => 'ج', 'd' => 'د'] as $key => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                            value="{{ $key }}" id="q{{ $question->id }}_{{ $key }}"
                                            required>
                                        <label class="form-check-label" for="q{{ $question->id }}_{{ $key }}">
                                            {{ $label }}) {{ $question['option_' . $key] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary">إرسال الإجابات</button>
            </form>
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
