<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    @include('layouts.header')
    @include('layouts.navbar')
    @yield('page_css')
</head>

<body dir="rtl">

    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3" style="width: 250px;">
            <h5>لوحة التحكم</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">إدارة الأيام</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.tasks') }}" class="nav-link text-white">مهام الأيام</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.test_questions.index') }}" class="nav-link text-white">الأسئلة</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link text-white">المستخدمين</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>

</body>

</html>
