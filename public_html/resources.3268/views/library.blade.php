@extends('layouts.layout')

@section('title', 'المكتبة')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-3">
        <button onclick="goBack()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-2"></i>رجوع
        </button>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg p-6 mb-8">
            <h1 class="text-3xl font-bold mb-2">
                <i class="bi bi-book-fill mr-2"></i>
                المكتبة الرقمية
            </h1>
            <p class="text-blue-100">
                مجموعة من الموارد التعليمية والمراجع المهمة في مجال الثورة الصناعية الرابعة والتعليم الأخضر
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Admin Upload Section -->
        @if(Auth::check() && Auth::user()->is_admin)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-upload text-blue-500 mr-2"></i>
                    رفع ملف جديد (أدمن فقط)
                </h2>
                
                <form action="{{ route('library.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-8">
                        <label for="file" class="form-label">اختر الملف</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="title" class="form-label">العنوان (اختياري)</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" placeholder="عنوان الملف">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload mr-1"></i>رفع الملف
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Files Library Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="bi bi-file-earmark-pdf-fill text-red-500 mr-2"></i>
                الملفات والمراجع
            </h2>
            
            @if($files->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($files as $file)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center mb-3">
                                <div class="bg-red-100 p-3 rounded-full mr-3">
                                    @if(in_array($file['extension'], ['pdf']))
                                        <i class="bi bi-file-earmark-pdf-fill text-red-500 text-2xl"></i>
                                    @elseif(in_array($file['extension'], ['doc', 'docx']))
                                        <i class="bi bi-file-earmark-word-fill text-blue-500 text-2xl"></i>
                                    @elseif(in_array($file['extension'], ['ppt', 'pptx']))
                                        <i class="bi bi-file-earmark-ppt-fill text-orange-500 text-2xl"></i>
                                    @elseif(in_array($file['extension'], ['jpg', 'jpeg', 'png', 'gif']))
                                        <i class="bi bi-file-earmark-image-fill text-green-500 text-2xl"></i>
                                    @else
                                        <i class="bi bi-file-earmark-fill text-gray-500 text-2xl"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800 text-sm">{{ Str::limit($file['name'], 25) }}</h3>
                                    <p class="text-xs text-gray-600">{{ strtoupper($file['extension']) }} • {{ number_format($file['size'] / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            
                            <div class="text-sm text-gray-600 mb-4">
                                <p><i class="bi bi-calendar3 mr-1"></i> {{ date('Y-m-d', $file['modified']) }}</p>
                            </div>
                            
                            <div class="flex gap-2">
                                @if(in_array($file['extension'], ['pdf']))
                                    <button onclick="openPDF('{{ $file['url'] }}')" 
                                            class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300 text-center text-sm">
                                        <i class="bi bi-eye mr-1"></i>عرض
                                    </button>
                                @endif
                                <a href="{{ route('library.download', basename($file['path'])) }}" 
                                   class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition-colors duration-300 text-center text-sm">
                                    <i class="bi bi-download mr-1"></i>تحميل
                                </a>
                                @if(Auth::check() && Auth::user()->is_admin)
                                    <form action="{{ route('library.destroy', basename($file['path'])) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الملف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-folder-x display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد ملفات في المكتبة حالياً</h4>
                    @if(Auth::check() && Auth::user()->is_admin)
                        <p class="text-muted">يمكنك رفع الملفات باستخدام النموذج أعلاه</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- PDF Viewer Modal -->
<div id="pdfModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-6xl w-full h-5/6 flex flex-col">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">عارض PDF</h3>
            <button onclick="closePDF()" class="text-gray-500 hover:text-gray-700">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="flex-1 p-4">
            <iframe id="pdfViewer" src="" class="w-full h-full border-0 rounded"></iframe>
        </div>
    </div>
</div>

<script>
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = '/';
    }
}

function openPDF(pdfUrl) {
    const modal = document.getElementById('pdfModal');
    const viewer = document.getElementById('pdfViewer');
    viewer.src = pdfUrl;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePDF() {
    const modal = document.getElementById('pdfModal');
    const viewer = document.getElementById('pdfViewer');
    viewer.src = '';
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('pdfModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePDF();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePDF();
    }
});
</script>
@endsection

