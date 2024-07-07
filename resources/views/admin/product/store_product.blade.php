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
        <h2 class="me-3">Thêm sản phẩm</h2>
    </div>
    <form enctype="multipart/form-data" class="m-auto" id="frm" method="post" action="/admin/storeproduct"> @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class='mb-3 px-2'>
                    <label><b>Tên sản phẩm</b></label>
                    <input type="text" name="name" value="" class="form-control" />
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Giá</b></label>
                    <input type="text" name="price" value="" class="form-control" />
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Tồn kho</b></label>
                    <input type="text" name="in_stock" value="" class="form-control" />
                </div>
                <div class='mb-3 px-2'>
                    <label><b>Thương hiệu</b></label>
                    <input type="text" name="brand" value="" class="form-control" />
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card" style="width: 90%;">
                    <div class="card-header">
                        <label><b>Danh mục</b></label>
                        <select name="category" class="form-control">
                            @foreach ($third_cate as $category)
                                <option value="{{ $category->id_third_category }}" name="category">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-header">
                        <label><b>Hình ảnh</b></label>
                        <input type="file" name="avatar" class="input_file" value="" />
                        <div id="preview-containerr" style="margin-top:10px"></div>
                        {{-- <img src="http://127.0.0.1:8000/images/product/{{ $data->avatar }}" alt=""> --}}
                    </div>
                    <div class="card-header">
                        <label><b>Hình ảnh phụ (tối đa 4 hình ảnh)</b></label>
                        <input type="file" multiple="multiple" id="fileInput" data-id="input1"
                            data-append="preview-container" data-max-length="4" data-class="upload__box"
                            accept="image/png, image/gif, image/jpeg" data-max_length="100" name="avatar_sub[]"
                            class="upload__inputfile">
                        <div id="fileInputsContainer"></div>
                        <div id="preview-container" style="margin-top:10px"></div>
                    </div>

                    <div class='mb-3 px-2'>
                        <label><b>Đối tượng sử dụng</b></label>
                        @foreach ($object as $obj)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="obj[]" value="{{ $obj->id_object }}"
                                    id="obj{{ $obj->id_object }}">
                                <label class="form-check-label" for="obj{{ $obj->id_object }}">
                                    {{ $obj->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class='mb-3 px-2'>
                        <label><b>Bệnh</b></label>
                        @foreach ($sick as $s)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sick[]" value="{{ $s->id_sick }}"
                                    id="sick{{ $s->id_sick }}">
                                <label class="form-check-label" for="sick{{ $s->id_sick }}">
                                    {{ $s->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-body">
                        <div class="form-check ms-2">
                            <input class="form-check-input" checked type="radio" id="flexCheckDefault" value="0"
                                name="hide">
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
                <div class='mt-3 px-2'>
                    <button type="submit" class="btn btn-primary py-2 px-5 border-0">Cập nhật</button>
                </div>
            </div>
        </div>
    </form>
@endsection
<style>
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
        document.querySelectorAll('.upload__inputfile').forEach(function(fileInput) {
            const previewContainer = document.getElementById('preview-container');
            const fileInputsContainer = document.getElementById('fileInputsContainer');
            let fileIndex = 0;
            let currentFileCount = 0; // Biến để theo dõi số lượng file hiện tại

            fileInput.addEventListener('change', function(event) {
                const files = Array.from(event.target.files);

                files.forEach(file => {
                    if (file.type.startsWith('image/')) {
                        // Nếu đã đủ 4 file thì thay thế file thứ 4 bằng file mới
                        if (currentFileCount >= 4) {
                            const lastFileInput = fileInputsContainer.querySelector(
                                `input[name="avatar_sub[3]"]`);
                            const lastImage = previewContainer.querySelector(
                                '.preview-img:last-child');

                            // Tạo một đối tượng DataTransfer và thêm file vào đó
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            lastFileInput.files = dataTransfer.files;

                            // Thay đổi hình ảnh preview
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                lastImage.src = e.target.result;
                                lastImage.alt = file.name;
                            }
                            reader.readAsDataURL(file);
                        } else {
                            // Tạo một input file mới
                            const newInput = document.createElement('input');
                            newInput.type = 'file';
                            newInput.className = `images`;
                            newInput.name = `avatar_sub[${fileIndex}]`;
                            newInput.style.display = 'none';

                            // Tạo một đối tượng DataTransfer và thêm file vào đó
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            newInput.files = dataTransfer.files;
                            fileInputsContainer.appendChild(newInput);
                            fileIndex++;
                            currentFileCount++; // Tăng số lượng file hiện tại

                            // Hiển thị preview
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = file.name;
                                img.classList.add('preview-img');
                                img.style.maxWidth = '100px';
                                img.style.marginRight = '10px';

                                // Thêm nút xóa
                                const deleteBtn = document.createElement('button');
                                deleteBtn.innerText = 'X';
                                deleteBtn.classList.add('delete-btn');
                                deleteBtn.style.marginLeft = '10px';
                                deleteBtn.addEventListener('click', function() {
                                    // Xóa hình ảnh và input file tương ứng
                                    img.remove();
                                    deleteBtn.remove();
                                    newInput.remove();
                                    currentFileCount--;

                                    // Giảm giá trị fileIndex nếu cần thiết
                                    if (fileIndex > 0) {
                                        fileIndex--;
                                    }

                                    // Nếu currentFileCount dưới 4, kích hoạt lại input
                                    if (currentFileCount < 4) {
                                        fileInput.disabled = false;
                                    }
                                });

                                const imgContainer = document.createElement('div');
                                imgContainer.appendChild(img);
                                imgContainer.appendChild(deleteBtn);
                                previewContainer.appendChild(imgContainer);
                            }
                            reader.readAsDataURL(file);
                        }
                    }
                });

                // Kiểm tra lại nếu đã đủ 4 file thì tắt input đi
                if (currentFileCount >= 4) {
                    fileInput.disabled = true;
                }

                // Reset input gốc
                this.value = '';
            });
        });

        // document.querySelectorAll('.input_file').forEach(function(fileInput) {
        //     const reasonId = fileInput.id.split('_')[1];
        //     const previewContainer = document.getElementById(`preview-containerr`);
        //     let fileIndex = 0;

        //     fileInput.addEventListener('change', function(event) {
        //         // Xóa nội dung cũ của previewContainer
        //         previewContainer.innerHTML = '';

        //         const files = Array.from(event.target.files);
        //         files.forEach(file => {
        //             if (file.type.startsWith('image/')) {
        //                 // Tạo một input file mới
        //                 // const newInput = document.createElement('input');
        //                 // newInput.type = 'file';
        //                 // newInput.className = `images`;
        //                 // newInput.name = `images[${fileIndex}]`;
        //                 // newInput.style.display = 'none';

        //                 // // Tạo một đối tượng DataTransfer và thêm file vào đó
        //                 // const dataTransfer = new DataTransfer();
        //                 // dataTransfer.items.add(file);
        //                 // newInput.files = dataTransfer.files;
        //                 // fileInputsContainer.appendChild(newInput);
        //                 // fileIndex++;

        //                 // Hiển thị preview
        //                 const reader = new FileReader();
        //                 reader.onload = function(e) {
        //                     const img = document.createElement('img');
        //                     img.src = e.target.result;
        //                     img.alt = file.name;
        //                     img.classList.add('preview-img');
        //                     img.style.maxWidth = '100px';
        //                     img.style.marginRight = '10px';
        //                     previewContainer.appendChild(img);
        //                 }
        //                 reader.readAsDataURL(file);
        //             }
        //         });

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
