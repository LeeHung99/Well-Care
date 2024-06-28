@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Bài viết</h2>
        <button class="btn btn-outline-primary">Thêm bài viết</button>
    </div>
    <div class="main-post">
        <div class="row">
            <div class="col-xl-12">
                <div class="filter-post">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Hành động hàng loạt</option>
                        <option value="1">Xóa</option>
                    </select>
                </div>
                <div class="list-post">
                    <table class="table table-striped border">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Tác giả</th>
                                <th scope="col">Chuyên mục</th>
                                <th scope="col">Bình luận</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $index => $post)
                                <tr>
                                    <th scope="row">1</th>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->user_name }}</td>
                                    <td>{{ $post->catename}}</td>
                                    <td>0</td>
                                    <td>{{$post->updated_at}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="">Sửa</a>     
                                        <form class="d-inline" action="" method="POST">
                                            @csrf @method('DELETE')
                                            <button type='submit' onclick="return confirm('Xóa hả')" class="btn btn-danger btn-sm">
                                                Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6"> {{$posts->onEachSide(3)->links() }} </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
