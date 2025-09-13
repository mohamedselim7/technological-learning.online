@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">إجابات المستخدم: {{ $user->full_name }}</h2>

    @if($answers->isEmpty())
        <p class="text-muted">لا توجد إجابات لهذا المستخدم.</p>
    @else
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>السؤال</th>
                    <th>إجابتك</th>
                    <th>الإجابة الصحيحة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answers as $index => $answer)
                    @if($answer->question)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $answer->question->question }}</td>
                            <td>{{ $answer->user_answer }}</td>
                            <td>
                                @if(!empty($answer->question->your_answer))
                                    {{ $answer->question->your_answer }}
                                @else
                                    <span class="text-muted">غير متوفر</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection