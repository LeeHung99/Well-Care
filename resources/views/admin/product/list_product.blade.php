@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Danh sách sản phẩm</h2>
        <a class="btn btn-outline-primary" href="/admin/storeproduct">Thêm danh sản phẩm</a>
    </div>
    @if (Session::exists('thongbao'))
        <h4 class="alert alert-info text-center">{{ Session::get('thongbao') }}</h4>
    @endif
    <div class="main-post">
        <div class="row">
            <div class="col-xl-12">
                <div class="list-post">
                    <table class="table table-striped border align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Tồn kho</th>
                                <th scope="col">Thương hiệu</th>
                                <th scope="col">Ẩn / Hiện</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $item)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->Third_categories['name'] }}</td>
                                    <td><img src="http://127.0.0.1:8000/images/product/{{ $item->avatar }}" alt=""
                                            width="150px" height="75px"></td>
                                    <td>{{ number_format($item->price, 0, ',', '.') . ' ₫' }}</td>
                                    <td>{{ $item->in_stock }}</td>
                                    <td>{{ $item->brand }}</td>
                                    <td>{{ $item->hide == 0 ? 'Đang ẩn' : 'Đang hiện' }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                            href="/admin/editproduct{{ $item->id_product }}">Sửa</a>
                                        <form class="d-inline"
                                            action="{{ route('destroyproduct', ['id' => $item->id_product]) }}"
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
                    <div class="d-flex justify-content-center">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
