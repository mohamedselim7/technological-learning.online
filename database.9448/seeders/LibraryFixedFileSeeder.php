<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LibraryFile;
use Illuminate\Support\Facades\Storage;

class LibraryFixedFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التحقق من وجود الملف
        if (Storage::disk('public')->exists('library/الموضوعات.pdf')) {
            // إضافة ملف الموضوعات كملف ثابت في المكتبة
            LibraryFile::updateOrCreate(
                ['file_name' => 'الموضوعات.pdf'],
                [
                    'title' => 'الموضوعات - الثورة الصناعية الرابعة والتكنولوجيا الخضراء',
                    'description' => 'ملف شامل يحتوي على جميع الموضوعات المتعلقة بالثورة الصناعية الرابعة، التكنولوجيا الخضراء، التنمية المستدامة، والذكاء الاصطناعي. هذا الملف مرجع أساسي لجميع الطلاب.',
                    'file_name' => 'الموضوعات.pdf',
                    'original_name' => 'الموضوعات.pdf',
                    'file_type' => 'pdf',
                    'file_size' => Storage::disk('public')->size('library/الموضوعات.pdf'),
                    'category' => 'مراجع أساسية',
                    'is_active' => true,
                    'is_fixed' => true,
                    'uploaded_by' => 1, // افتراض أن المدير له ID = 1
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

