@extends('layouts.layout')

@section('content')
<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-3">
        <button onclick="goBack()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-2"></i>رجوع
        </button>
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

<style>
.accordion-button:not(.collapsed) {
    background-color: #e3f2fd;
    color: #1976d2;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(25, 118, 210, 0.25);
}

.accordion-item {
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.accordion-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.accordion-body {
    line-height: 1.6;
}

@media (max-width: 768px) {
    .accordion-button {
        font-size: 0.9rem;
        padding: 0.75rem;
    }
}
</style>

<script>
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = '/';
    }
}

// Smooth scroll to opened accordion item
document.addEventListener('DOMContentLoaded', function() {
    const accordionButtons = document.querySelectorAll('.accordion-button');
    
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            setTimeout(() => {
                if (!this.classList.contains('collapsed')) {
                    this.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }, 350); // Wait for accordion animation
        });
    });
});
</script>
@endsection

