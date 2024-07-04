@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Thêm bài viết</h2>
    </div>
    <form class="m-auto" id="frm" method="post" action="/admin/updatecategory{{$category->id_category}}"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class='mb-3 px-2'>
                    <label><b>Tên Danh mục cấp 1</b></label>
                    <input type="text" name="name" value="{{$category->name}}" class="form-control" required />
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
                                name="hide" {{ $category->hide == 0 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Ẩn
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="radio" id="flexCheckDefault" value="1"
                                name="hide" {{ $category->hide == 0 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Hiện
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
