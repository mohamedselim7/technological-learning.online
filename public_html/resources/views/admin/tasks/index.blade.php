@extends('layouts.admin')

@section('content')
    <div class="d-flex" style="min-height: 100vh;">

       

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <h4 class="mb-4">مهام الأيام حسب الخبرة</h4>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.tasks.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="learning_day_id" class="form-label">اختر اليوم</label>
                    <select name="learning_day_id" id="learning_day_id" class="form-control" required>
                        @foreach ($days as $day)
                            <option value="{{ $day->id }}">{{ $day->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="experience_level" class="form-label">مستوى الخبرة</label>
                    <select name="experience_level" id="experience_level" class="form-control" required>
                        <option value="أقل من 15 سنة">أقل من 15 سنة</option>
                        <option value="أكثر من 15 سنة">أكثر من 15 سنة</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="task" class="form-label">نص المهمة</label>
                    <textarea name="task" id="task" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">حفظ المهمة</button>
            </form>
        </div>
    </div>
@endsection
