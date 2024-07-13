<style>
    thead a {
        color: black
    }
</style>
@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Danh sách sản phẩm</h2>
        <a class="btn btn-outline-primary" href="/admin/storeproduct">Thêm danh sản phẩm</a>
    </div>
    @if (Session::exists('thongbao'))
        <h4 class="alert alert-info text-center">{{ Session::get('thongbao') }}</h4>
    @endif
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="main-post">
        <div class="row">
            <div class="col-xl-12">
                <div class="list-post">
                    <table class="table table-striped border align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">
                                    <a
                                        href="{{ route('product', ['sort_by' => 'name', 'sort_order' => $sort_by == 'name' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                                        Tên sản phẩm
                                        @if ($sort_by == 'name')
                                            @if ($sort_order == 'asc')
                                                <span>&uarr;</span>
                                            @else
                                                <span>&darr;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">
                                    <a
                                        href="{{ route('product', ['sort_by' => 'price', 'sort_order' => $sort_by == 'price' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                                        Giá
                                        @if ($sort_by == 'price')
                                            @if ($sort_order == 'asc')
                                                <span>&uarr;</span>
                                            @else
                                                <span>&darr;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" style="width: 100px">
                                    <a
                                        href="{{ route('product', ['sort_by' => 'in_stock', 'sort_order' => $sort_by == 'in_stock' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                                        Tồn kho
                                        @if ($sort_by == 'in_stock')
                                            @if ($sort_order == 'asc')
                                                <span>&uarr;</span>
                                            @else
                                                <span>&darr;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>
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
                                    <td>{{ $item->hide == 1 ? 'Đang ẩn' : 'Đang hiện' }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                            href="/admin/editproduct{{ $item->id_product }}">Sửa</a>

                                        <button type="button" class="btn btn-danger btn-sm delete-button"
                                            data-id="{{ $item->id_product }}">
                                            Xóa
                                        </button>
                                        <form id="delete-form-{{ $item->id_product }}"
                                            action="{{ route('destroyproduct', ['id' => $item->id_product]) }}"
                                            method="GET" style="display: none;">
                                            {{-- @csrf --}}
                                            @method('DELETE')
                                        </form>
                                        {{-- <form class="d-inline"
                                            action="{{ route('destroyproduct', ['id' => $item->id_product]) }}"
                                            method="GET">
                                            <button type='submit' onclick="return confirm('Xóa hả')"
                                                class="btn btn-danger btn-sm">
                                                Xóa
                                            </button>
                                        </form> --}}
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const formId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Bạn có chắc chắn không?',
                    text: "Bạn sẽ không thể hoàn tác điều này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${formId}`).submit();
                    }
                });
            });
        });
    });
</script>
