<style>
    textarea {
        width: 100%;
        border: var(--bs-border-width) solid var(--bs-border-color);
        border-radius: var(--bs-border-radius);
        /* height: 200px; */
    }

    textarea:focus {
        outline: none !important;
        /* border: 1px solid red; */
        /* box-shadow: 0 0 10px #719ECE; */
    }

    .card-header {
        padding: 10px;
    }
</style>
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
        <h2 class="me-3">Thêm bệnh</h2>
    </div>
    <form enctype="multipart/form-data" class="m-auto" id="frm" method="post" action="/admin/storesick"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class="d-flex">
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Tên bệnh</b></label> <span style="color: red">*</span>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" />
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Triệu chứng</b></label> <span style="color: red">*</span>
                        <input type="text" name="symptom" value="{{ old('symptom') }}" class="form-control" />
                        @if ($errors->has('symptom'))
                            <span class="text-danger">{{ $errors->first('symptom') }}</span>
                        @endif
                    </div>
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Mô tả</b></label> <span style="color: red">*</span>
                    <textarea name="description" id="description" cols="30" rows="10"></textarea>
                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card" style="width: 90%;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div class="card-body">
                                <div class="form-check ms-2">
                                    <input class="form-check-input" checked type="radio" id="flexCheckHidden"
                                        value="0" name="hide">
                                    <label class="form-check-label" for="flexCheckHidden">
                                        Ẩn
                                    </label>
                                </div>
                                <div class="form-check ms-2">
                                    <input class="form-check-input" type="radio" id="flexCheckVisible" value="1"
                                        name="hide">
                                    <label class="form-check-label" for="flexCheckVisible">
                                        Hiện
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class='mt-3 px-2 mb-3'>
                        <button type="submit" class="btn btn-primary py-2 px-5 border-0">Lưu</button>
                    </div>
                </div>
            </div>
    </form>
@endsection
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script> --}}

@section('js-custom')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editorElement = document.querySelector('#description');
            if (editorElement) {
                ClassicEditor
                    .create(editorElement)
                    .catch(error => {
                        console.error(error);
                    });
            }

            // Khởi tạo CKEditor cho #short_des nếu cần
            // var shortDesElement = document.querySelector('#short_des');
            // if (shortDesElement) {
            //     ClassicEditor
            //         .create(shortDesElement)
            //         .catch(error => {
            //             console.error(error);
            //         });
            // }
        });
    </script>
@endsection
