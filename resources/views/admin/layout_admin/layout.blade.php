<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="X-UA-Compatible" content="{{ csrf_token() }}"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="/images/logo/LOGOsite.png" />
    <title>TRANG QUẢN TRỊ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/29ac88f093.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <div class="container-fluid p-0">
        <div class="admin-bar">
            <div class="row">
                <div class="col-xl-12 d-flex justify-content-between align-content-center nav-bar">
                    <div class="view-website d-flex">
                        <i class="fa-solid fa-house-laptop"></i>
                        <p><a href="http://localhost:3000/" class="nav-link" title="View website">Well-care</a></p>
                    </div>
                    <div class="view-user d-flex">
                        <p> Chào, <span> {{ Auth::user()->name }}</span></p>
                        <i class="fa-solid fa-user"></i>
                        <div class="dropdown-view-user">
                            <ul class="menu-user">
                                <li><a href="/admin/editusers{{ Auth::user()->id_user }}">Chỉnh sửa hồ sơ</a></li>
                                <li><a href="/exit">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-admin">
            <div class="row">
                <div class="col-xl-2">
                    <div class="contain-side-bar">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button">
                                    <a href="/admin/" class="nav-link text-white">Bảng tin</a>
                                </button>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne">
                                        Bài viết
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="/admin/post"
                                                    class="nav-link text-white">Tất cả bài viết</a></li>
                                            <li class="list-group-item"><a href="/admin/createpost"
                                                    class="nav-link text-white">Viết bài mới</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button"><a style="color: white" href="{{ route('comment') }}">Phản
                                        hồi</a></button>

                            </div>
                            <div class="accordion-item" style="background-color: #1d2327">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                        aria-controls="flush-collapseTwo">
                                        Danh mục sản phẩm
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="/admin/category"
                                                    class="nav-link text-white">Danh mục cấp 1</a></li>
                                            <li class="list-group-item"><a href="/admin/secategory"
                                                    class="nav-link text-white">Danh mục cấp 2</a></li>
                                            <li class="list-group-item"><a href="/admin/thirdcategory"
                                                    class="nav-link text-white">Danh mục cấp 3</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseThree" aria-expanded="false"
                                        aria-controls="flush-collapseThree">
                                        Sản phẩm
                                    </button>
                                </h2>
                                <div id="flush-collapseThree" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="{{ route('product') }}"
                                                    class="nav-link text-white">Tất cả sản phẩm</a></li>
                                            <li class="list-group-item"><a href="{{ route('storeproduct') }}"
                                                    class="nav-link text-white">Thêm sản phẩm</a></li>
                                            <li class="list-group-item"><a href="{{ route('sick') }}"
                                                    class="nav-link text-white">Danh sách bệnh</a></li>
                                            <li class="list-group-item"><a href="{{ route('object') }}"
                                                    class="nav-link text-white">Danh sách đối tượng</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button">
                                    <a href="/admin/bill" class="nav-link text-white">Đơn hàng</a>
                                </button>
                            </div>
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button">
                                    <a href="/admin/voucher" class="nav-link text-white">Các ưu đãi</a>
                                </button>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseFour"
                                        aria-expanded="false" aria-controls="flush-collapseFour">
                                        Giao diện
                                    </button>
                                </h2>
                                <div id="flush-collapseFour" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="/admin/logo"
                                                    class="nav-link text-white">Logo</a></li>
                                            <li class="list-group-item"><a href="/admin/banner"
                                                    class="nav-link text-white">Banner</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseFive"
                                        aria-expanded="false" aria-controls="flush-collapseFive">
                                        Thành viên
                                    </button>
                                </h2>
                                <div id="flush-collapseFive" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="/admin/kh"
                                                    class="nav-link text-white">Tất cả khách hàng</a></li>
                                            <li class="list-group-item"><a href="/admin/users"
                                                    class="nav-link text-white">Tất cả nhân viên</a></li>
                                            <li class="list-group-item"><a
                                                    href="/admin/editusers{{ Auth::user()->id_user }}"
                                                    class="nav-link text-white">Hồ sơ</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-10">
                    <div class="row">
                        <div class="col-xl-12">
                            @yield('noidungchinh')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    <script src="{{ asset('js/ckeditor-upload-adapter.js') }}"></script>
    @yield('js-custom')
</body>

</html>
