@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-2"></i>رجوع للأسئلة الشائعة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">تعديل السؤال الشائع</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.faq.update', $faq) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="question" class="form-label">السؤال <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('question') is-invalid @enderror" 
                                   id="question" 
                                   name="question" 
                                   value="{{ old('question', $faq->question) }}" 
                                   required>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="answer" class="form-label">الإجابة <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('answer') is-invalid @enderror" 
                                      id="answer" 
                                      name="answer" 
                                      rows="5" 
                                      required>{{ old('answer', $faq->answer) }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">ترتيب العرض</label>
                            <input type="number" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', $faq->order) }}" 
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">الأسئلة ذات الترتيب الأقل تظهر أولاً</div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    نشط (يظهر للمستخدمين)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

