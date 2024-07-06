@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Đơn Hàng</h2>
        {{-- <a class="btn btn-outline-primary" href="/admin/createpost">Thêm bài viết</a> --}}
    </div>
    @if (Session::exists('thongbao'))
        <h4 class="alert alert-info text-center">{{ Session::get('thongbao') }}</h4>
    @endif
    <div class="main-post">
        <div class="row">
            <div class="col-xl-12">
                {{-- <div class="filter-post">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Hành động hàng loạt</option>
                        <option value="1">Xóa</option>
                    </select>
                </div> --}}
                <div class="list-post">
                    <table class="table table-striped border">
                        <thead>
                            <tr>
                                <th scope="col">#Mã Đơn hàng</th>
                                <th scope="col">Người Mua</th>
                                <th scope="col">Tình Trạng</th>
                                <th scope="col">Tổng Tiền</th>
                                <th scope="col"><i class="fa-regular fa-clock"></i> Ngày mua</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bill as $index => $item)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->user_name }}</td>
                                    <td>{{ $post->catename }}</td>
                                    <td>0</td>
                                    <td>{{ $post->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="/admin/editpost{{$post->id_post}}">Sửa</a>
                                        <form class="d-inline" action="/admin/destroypost{{$post->id_post}}" method="POST">
                                            @csrf
                                            <button type='submit' onclick="return confirm('Xóa hả')"
                                                class="btn btn-danger btn-sm">
                                                Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                                
                        </tbody>
                    </table>
                    {{ $posts->onEachSide(3)->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
