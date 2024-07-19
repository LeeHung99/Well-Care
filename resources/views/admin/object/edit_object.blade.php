@extends('admin/layout_admin/layout')
@section('noidungchinh')
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Sửa đối tượng</h2>
    </div>
    <form enctype="multipart/form-data" class="m-auto" id="frm" method="post"
        action="/admin/updateobject{{ $data->id_object }}"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class="d-flex">
                    <div class='mb-3 px-2' style="width: 100%">
                        <label><b>Đối tượng</b></label> <span style="color: red">*</span>
                        <input type="text" name="name" value="{{ $data->name }}" class="form-control" />
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card" style="width: 90%;">
                    <div class="d-flex justify-content-between">
                        <div class="card-body">
                            <div class="form-check ms-2">
                                <input class="form-check-input" type="radio" id="flexCheckVisible" value="0"
                                    name="hide" {{ $data->hide == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckVisible">
                                    Hiện
                                </label>
                            </div>
                            <div class="form-check ms-2">
                                <input class="form-check-input" type="radio" id="flexCheckHidden" value="1"
                                    name="hide" {{ $data->hide == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckHidden">
                                    Ẩn
                                </label>
                            </div>
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
