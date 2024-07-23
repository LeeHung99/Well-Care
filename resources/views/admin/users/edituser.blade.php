@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Chỉnh sửa hồ sơ</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/updateusers{{$nv->id_user}}" enctype="multipart/form-data"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class='mb-3 px-2'>
                    <label><b>Tên thành viên</b></label>
                    <input type="text" name="name" class="form-control" value="{{ $nv->name }}" />
                
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Email</b></label>
                    <input type="email" name="email" class="form-control" value="{{ $nv->email }}" />
                 
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Mật khẩu</b></label>
                    <input type="password" name="password" value="{{ $nv->password }}" class="form-control" />
                 
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Số điện thoại</b></label>
                    <input type="text" name="phone" value="{{ $nv->phone }}" class="form-control"  />
                  
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Địa chỉ</b></label>
                    <input type="text" name="address" value="{{ $nv->address }}" class="form-control"  />
                
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Ngày sinh</b></label>
                    <input type="date" name="date" value="{{ $nv->date }}" class="form-control"  />
                  
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Chức vụ</b></label>
                    <select class="form-select" name="role" aria-label="Default select example">
                        @foreach ($role as $key => $item)
                            <option value="{{ $key }}" {{ $nv->role == $key ? 'selected' : '' }}>
                                {{ $item }}</option>
                        @endforeach
                    </select>
                  
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card mt-3" style="width: 90%;">
                    <div class="card-header">
                        <strong>Giới tính</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="0"
                                name="gender" {{ $nv->gender == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexCheckDefault">
                                Nam
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="1"
                                name="gender" {{ $nv->gender == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexCheckDefault">
                                Nữ
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
