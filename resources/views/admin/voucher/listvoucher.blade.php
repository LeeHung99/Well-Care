@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Các ưu đãi</h2>
        <a class="btn btn-outline-primary" href="/admin/createvoucher">Thêm ưu đãi</a>
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
                    <table class="table table-striped border text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên voucher</th>
                                <th scope="col">Mã giảm giá</th>
                                <th scope="col">Giảm giá</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $index => $voucher)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $voucher->name }}</td>
                                    <td>{{ $voucher->code }}</td>
                                    <td>{{ $voucher->number }}</td>
                                    <td>
                                        {{ $voucher->status == 0 ? '%' : 'Số tiền'}}
                                    </td>
                                    <td>{{ $voucher->count_voucher }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="/admin/editvoucher{{$voucher->id_voucher}}">Sửa</a>
                                        <form class="d-inline" action="/admin/destroyvoucher{{$voucher->id_voucher}}" method="POST">
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
                    {{ $vouchers->onEachSide(3)->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
