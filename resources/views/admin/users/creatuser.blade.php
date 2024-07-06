@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Thêm thành viên</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/storeusers" enctype="multipart/form-data"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class='mb-3 px-2'>
                    <label><b>Tên thành viên</b></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                    @if ($errors->has('name'))
                        <p class="text-danger"><b>{{ $errors->first('name') }}</b></p>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Email</b></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" />
                    @if ($errors->has('email'))
                        <p class="text-danger"><b>{{ $errors->first('email') }}</b></p>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Mật khẩu</b></label>
                    <input type="password" name="password" class="form-control" />
                    @if ($errors->has('password'))
                        <p class="text-danger"><b>{{ $errors->first('password') }}</b></p>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Số điện thoại</b></label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" />
                    @if ($errors->has('phone'))
                        <p class="text-danger"><b>{{ $errors->first('phone') }}</b></p>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Địa chỉ</b></label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" />
                    @if ($errors->has('address'))
                        <p class="text-danger"><b>{{ $errors->first('address') }}</b></p>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Ngày sinh</b></label>
                    <input type="date" name="date" class="form-control" value="{{ old('date') }}" />
                    @if ($errors->has('date'))
                        <p class="text-danger"><b>{{ $errors->first('date') }}</b></p>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Chức vụ</b></label>
                    <select class="form-select" name="role" aria-label="Default select example" >
                        @foreach ($role as $key => $item)
                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('role'))
                        <p class="text-danger"><b>{{ $errors->first('role') }}</b></p>
                    @endif
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card mt-3" style="width: 90%;">
                    <div class="card-header">
                        <strong>Giới tính</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="0" name="gender" {{ old('gender') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexCheckDefault">
                                Nam
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="1" name="gender" {{ old('gender') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexCheckDefault">
                                Nữ
                            </label>
                        </div>
                        @if ($errors->has('gender'))
                            <p class="text-danger"><b>{{ $errors->first('gender') }}</b></p>
                        @endif
                    </div>
                </div>
                <div class='mt-3 px-2'>
                    <button type="submit" class="btn btn-primary py-2 px-5 border-0">Đăng</button>
                </div>
            </div>
        </div>
    </form>
@endsection
