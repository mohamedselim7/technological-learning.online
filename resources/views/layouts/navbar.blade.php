<nav class="navbar navbar-expand-lg bg-white border-bottom py-3" dir="rtl">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <div class="d-flex flex-column text-end">
            <a class="navbar-brand fw-bold text-primary m-0" href="{{ route('home') }}">المنصة التعليمية - المسار
                العادي</a>
            <small class="text-muted">منصة التعلم للمبتدئين والمتوسطين</small>
        </div>

        <ul class="navbar-nav d-flex flex-row align-items-center gap-3 m-0">
            <li class="nav-item d-flex align-items-center small">
                <i class="bi bi-book text-muted"></i>
                <a class="nav-link px-1 text-muted" href="{{ route('library.index') }}">المكتبة</a>
            </li>

            <li class="nav-item d-flex align-items-center text-primary small">
                <i class="bi bi-person"></i>
                <span class="ms-1">مرحباً، {{ Auth::user()->username }}</span>
            </li>
            @if (Auth::user()->role == 'admin')
                <li class="nav-item d-flex align-items-center small">
                    <i class="bi bi-speedometer2 text-muted"></i>
                    <a class="nav-link px-1 text-muted" href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
                </li>
            @endif


            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">تسجيل الخروج</button>
                </form>
            </li>
        </ul>
    </div>
</nav>
