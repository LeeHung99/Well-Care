@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Thêm Ưu đãi</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/storevoucher" enctype="multipart/form-data"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class='mb-3 px-2'>
                    <label><b>Tên voucher</b></label>
                    <input type="text" name="name" class="form-control"></input>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Mã giảm giá</b></label>
                    <input type="text" name="code" class="form-control"></input>
                    @if ($errors->has('code'))
                        <span class="text-danger">{{ $errors->first('code') }}</span>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Giảm giá</b></label>
                    <input type="number" name="number" class="form-control"></input>
                    @if ($errors->has('number'))
                        <span class="text-danger">{{ $errors->first('number') }}</span>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Số lượng</b></label>
                    <input type="number" name="count_voucher" class="form-control"></input>
                    @if ($errors->has('count_voucher'))
                        <span class="text-danger">{{ $errors->first('count_voucher') }}</span>
                    @endif
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
                                name="status" checked>
                            <label class="form-check-label" for="flexCheckDefault">
                                Theo phần trăm (%)
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault1" value="1"
                                name="status">
                            <label class="form-check-label" for="flexCheckDefault1">
                                Theo giá tiền (VNĐ)
                            </label>
                        </div>
                    </div>
                    <b class="text-danger ps-4 pb-2"> @error('id_cate')
                            {{ $message }}
                        @enderror </b>
                </div>
                <div class='mt-3 px-2'>
                    <button type="submit" class="btn btn-primary py-2 px-5 border-0">Đăng</button>
                </div>
            </div>
        </div>
    </form>
@endsection
