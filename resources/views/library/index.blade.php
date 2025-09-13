@extends('layouts.layout')

@section('content')
<div class="container py-5">

     @if(auth()->check() && auth()->user()->role === 'admin')
    
    <div class="mb-5">
        <h3 class="mb-3 text-primary">رفع ملف جديد</h3>
        <form action="{{ route('library.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">اختر الملف</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">العنوان</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">التصنيف</label>
                <input type="text" name="category" id="category" class="form-control">
            </div>
            <div class="form-check mb-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" checked>
                <label for="is_active" class="form-check-label">نشط</label>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="is_fixed" id="is_fixed" value="1" class="form-check-input">
                <label for="is_fixed" class="form-check-label">مثبت</label>
            </div>
            <button type="submit" class="btn btn-success">رفع الملف</button>
        </form>
    </div>
    @endif

    
    <div class="mb-5">
        <h3 class="mb-3 text-primary">📂 ملفات المكتبة</h3>
        @if($files->count() > 0)
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الوصف</th>
                        <th>التصنيف</th>
                        <th>تحميل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $file)
                        <tr>
                            <td>{{ $file->title }}</td>
                            <td>{{ $file->description }}</td>
                            <td>{{ $file->category ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.library.download', $file->id) }}" class="btn btn-sm btn-success">
                                    ⬇ تحميل
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $files->links() }}
            </div>
        @else
            <p class="text-muted">لا توجد ملفات مرفوعة بعد</p>
        @endif
    </div>

   
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h2 class="text-primary mb-3">الأسئلة الشائعة</h2>
                <p class="text-muted">اعثر على إجابات للأسئلة الأكثر شيوعاً</p>
            </div>

            @if($faqs->count() > 0)
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item mb-3 border rounded shadow-sm">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button collapsed fw-bold text-end" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse{{ $index }}" 
                                        aria-expanded="false" 
                                        aria-controls="collapse{{ $index }}">
                                    <i class="bi bi-question-circle me-2 text-primary"></i>
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" 
                                 class="accordion-collapse collapse" 
                                 aria-labelledby="heading{{ $index }}" 
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body bg-light">
                                    <div class="d-flex">
                                        <i class="bi bi-chat-dots text-success me-2 mt-1"></i>
                                        <div class="text-muted">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-question-circle display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد أسئلة شائعة متاحة حالياً</h4>
                    <p class="text-muted">سيتم إضافة الأسئلة الشائعة قريباً</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection