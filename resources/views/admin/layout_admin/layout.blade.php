<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TRANG QUẢN TRỊ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/29ac88f093.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <div class="container-fluid p-0">
        <div class="admin-bar">
            <div class="row">
                <div class="col-xl-12 d-flex justify-content-between align-content-center nav-bar">
                    <div class="view-website d-flex">
                        <i class="fa-solid fa-house-laptop"></i>
                        <p>Well-care</p>
                    </div>
                    <div class="view-user d-flex">
                        <p>Chào, <span>Admin</span></p>
                        <i class="fa-solid fa-user"></i>
                        <div class="dropdown-view-user">
                            <ul class="menu-user">
                                <li><a href="">Chỉnh sửa hồ sơ</a></li>
                                <li><a href="">Đăng xuất</a></li>
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
                                <button class="accordion-button collapsed" type="button">Bảng tin</button>
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
                                            <li class="list-group-item"><a href="/admin/post" class="nav-link text-white">Tất cả bài viết</a></li>
                                            <li class="list-group-item"><a href="/admin/createpost" class="nav-link text-white">Viết bài mới</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button">Phản hồi</button>
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
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Danh mục cấp 1</a></li>
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Danh mục cấp 2</a></li>
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Danh mục cấp 3</a></li>
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
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Tất cả sản phẩm</a></li>
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Thêm sản phẩm</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button">Các ưu đãi</button>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseFour" aria-expanded="false"
                                        aria-controls="flush-collapseFour">
                                        Giao diện
                                    </button>
                                </h2>
                                <div id="flush-collapseFour" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Logo</a></li>
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Banner Header</a></li>
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Banner Main</a></li>
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Banner Footer</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseFive" aria-expanded="false"
                                        aria-controls="flush-collapseFive">
                                        Thành viên
                                    </button>
                                </h2>
                                <div id="flush-collapseFive" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Tất cả người dùng</a></li>
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Thêm người dùng mới</a></li>
                                            <li class="list-group-item"><a href="" class="nav-link text-white">Hồ sơ</a></li>
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
</body>

</html>
