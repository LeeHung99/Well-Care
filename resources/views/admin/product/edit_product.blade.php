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
        <h2 class="me-3">Sửa sản phẩm</h2>
    </div>
    <form enctype="multipart/form-data" class="m-auto" id="frm" method="post"
        action="/admin/updateproduct{{ $data->id_product }}"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class="d-flex">
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Tên sản phẩm</b></label> <span style="color: red">*</span>
                        <input type="text" name="name" value="{{ $data->name }}" class="form-control" />
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Giá</b></label> <span style="color: red">*</span>
                        <input type="text" name="price" value="{{ $data->price }}"
                            class="form-control" />
                        @if ($errors->has('price'))
                            <span class="text-danger">{{ $errors->first('price') }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex">
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Tồn kho</b></label> <span style="color: red">*</span>
                        <input type="text" name="in_stock" value="{{ $data->in_stock }}" class="form-control" />
                        @if ($errors->has('in_stock'))
                            <span class="text-danger">{{ $errors->first('in_stock') }}</span>
                        @endif
                    </div>
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Thương hiệu</b></label> <span style="color: red">*</span>
                        <input type="text" name="brand" value="{{ $data->brand }}" class="form-control" />
                        @if ($errors->has('brand'))
                            <span class="text-danger">{{ $errors->first('brand') }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex">
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Phần trăm giảm giá</b></label>
                        <input type="text" name="sale" value="{{ $data->sale }}" class="form-control" />
                        @if ($errors->has('sale'))
                            <span class="text-danger">{{ $errors->first('sale') }}</span>
                        @endif
                    </div>
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Triệu chứng</b></label>
                        <input type="text" name="symptom" value="{{ $data->symptom }}" class="form-control" />
                        @if ($errors->has('symptom'))
                            <span class="text-danger">{{ $errors->first('symptom') }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex">
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Xuất xứ</b></label> <span style="color: red">*</span>
                        <input type="text" name="origin" value="{{ $data->origin }}" class="form-control" />
                    </div>
                    <div class='mb-3 px-2' style="width: 50%">
                        <label><b>Dạng (hộp, chai)</b></label> <span style="color: red">*</span>
                        <input type="text" name="unit" value="{{ $data->unit }}" class="form-control" />
                    </div>
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Mô tả ngắn</b></label> <span style="color: red">*</span>
                    <textarea name="short_des" id="short_des" cols="30" rows="10">{{ $data->short_des }}</textarea>
                    @if ($errors->has('short_des'))
                        <span class="text-danger">{{ $errors->first('short_des') }}</span>
                    @endif
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Mô tả sản phẩm</b></label> <span style="color: red">*</span>
                    <textarea name="description" id="description" cols="30" rows="10">{{ $data->description }}</textarea>
                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-xl-4" style="display: flex; flex-direction: column; justify-content: space-between">
                <div class="card" style="width: 90%;">
                    <div class="card-header">
                        <label><b>Danh mục</b> <span style="color: red">*</span></label> 
                        <select name="category" class="form-control">
                            @foreach ($third_cate as $category)
                                <option value="{{ $category->id_third_category }}" name="category"
                                    {{ $data->Third_categories->id_third_category == $category->id_third_category ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card" style="width: 90%;">
                    <div class="card-header">
                        <label><b>Hình ảnh</b> <span style="color: red">*</span></label> 
                        <input type="file" name="avatar" value="{{ $data->avatar }}" accept=".png,.jpg,.jpeg,.webp"
                            class="input_file" class="form-control" />
                        {{-- <img src="{{ asset($data->avatar) }}" alt=""> --}}
                        <div id="preview-containerr">

                            <div class="img-containerr" data-field="">
                                <img src="{{ asset('/images/product/' . $data->avatar) }}" alt="Image"
                                    class="preview-imgg">
                                {{-- <button type="button" class="delete-btn"
                                    onclick="deleteDBImage(this, '{{ $image_field }}', {{ $image->id }})">X</button> --}}
                            </div>
                        </div>
                        @if ($errors->has('avatar'))
                            <span class="text-danger">{{ $errors->first('avatar') }}</span>
                        @endif
                    </div>
                </div>

                <div class="card" style="width: 90%;">
                    <div class="card-header">
                        <label><b>Hình ảnh phụ (tối đa 4 hình ảnh)</b></label>
                        <input type="file" multiple id="fileInput" name=""
                            class="form-control upload__inputfile">
                        <div id="preview-container" style="margin-top: 10px">
                            @foreach ($image_product as $index => $image)
                                @foreach (['image_1', 'image_2', 'image_3', 'image_4'] as $image_field)
                                    @if ($image->$image_field)
                                        {{-- @dd($image_field); --}}
                                        <div class="img-container" data-field="{{ $image_field }}">
                                            <img src="{{ asset('images/product_sub/' . $image->$image_field) }}"
                                                alt="Image" class="preview-img" style="max-width: 100px;">
                                            <button type="button" class="delete-btn"
                                                onclick="deleteDBImage(this, '{{ $image_field }}', {{ $image->id }})">X</button>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                        @if ($errors->has('avatar_sub'))
                            <span class="text-danger">{{ $errors->first('avatar_sub') }}</span>
                        @endif
                        <input type="hidden" id="deletedImages" name="deletedImages" value="">
                    </div>
                </div>

                <div class="card" style="width: 90%;">
                    <div class='mb-3 px-2' style="margin-top: 10px">
                        <label><b>Đối tượng sử dụng</b></label> <span style="color: red">*</span>
                        <select name="obj" class="form-control">
                            @foreach ($objects as $obj)
                                <option value="{{ $obj->id_object }}"
                                    {{ $data->Objects->id_object == $obj->id_object ? 'selected' : '' }}>
                                    {{ $obj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class='mb-3 px-2'>
                        <label><b>Bệnh</b></label> <span style="color: red">*</span>
                        <select name="sick" class="form-control">
                            @foreach ($sicks as $s)
                                <option value="{{ $s->id_sick }}"
                                    {{ $data->Sick->id_sick == $s->id_sick ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="card-body">
                            <div class="form-check ms-2">
                                <input class="form-check-input" type="radio" id="flexCheckDefault" value="1"
                                    name="hide" {{ $data->hide == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Hiện
                                </label>
                            </div>
                            <div class="form-check ms-2">
                                <input class="form-check-input" type="radio" id="flexCheckDefault" value="0"
                                    name="hide" {{ $data->hide == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Ẩn
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-check ms-2">
                                <input class="form-check-input" type="radio" id="flexCheckDefault" value="0"
                                    name="hot" {{ $data->hot == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Bình thường
                                </label>
                            </div>
                            <div class="form-check ms-2">
                                <input class="form-check-input" type="radio" id="flexCheckDefault" value="1"
                                    name="hot" {{ $data->hot == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Nổi bật
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
{{-- <script src="{{ asset('js/ckeditor-upload-adapter.js') }}"></script> --}}
@section('js-custom')
    <script>
        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            var editorConfig = {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                // ... các cấu hình khác nếu cần ...
            };

            var editorElement = document.querySelector('#description');
            if (editorElement) {
                ClassicEditor
                    .create(editorElement, editorConfig)
                    .catch(error => {
                        console.error(error);
                    });
            }

            var shortDesElement = document.querySelector('#short_des');
            if (shortDesElement) {
                ClassicEditor
                    .create(shortDesElement, editorConfig)
                    .catch(error => {
                        console.error(error);
                    });
            }

            var editorElement = document.querySelector('#editor');
            if (editorElement) {
                ClassicEditor
                    .create(editorElement, editorConfig)
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    </script>
@endsection
<style>
    .card {
        margin-bottom: 20px;
    }

    #preview-container {
        display: grid;
        grid-template-columns: 50% 50%;
        align-items: center;
    }

    .img-containerr {
        margin-top: 10px;
    }
    .card-header {
        display: flex;
        flex-direction: column;
    }

    .preview-img {
        max-width: 100px;
        margin: 0 1em 1em 0;
        padding: 0.5em;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .preview-imgg {
        max-width: 100px;
        margin: 0 1em 1em 0;
        padding: 0.5em;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const previewContainer = document.getElementById('preview-container');
        const fileInput = document.querySelector('.upload__inputfile');
        const deletedImagesInput = document.getElementById('deletedImages');
        const MAX_FILES = 4;
        // const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
        // const ALLOWED_TYPES = ['image/png', 'image/jpeg', 'image/webp'];

        let currentFileCount = previewContainer.querySelectorAll('.img-container').length;

        function updateFileInput() {
            fileInput.disabled = currentFileCount >= MAX_FILES;
        }

        updateFileInput();

        window.deleteDBImage = function(button, imageField, imageId) {
            const container = button.closest('.img-container');
            container.remove();
            currentFileCount--;
            updateFileInput();

            const deletedImages = deletedImagesInput.value ? deletedImagesInput.value.split(',') : [];
            deletedImages.push(`${imageId}:${imageField}`);
            deletedImagesInput.value = deletedImages.join(',');
        }

        function createImagePreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('img-container');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = file.name;
                img.classList.add('preview-img');
                img.style.maxWidth = '100px';

                const deleteBtn = document.createElement('button');
                deleteBtn.innerText = 'X';
                deleteBtn.classList.add('delete-btn');
                deleteBtn.type = 'button';
                deleteBtn.addEventListener('click', function() {
                    imgContainer.remove();
                    currentFileCount--;
                    updateFileInput();
                });

                imgContainer.appendChild(img);
                imgContainer.appendChild(deleteBtn);
                previewContainer.appendChild(imgContainer);
            }
            reader.readAsDataURL(file);
        }

        fileInput.addEventListener('change', function(event) {
            const files = Array.from(event.target.files);

            files.forEach(file => {
                // code cũ
                if (currentFileCount < MAX_FILES) {
                    createImagePreview(file);
                    currentFileCount++;
                    updateFileInput();
                }

                // if (ALLOWED_TYPES.includes(file.type) && file.size <= MAX_FILE_SIZE &&
                //     currentFileCount < MAX_FILES) {
                //     createImagePreview(file);
                //     currentFileCount++;
                //     updateFileInput();
                // } else {
                //     alert(
                //         `File ${file.name} không hợp lệ. Chỉ chấp nhận file PNG, JPG, WEBP dưới 2MB.`
                //     );
                // }
            });

            // Clone the FileList to a new input for form submission
            const dataTransfer = new DataTransfer();
            files.forEach(file => dataTransfer.items.add(file));

            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.multiple = true;
            newInput.name = 'avatar_sub[]';
            newInput.style.display = 'none';
            newInput.files = dataTransfer.files;

            this.parentNode.insertBefore(newInput, this.nextSibling);

            this.value = '';
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        // document.querySelectorAll('.upload__inputfile').forEach(function(fileInput) {
        //     const previewContainer = document.getElementById('preview-container');
        //     const fileInputsContainer = document.getElementById('fileInputsContainer');
        //     let fileIndex = 0;
        //     let currentFileCount = 0; // Biến để theo dõi số lượng file hiện tại

        //     fileInput.addEventListener('change', function(event) {
        //         const files = Array.from(event.target.files);

        //         files.forEach(file => {
        //             if (file.type.startsWith('image/')) {
        //                 // Nếu đã đủ 4 file thì thay thế file thứ 4 bằng file mới
        //                 if (currentFileCount >= 4) {
        //                     const lastFileInput = fileInputsContainer.querySelector(
        //                         `input[name^="avatar_sub"]`);
        //                     const lastImage = previewContainer.querySelector(
        //                         '.preview-img:last-child');

        //                     // Tạo một đối tượng DataTransfer và thêm file vào đó
        //                     const dataTransfer = new DataTransfer();
        //                     dataTransfer.items.add(file);
        //                     lastFileInput.files = dataTransfer.files;

        //                     // Thay đổi hình ảnh preview
        //                     const reader = new FileReader();
        //                     reader.onload = function(e) {
        //                         lastImage.src = e.target.result;
        //                         lastImage.alt = file.name;
        //                     }
        //                     reader.readAsDataURL(file);
        //                 } else {
        //                     // Tạo một input file mới
        //                     const newInput = document.createElement('input');
        //                     newInput.type = 'file';
        //                     newInput.className = `images`;
        //                     newInput.name = `avatar_sub[${fileIndex}]`;
        //                     newInput.style.display = 'none';

        //                     // Tạo một đối tượng DataTransfer và thêm file vào đó
        //                     const dataTransfer = new DataTransfer();
        //                     dataTransfer.items.add(file);
        //                     newInput.files = dataTransfer.files;
        //                     fileInputsContainer.appendChild(newInput);
        //                     fileIndex++;
        //                     currentFileCount++; // Tăng số lượng file hiện tại

        //                     // Hiển thị preview
        //                     const reader = new FileReader();
        //                     reader.onload = function(e) {
        //                         const img = document.createElement('img');
        //                         img.src = e.target.result;
        //                         img.alt = file.name;
        //                         img.classList.add('preview-img');
        //                         img.style.maxWidth = '100px';
        //                         img.style.marginRight = '10px';

        //                         // Thêm nút xóa
        //                         const deleteBtn = document.createElement('button');
        //                         deleteBtn.innerText = 'X';
        //                         deleteBtn.classList.add('delete-btn');
        //                         deleteBtn.style.marginLeft = '10px';
        //                         deleteBtn.addEventListener('click', function() {
        //                             // Xóa hình ảnh và input file tương ứng
        //                             img.remove();
        //                             deleteBtn.remove();
        //                             newInput.remove();
        //                             currentFileCount--;

        //                             // Giảm giá trị fileIndex nếu cần thiết
        //                             if (fileIndex > 0) {
        //                                 fileIndex--;
        //                             }

        //                             // Nếu currentFileCount dưới 4, kích hoạt lại input
        //                             if (currentFileCount < 4) {
        //                                 fileInput.disabled = false;
        //                             }
        //                         });

        //                         const imgContainer = document.createElement('div');
        //                         imgContainer.appendChild(img);
        //                         imgContainer.appendChild(deleteBtn);
        //                         previewContainer.appendChild(imgContainer);
        //                     }
        //                     reader.readAsDataURL(file);
        //                 }
        //             }
        //         });

        //         // Kiểm tra lại nếu đã đủ 4 file thì tắt input đi
        //         if (currentFileCount >= 4) {
        //             fileInput.disabled = true;
        //         }

        //         // Reset input gốc
        //         this.value = '';
        //     });
        // });


        document.querySelectorAll('.input_file').forEach(function(fileInput) {
            fileInput.addEventListener('change', function(event) {
                const previewContainer = document.getElementById('preview-containerr');
                previewContainer.innerHTML = ''; // Xóa nội dung cũ của previewContainer

                const files = event.target.files;
                if (files.length > 0) {
                    const file = files[0]; // Chỉ lấy file đầu tiên trong trường hợp này
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = file.name;
                            img.classList.add('preview-imgg');
                            img.style.maxWidth = '100px';
                            img.style.marginRight = '10px';
                            previewContainer.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        });

    });
</script>
