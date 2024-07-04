@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Danh mục cấp 1</h2>
        <a class="btn btn-outline-primary" href="/admin/createcategory">Thêm danh mục mới</a>
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
                                <th scope="col">Tên Loại cấp 1</th>
                                <th scope="col">Ẩn / Hiện</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($category as $index => $item)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->hide == 0 ? 'Đang ẩn' : 'Đang hiện' }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="/admin/editcategory{{$item->id_category}}">Sửa</a>
                                        <form class="d-inline" action="/admin/destroycategory{{$item->id_category}}" method="POST">
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
                    {{ $category->onEachSide(3)->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
