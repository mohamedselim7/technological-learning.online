@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Back Button -->
    <div class="mb-3">
        <button onclick="goBack()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-2"></i>رجوع
        </button>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">إدارة الأسئلة الشائعة</h2>
        <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>إضافة سؤال جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($faqs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>الترتيب</th>
                                <th>السؤال</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $faq)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $faq->order }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($faq->question, 60) }}</div>
                                        <small class="text-muted">{{ Str::limit($faq->answer, 80) }}</small>
                                    </td>
                                    <td>
                                        @if($faq->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $faq->created_at->format('Y-m-d H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.faq.edit', $faq) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.faq.destroy', $faq) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-question-circle display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد أسئلة شائعة</h4>
                    <p class="text-muted mb-4">ابدأ بإضافة أول سؤال شائع</p>
                    <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>إضافة سؤال جديد
                    </a>
                </div>
            @endif
        </div>
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

