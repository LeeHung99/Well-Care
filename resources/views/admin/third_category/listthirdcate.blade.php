<style>
    .search-container {
        /* display: flex; */
        justify-content: center;
        align-items: center;
        /* height: 100vh; */
    }

    .search-box {
        position: relative;
        width: 300px;
    }

    .search-box input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 25px;
        outline: none;
    }

    .search-box button {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
    }

    .search-box button img {
        width: 20px;
        height: 20px;
    }
</style>
@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="d-flex justify-content-between align-items-end " style="width: 90%">
        <div class="title_post d-flex my-3">
            <h2 class="me-3">Danh mục cấp 3</h2>
            <a class="btn btn-outline-primary" href="/admin/createthirdcategory">Thêm danh mục mới</a>
        </div>
        <div class="search-container">
            <div class="search-box">
                <form action="{{ route('thirdcategory') }}" method="GET">
                    <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="Tìm kiếm...">
                    <button type="submit">
                        <img src="https://img.icons8.com/ios-glyphs/30/000000/search--v1.png" alt="Tìm kiếm">
                    </button>
                </form>
            </div>
        </div>
    </div>
    @if (Session::exists('thongbao'))
        <h4 class="alert alert-info text-center">{{ Session::get('thongbao') }}</h4>
    @endif
    <div class="main-post">
        <div class="row">
            <div class="col-xl-12">
                {{-- <div class="filter-post">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Hành động hàng loạt</option>
                        <option value="1">Xóa</option>
                    </select>
                </div> --}}
                <div class="list-post">
                    <table class="table table-striped border align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên Loại cấp 3</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Tên Loại cấp 2</th>
                                <th scope="col">Ẩn / Hiện</th>
                                <th scope="col">Thứ tự</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($thirdcategories as $index => $item)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $item->thirdcate_name }}</td>
                                    <td><img src="https://cms.wellcarepharmacy.shop/images/category/{{ $item->avatar }}" alt=""
                                            width="150px" height="75px"></td>
                                    <td>{{ $item->secate_name }}</td>
                                    <td>{{ $item->hide == 0 ? 'Đang ẩn' : 'Đang hiện' }}</td>
                                    <td>{{ $item->order }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                            href="/admin/editthirdcategory{{ $item->id_third_category }}">Sửa</a>
                                        <form class="d-inline"
                                            action="/admin/destroythirdcategory{{ $item->id_third_category }}"
                                            method="POST">
                                            @csrf
                                            <button type='submit' onclick="return confirm('Xóa hả')"
                                                class="btn btn-danger btn-sm">
                                                Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $thirdcategories->onEachSide(3)->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
