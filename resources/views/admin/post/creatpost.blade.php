@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Thêm bài viết</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/storepost" enctype="multipart/form-data"> @csrf
        <div class="row">
            <div class="col-xl-9">
                <div class='mb-3 px-2'>
                    <input name="title" type="text" class="form-control" placeholder="Thêm tiêu đề bài viết"
                        value="{{ old('title') }}">
                    <b class="text-danger ps-2"> @error('title')
                            {{ $message }}
                        @enderror </b>
                </div>
                <div class='mb-3 px-2'>
                    <label> Mô tả ngắn</label>
                    <textarea name="shortdes" rows="3" class="form-control"></textarea>
                </div>
                <div class='mb-3 px-2'>
                    <label> Nội dung</label>
                    <textarea name="des" rows="6" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card" style="width: 90%;">
                    <div class="card-header">
                        <strong>Chuyên mục</strong>
                    </div>
                    <div class="card-body">
                        @foreach ($catepost as $index => $cate)
                            <div class="form-check ms-2">
                                <input class="form-check-input" type="radio"
                                    id="flexCheckDefault{{ $cate->id_article_category }}"
                                    value="{{ $cate->id_article_category }}" name="id_cate">
                                <label class="form-check-label" for="flexCheckDefault{{ $cate->id_article_category }}">
                                    {{ $cate->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <b class="text-danger ps-4 pb-2"> @error('id_cate')
                            {{ $message }}
                        @enderror </b>
                </div>
                <div class="card mt-3" style="width: 90%;">
                    <div class="card-header">
                        <strong>Ảnh đại diện</strong>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input type="file" name="avatar" class="form-control" id="imgInp">
                        </div>
                        <img id="blah" src="#" alt="your image" width="100px" height="100px" />
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
                <div class='mt-3 px-2'>
                    <button type="submit" class="btn btn-primary py-2 px-5 border-0">Đăng</button>
                </div>
            </div>
        </div>
    </form>
@endsection
