@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Thêm danh mục cấp 2</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/storesecategory" enctype="multipart/form-data"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class='mb-3 px-2'>
                    <label><b>Tên Danh mục cấp 2</b></label>
                    <input type="text" name="name" class="form-control" required />
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
            </div>
            <div class="col-xl-4">
                <div class="card" style="width: 90%;">
                    <div class="card-header">
                        <strong>Danh mục cấp 1</strong>
                    </div>
                    <div class="card-body">
                        @foreach ($cate as $key => $item)
                            <div class="form-check ms-2">
                                <input class="form-check-input" type="radio" 
                                    id="flexCheckDefault"
                                    value="{{$item->id_category}}" name="id_cate"required>
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{$item->name}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card mt-3" style="width: 90%;">
                    <div class="card-header">
                        <strong>Trạng thái</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="0"
                                name="hide" required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Ẩn
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="1"
                                name="hide" required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Hiện
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
