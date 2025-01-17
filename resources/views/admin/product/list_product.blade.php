<style>
    .h1{
        text-align: center;
    }
    thead a {
        color: black
    }

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

    /* Đặt style cho select và input */
    form select,
    form input[type="number"] {
        padding: 8px 12px;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    form select:focus,
    form input[type="number"]:focus {
        border-color: #007bff;
        outline: none;
    }

    /* Style cho button */
    form .submit-select {
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    form .submit-select:hover {
        background-color: #0056b3;
    }

    /* Tạo khoảng cách đều giữa các thành phần trong form */
    form {
        display: flex;
        align-items: center;
        gap: 10px;
        /* Khoảng cách giữa các input/select/button */
    }

    /* Căn chỉnh form */
    form select,
    form input[type="number"],
    form .submit-select {
        min-width: 150px;
    }

    /* Điều chỉnh style của option trong select */
    form select option {
        padding: 8px;
        font-size: 14px;
    }
</style>
{{-- @dd($data) --}}
@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="d-flex justify-content-between align-items-end" style="width: 90%">
        <div class="title_post d-flex my-3">
            <h2 class="me-3">Danh sách sản phẩm</h2>
            <a class="btn btn-outline-primary" href="/admin/storeproduct">Thêm sản phẩm</a>

        </div>
        <div class="search-container">
            <div class="search-box">
                <form action="{{ route('product') }}" method="GET">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm...">
                    <button type="submit">
                        <img src="https://img.icons8.com/ios-glyphs/30/000000/search--v1.png" alt="Tìm kiếm">
                    </button>
                </form>
            </div>
        </div>
    </div>
    <form method="GET" action="{{ route('product') }}">
        <select name="category">
            <option value="">Chọn danh mục</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id_third_category }}"
                    {{ request('category') == $category->id_third_category ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <input type="number" name="price_min" placeholder="Giá tối thiểu" value="{{ request('price_min') }}">
        <input type="number" name="price_max" placeholder="Giá tối đa" value="{{ request('price_max') }}">


        <select name="stock">
            <option value="">Tồn kho</option>
            <option value="0" {{ request('stock') == 0 ? 'selected' : '' }}>Hết hàng</option>
            <option value="1" {{ request('stock') == 1 ? 'selected' : '' }}>Sắp hết hàng</option>
        </select>

        <button type="submit" class="submit-select">Lọc</button>
    </form>

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
    @if (is_string($data))
        <h1 class="h1">{{ $data }}</h1>
    @else
        <div class="main-post">
            <div class="row">
                <div class="col-xl-12">
                    <div class="list-post">
                        <table class="table table-striped border align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">
                                        Tên sản phẩm
                                        {{-- <a
                                            href="{{ route('product', ['sort_by' => 'name', 'sort_order' => $sort_by == 'name' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                                            Tên sản phẩm
                                            @if ($sort_by == 'name')
                                                @if ($sort_order == 'asc')
                                                    <span>&uarr;</span>
                                                @else
                                                    <span>&darr;</span>
                                                @endif
                                            @endif
                                        </a> --}}
                                    </th>
                                    <th scope="col">Danh mục</th>
                                    <th scope="col">Hình ảnh</th>
                                    <th scope="col" style="width: 75px">
                                        {{-- <a
                                            href="{{ route('product', ['sort_by' => 'price', 'sort_order' => $sort_by == 'price' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                                            Giá
                                            @if ($sort_by == 'price')
                                                @if ($sort_order == 'asc')
                                                    <span>&uarr;</span>
                                                @else
                                                    <span>&darr;</span>
                                                @endif
                                            @endif
                                        </a> --}}
                                        Giá
                                    </th>
                                    <th scope="col" style="width: 100px">
                                        {{-- <a
                                            href="{{ route('product', ['sort_by' => 'in_stock', 'sort_order' => $sort_by == 'in_stock' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">
                                            Tồn kho
                                            @if ($sort_by == 'in_stock')
                                                @if ($sort_order == 'asc')
                                                    <span>&uarr;</span>
                                                @else
                                                    <span>&darr;</span>
                                                @endif
                                            @endif
                                        </a> --}}
                                        Tồn kho
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
                                        <td><img src="https://cms.wellcarepharmacy.shop/images/product/{{ $item->avatar }}"
                                                alt="" width="150px" height="75px"></td>
                                        <td>{{ number_format($item->price, 0, ',', '.') . ' ₫' }}</td>
                                        <td>{{ $item->in_stock }}</td>
                                        <td>{{ $item->brand }}</td>
                                        <td>{{ $item->hide == 0 ? 'Đang ẩn' : 'Đang hiện' }}</td>
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
    @endif

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
