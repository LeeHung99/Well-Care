@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Thêm danh mục cấp 3</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/storethirdcategory" enctype="multipart/form-data"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class='mb-3 px-2'>
                    <label><b>Tên Danh mục cấp 3</b></label>
                    <input type="text" name="name" class="form-control" />
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="card-header mb-3">
                    <strong>Ảnh đại diện</strong>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="file" name="image" class="form-control" id="imgInp">
                    </div>

                    <img id="blah" src="" alt="your image" width="70%" />
                </div>
                <script>
                    imgInp.onchange = evt => {
                        const [file] = imgInp.files
                        if (file) {
                            blah.src = URL.createObjectURL(file)
                        }
                    }
                </script>
                @if ($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                @endif
                <div class='mb-3 px-2'>
                    <select class="form-select" name="id_cate" aria-label="Default select example" required>
                        @foreach ($secate as $key => $item)
                            <option value="{{ $item->id_se_category }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Thứ tự</b></label>
                    <input type="number" name="order" class="form-control" />
                    @if ($errors->has('order'))
                        <span class="text-danger">{{ $errors->first('order') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card mt-3" style="width: 90%;">
                    <div class="card-header">
                        <strong>Trạng thái</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="0"
                                name="hide" checked>
                            <label class="form-check-label" for="flexCheckDefault">
                                Ẩn
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="1"
                                name="hide">
                            <label class="form-check-label" for="flexCheckDefault">
                                Hiện
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card mt-3" style="width: 90%;">
                    <div class="card-header">
                        <strong>Nổi bật</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="0"
                                name="hot" checked>
                            <label class="form-check-label" for="flexCheckDefault">
                                Bình Thường
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="1"
                                name="hot">
                            <label class="form-check-label" for="flexCheckDefault">
                                Nổi Bật
                            </label>
                        </div>
                    </div>
                </div>
                <div class='mt-3 px-2'>
                    <button type="submit" class="btn btn-primary py-2 px-5 border-0">Đăng</button>
                </div>
            </div>
        </div>
    </form>
@endsection
