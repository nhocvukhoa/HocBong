<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    @can('ctsv')
    <li class="nav-item active">
        <a class="nav-link" href="index.html" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_loaihocbong')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý loại học bổng</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_hocbong')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý thông tin học bổng</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_namhoc')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý năm học</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_hocky')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý học kỳ</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_khoa')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý khoa</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_nganh')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý ngành học</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_lop')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý lớp</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_tieuchi')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý tiêu chí</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_thongbao')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý thông báo</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('list_account')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Duyệt đăng kí tài khoản</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('list_post')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Duyệt bài đăng học bổng</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('show_thietlapquyen')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Thiết lập quyền</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"
        style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-cog"></i>
            <span>Thống kê</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{route('thongke_index')}}">Thống kê 1</a>
                <!-- <a class="collapse-item" href="{{route('thongke_max_register')}}">Thống kê 2</a> -->
                <a class="collapse-item" href="{{route('thongke_total_bysemester')}}">Thống kê 2</a>
                <a class="collapse-item" href="{{route('thongke_total_student_apply')}}">Thống kê 3</a>
            </div>
        </div>
    </li>
    @endcan

    @can('cbk')
    <li class="nav-item">
        <a class="nav-link" href="{{route('show_diemrenluyen')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Import điểm rèn luyện</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('thongke_diemrenluyen')}}" style="padding: 10px 1rem;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Thống kê</span></a>
    </li>
    @endcan



    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>