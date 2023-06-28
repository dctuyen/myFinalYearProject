<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>English Center - @yield('title')</title>

    <meta name="description" content=""/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('/images/logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('theme/assets/vendor/fonts/boxicons.css') }}"/>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('theme/assets/vendor/css/core.css') }}" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="{{ asset('theme/assets/vendor/css/theme-default.css') }}"
          class="template-customizer-theme-css"/>
    <link rel="stylesheet" href="{{ asset('theme/assets/css/demo.css') }}"/>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}"/>

    <link rel="stylesheet" href="{{ asset('theme/assets/vendor/libs/apex-charts/apex-charts.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
          integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
          crossorigin="anonymous"/>
    @vite(['public/css/all.css', 'public/css/custom.css'])
    @vite(['resources/css/app.css'])
    @vite(['public/css/all.css', 'public/css/v4-shims.css'])
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('theme/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('theme/assets/js/config.js') }}"></script>
</head>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme left-sidebar"
               style="overflow: hidden">
            <div class="app-brand demo">
                <div class="logo dark-logo-bg visible-xs-* visible-sm-*">
                    <a class="row" href="{{ route('admin.home') }}">
                        <img class="col-3" src="{{ asset('/images/logo.png') }}" alt=""
                             style="width: 90px; border-radius: 90%">
                        <span class="col-7 brand-name mt-4 p-0"
                              style="white-space: nowrap; font-weight: bold; font-size: 18px">
                            English Center
                        </span>
                    </a>
                </div>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboard -->
                <li class="menu-item {{ $activeSidebar === 'home' ? 'active' : '' }}">
                    <a href="{{ route('studentmanagement') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <span>Trang chủ</span>
                    </a>
                </li>
                <li class="menu-item @if($activeSidebar === 'myaccount') active @endif">
                    <a href="{{ route('myaccount') }}" class="menu-link">
                        <i class="menu-icon tf-icons far fa-user"></i>
                        <span>Tài khoản của tôi</span>
                    </a>
                </li>
                @if($uinfo->role_id == 1)
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Quản lý</span>
                    </li>
                    <!-- Layouts -->
                    <li class="menu-item @if($activeSidebar === 'studentmanagement') active open @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="fas fa-graduation-cap"></i>&emsp;<span>Học Viên</span>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item @if($activeSidebar === 'studentmanagement') active @endif">
                                <a href="{{ route('studentmanagement') }}" class="menu-link">
                                    Quản lý học viên</a>
                            </li>
                            <li class="menu-item @if($activeSidebar === '') active @endif">
                                <a href="{{ route('studentmanagement') }}" class="menu-link">
                                    Chờ khai giảng</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item @if($activeSidebar === 'teachermanagement') active open @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="fas fa-chalkboard-teacher"></i>&emsp;<span>Giảng viên</span>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item @if($activeSidebar === 'teachermanagement') active @endif">
                                <a href="{{ route('teachermanagement') }}" class="menu-link">
                                    Quản lý giảng viên</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item @if($activeSidebar === 'carermanagement') active open @endif">
                        <a href="{{ route('carermanagement') }}" class="menu-link">
                            <i class="fas fa-handshake"></i>&emsp;<span>Người chăm sóc</span>
                        </a>
                    </li>

                    <li class="menu-item @if($activeSidebar === 'classmanagement' || $activeSidebar === 'classwaiting') active open @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="far fa-pencil-ruler"></i>&emsp;<span>Lớp học</span>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item @if($activeSidebar === 'classmanagement') active @endif">
                                <a href="{{ route('classmanagement') }}" class="menu-link">
                                    Quản lý lớp học</a>
                            </li>
                            <li class="menu-item @if($activeSidebar === 'classwaiting') active @endif">
                                <a href="{{ route('classmanagement.waiting') }}" class="menu-link">
                                    Chờ khai giảng</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item @if($activeSidebar === 'coursemanagement') active open @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="far fa-list-ul"></i>&emsp;<span>Danh mục</span>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item @if($activeSidebar === 'coursemanagement') active @endif">
                                <a href="{{ route('admin.coursemanagement') }}" class="menu-link">
                                    Danh sách khóa học</a>
                            </li>
                            <li class="menu-item @if($activeSidebar === 's') active @endif">
                                <a href="{{ route('admin.coursemanagement') }}" class="menu-link">
                                    Danh sách ca học</a>
                            </li>
                            <li class="menu-item @if($activeSidebar === '') active @endif">
                                <a href="#" class="menu-link">
                                    Tài liệu</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($uinfo->role_id == 2)
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Danh sách</span>
                    </li>
                    <li class="menu-item @if($activeSidebar === 'student.index') active open @endif">
                        <a href="{{ route('student.index') }}" class="menu-link">
                            <i class="far fa-books"></i>&emsp;<span>Danh sách khóa học</span>
                        </a>
                    </li>
                    <li class="menu-item @if($activeSidebar === '') active open @endif">
                        <a href="{{ route('student.index') }}" class="menu-link">
                            <i class="far fa-pencil-ruler"></i>&emsp;<span>Lớp học của tôi</span>
                        </a>
                    </li>
                @endif
            </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page" style="margin-top: 0!important">
            <!-- Navbar -->

            <nav
                class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                id="layout-navbar"
            >
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                        <i class="bx bx-menu bx-sm"></i>
                    </a>
                </div>

                <!-- Search -->
                <div class="navbar-nav d-flex">
                    <input class="form-control me-2" type="search" id="searchData"
                           value="{{ request()->query('search') }}"
                           placeholder="Tìm kiếm" aria-label="Tìm kiếm">
                    <button class="btn btn-outline-primary" id="btnSearch" data-togle="tooltip" title="Tìm kiếm"><i
                            class="fas fa-search"></i></button>
                </div>
                <!-- /Search -->
                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <!-- User -->
                        <li class="nav-item navbar-dropdown dropdown me-3">
                            <div class="demo-inline-spacing">
                                <div class="btn-group" style="margin: 0 !important">
                                    <button
                                        type="button"
                                        class="btn rounded-pill btn-icon dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fas fa-bell fa-lg" style="color: #d99100;"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">5</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider"/>
                                        </li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Xem tất cả thông báo</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </li>
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                               data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <img src="{{ asset($uinfo->background_url) }}" alt
                                         class="w-px-40 h-auto rounded-circle"/>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <img src="{{ asset($uinfo->background_url) }}" alt
                                                         class="w-px-40 h-auto rounded-circle"/>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span
                                                    class="fw-semibold d-block">{{ $uinfo->first_name . ' ' . $uinfo->last_name }}</span>
                                                <small class="text-muted">
                                                    @if ($uinfo->role_id === 1)
                                                        Admin
                                                    @elseif ($uinfo->role_id === 2)
                                                        Học viên
                                                    @elseif ($uinfo->role_id === 3)
                                                        Giảng viên
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('myaccount') }}">
                                        <i class="bx bx-user me-2"></i>
                                        <span class="align-middle">Thông tin</span>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                       href="{{ route('logout') }}">
                                        <i class="bx bx-power-off me-2"></i>
                                        <span class="align-middle">Đăng xuất</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--/ User -->
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </nav>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper" style="overflow-y: auto">

                <!-- Content -->
                <div class="container flex-grow-1 container-p-y">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
                <!-- / Content -->

                <!-- Footer -->
                <footer class="content-footer footer bg-light">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            , made with ❤️ by Tuyendc
                        </div>
                        <div>
                            <a class="dbtn btn-sm btn-outline-danger"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                               href="{{ route('logout') }}">
                                <i class="bx bx-log-out-circle"></i>
                                <span class="align-middle">Đăng xuất</span>
                            </a>
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>

        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->

<div class="buy-now">
    <button id="scrollTop" title="Go to top" class="btn btn-danger btn-buy-now btn-icon rounded-pill">
        <i class="fa fa-angle-up" aria-hidden="true"></i>
    </button>
</div>
<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('theme/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('theme/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('theme/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('theme/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('theme/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('theme/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('theme/assets/js/dashboards-analytics.js') }}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script type="text/javascript">
    $('#btnSearch').on('click', function () {
        let currentUrl = window.location.href;
        let realUrl = currentUrl.split("?")[0];
        let searchData = $('#searchData').val();
        window.location.href = realUrl + '?search=' + searchData;
    });

    $('#searchData').on('keydown', function () {
        if (event.keyCode === 13) {
            // Kiểm tra nếu là phím Enter (keyCode = 13)
            $('#btnSearch').click();
        }
    });

    Number.prototype.formatMoney = function (c, d, t) {
        var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d === undefined ? "." : d,
            t = t === undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

    let scrollTop = $("#scrollTop");
    $(scrollTop).css("display", "none");

    //Show/hide button GO TO TOP when scrolling
    $(window).scroll(function () {
        let topPos = $(this).scrollTop();
        // if user scrolls down - show scroll to top button
        if (topPos > 100) {
            $(scrollTop).css("display", "block");
        } else {
            $(scrollTop).css("display", "none");
        }
    });

    //Click button to scroll to top
    $(scrollTop).click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    $('.format-money').each(function () {
        $(this).text(parseInt($(this).text()).formatMoney(0, ',', '.'));
        $(this).val(parseInt($(this).val()).formatMoney(0, ',', '.'));
    });
</script>
@yield('script')

</body>
</html>
