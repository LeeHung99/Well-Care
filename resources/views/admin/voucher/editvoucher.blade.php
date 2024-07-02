@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Chỉnh sửa ưu đãi</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/updatevoucher{{$vouchers->id_voucher}}" enctype="multipart/form-data"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class='mb-3 px-2'>
                    <label><b>Tên voucher</b></label>
                    <input type="text" name="name" value="{{$vouchers->name}}" class="form-control"/>
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Mã giảm giá</b></label>
                    <input type="text" name="code" value="{{$vouchers->code}}" class="form-control"/>
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Giảm giá</b></label>
                    <input type="number" name="number" value="{{$vouchers->number}}" class="form-control"/>
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Số lượng</b></label>
                    <input type="number" name="count_voucher" value="{{$vouchers->count_voucher}}" class="form-control"/>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card" style="width: 90%;">
                    <div class="card-header">
                        <strong>Trạng thái</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="0"
                                name="status" {{ $vouchers->status == 0 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Theo phần trăm (%)
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="1"
                                name="status" {{ $vouchers->status == 1 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Theo giá tiền (VNĐ)
                            </label>
                        </div>
                    </div>
                </div>
                <div class='mt-3 px-2'>
                    <button type="submit" class="btn btn-primary py-2 px-5 border-0">Cập nhật</button>
                </div>
            </div>
        </div>
    </form>
@endsection
