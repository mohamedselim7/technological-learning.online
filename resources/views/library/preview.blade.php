@extends('layouts.app')

@section('title', 'معاينة الملف - ' . $file->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">{{ $file->title }}</h1>
                @if($file->is_fixed)
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                        ملف ثابت
                    </span>
                @endif
            </div>
            
            @if($file->description)
                <p class="text-gray-600 mb-4">{{ $file->description }}</p>
            @endif
            
            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                <span><i class="fas fa-file"></i> {{ $file->original_name }}</span>
                <span><i class="fas fa-hdd"></i> {{ $file->formatted_file_size }}</span>
                @if($file->category)
                    <span><i class="fas fa-tag"></i> {{ $file->category }}</span>
                @endif
                <span><i class="fas fa-calendar"></i> {{ $file->created_at->format('Y-m-d') }}</span>
            </div>
        </div>

        <!-- File Preview -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">معاينة الملف</h2>
            
            @if($fileType === 'pdf')
                <div class="w-full h-96 border rounded-lg overflow-hidden">
                    <iframe src="{{ $fileUrl }}" 
                            class="w-full h-full" 
                            frameborder="0">
                        <p>متصفحك لا يدعم عرض ملفات PDF. 
                           <a href="{{ $fileUrl }}" target="_blank" class="text-blue-600 hover:underline">
                               اضغط هنا لفتح الملف
                           </a>
                        </p>
                    </iframe>
                </div>
            @elseif($fileType === 'image')
                <div class="text-center">
                    <img src="{{ $fileUrl }}" 
                         alt="{{ $file->title }}" 
                         class="max-w-full h-auto rounded-lg shadow-md mx-auto">
                </div>
            @elseif($fileType === 'video')
                <div class="w-full">
                    <video controls class="w-full rounded-lg shadow-md">
                        <source src="{{ $fileUrl }}" type="video/mp4">
                        متصفحك لا يدعم تشغيل الفيديو.
                    </video>
                </div>
            @elseif($fileType === 'audio')
                <div class="w-full">
                    <audio controls class="w-full">
                        <source src="{{ $fileUrl }}" type="audio/mpeg">
                        متصفحك لا يدعم تشغيل الصوت.
                    </audio>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 mb-4">لا يمكن معاينة هذا النوع من الملفات</p>
                    <a href="{{ route('library.download', $file->id) }}" 
                       class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        تحميل الملف
                    </a>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('library.download', $file->id) }}" 
                   class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    تحميل الملف
                </a>
                
                <a href="{{ route('library.index') }}" 
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للمكتبة
                </a>
                
                <button onclick="window.print()" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-print mr-2"></i>
                    طباعة
                </button>
                
                <button onclick="shareFile()" 
                        class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-share mr-2"></i>
                    مشاركة
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function shareFile() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $file->title }}',
            text: '{{ $file->description }}',
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('تم نسخ رابط الملف إلى الحافظة');
        });
    }
}
</script>
@endsection

