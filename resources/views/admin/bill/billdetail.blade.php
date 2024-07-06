@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex justify-content-between my-3">
        <h2 class="me-3">#{{ $bill->id_bill }} Đơn hàng chi tiết</h2>
        <div class='mb-3 px-2'>
            <form class="m-auto" id="frm" method="post" action="/admin/updatebill{{ $bill->id_bill }}"
                enctype="multipart/form-data"> @csrf
                <select class="form-select" name="transport_status" aria-label="Default select example" required>
                    @foreach ($transport_status as $key => $status)
                        <option value="{{ $key }}" {{$key == $bill->transport_status? "checked":""}}>
                            {{ $status }}</option>
                    @endforeach
                </select>
                <button type='submit' class="btn btn-primary btn-sm mt-2 float-end">
                    Cập nhật
                </button>
            </form>
        </div>
    </div>
    <div class="main">
        <div class="row">
            <div class="col-xl-4">
                <h3>Thông tin khách hàng</h3>
                <p><b>Tên khách hàng:</b> {{ $user->name }}</p>
                <p><b>Số điện thoại:</b> <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a></p>
                <p><b>Địa Chỉ:</b> {{ $user->address }}</p>
                <p><b>Email:</b> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
            </div>
            <div class="col-xl-4">
                <h3>Thông tin thanh toán</h3>
                <p><b>Hình thức thanh toán:</b>
                    @if ($bill->payment_status == 0)
                        Thanh Toán Bằng Tiền Mặt
                    @else
                        Thanh Toán Online
                    @endif
                </p>
                <p><b>Tình trạng:</b>
                    @foreach ($payment_status as $key => $status)
                        @if ($bill->payment_status == $key)
                            {{ $status }}
                        @endif
                    @endforeach
                </p>
                <p><b>Voucher sử dụng:</b>
                    @if ($bill->voucher == null)
                        Không sử dụng
                    @else
                        {{ $bill->voucher }}
                    @endif
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <table class="table table-striped border align-middle text-center">
                    <thead>
                        <tr>
                            <th scope="col">STT</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Ngày Mua</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bill_detail as $index => $item)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td><b>{{ $item->name }}</b></td>
                                <td><img src="http://127.0.0.1:8000/images/product/{{ $item->avatar }}"
                                        alt="Không có hình ảnh" width="100px"></td>
                                <td><b>{{ number_format($item->price) }} VNĐ</b></td>
                                <td>{{ $item->quantity }}</td>
                                <td><b>{{ number_format($item->total_amount) }} VNĐ</b></td>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
