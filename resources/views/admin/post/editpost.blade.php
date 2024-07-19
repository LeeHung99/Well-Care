@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Thêm bài viết</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/updatepost{{$post->id_post}}"> @csrf
        <div class="row">
            <div class="col-xl-9">
                <div class='mb-3 px-2'>
                    <input name="title" type="text" class="form-control" placeholder="Thêm tiêu đề bài viết"
                        value="{{$post->title}}">
                    <b class="text-danger ps-2"> @error('title')
                            {{ $message }}
                        @enderror </b>
                </div>
                <div class='mb-3 px-2'>
                    <label> Mô tả ngắn</label>
                    <textarea name="shortdes" id="shortdes" rows="3" class="form-control">{{$post->short_des}}</textarea>
                </div>
                <div class='mb-3 px-2'>
                    <label> Nội dung</label>
                    <textarea name="des" id="des" rows="6" class="form-control">{{$post->description}}</textarea>
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
                                    value="{{ $cate->id_article_category }}" name="id_cate" {{$cate->id_article_category==$post->id_article_category? "checked":""}}>
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
                            <input type="file" class="form-control" id="imgInp" name="avatar" value="{{$post->avatar}}">
                        </div>
                        <img id="blah" src="http://127.0.0.1:8000/images/post/{{$post->avatar}}" alt="your image" width="90%" />
                    </div>
                </div>
                <div class='mt-3 px-2'>
                    <button type="submit" class="btn btn-primary py-2 px-5 border-0">Cập nhật</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('js-custom')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editorElement = document.querySelector('#shortdes');
            if (editorElement) {
                ClassicEditor
                    .create(editorElement)
                    .catch(error => {
                        console.error(error);
                    });
            }

            // Khởi tạo CKEditor cho #short_des nếu cần
            var shortDesElement = document.querySelector('#des');
            if (shortDesElement) {
                ClassicEditor
                    .create(shortDesElement)
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    </script>
@endsection