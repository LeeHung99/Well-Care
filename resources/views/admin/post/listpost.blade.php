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
            <h2 class="me-3">Bài viết</h2>
            <a class="btn btn-outline-primary" href="/admin/createpost">Thêm bài viết</a>
        </div>
        <div class="search-container">
            <div class="search-box">
                <form action="{{ route('post') }}" method="GET">
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
                    <table class="table table-striped border">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Tác giả</th>
                                <th scope="col">Chuyên mục</th>
                                <th scope="col"><i class="fa-regular fa-clock"></i> Thời gian</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $index => $post)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->user_name }}</td>
                                    <td>{{ $post->catename }}</td>
                                    <td>{{ $post->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="/admin/editpost{{ $post->id_post }}">Sửa</a>
                                        <form class="d-inline" action="/admin/destroypost{{ $post->id_post }}"
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
                    {{ $posts->onEachSide(3)->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
