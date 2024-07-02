@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_banner d-flex my-3">
        <h2 class="me-3">Banner</h2>
        <a class="btn btn-outline-primary" href="/admin/createbanner">Thêm hình ảnh</a>
    </div>
    @if (Session::exists('thongbao'))
        <h4 class="alert alert-info text-center">{{ Session::get('thongbao') }}</h4>
    @endif
    <div class="main-banner">
        <div class="list-post">
            <table class="table table-striped border align-middle text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Hình ảnh</th>
                        <th scope="col">Vị trí</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logo as $index => $item)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td><img src="http://127.0.0.1:8000/images/banner/{{$item->logo}}" alt="" width="300px" height="150px"></td>
                            <td>
                               <b> {{$position[$item->position]}}</b>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="/admin/editbanner{{$item->id_logo}}">Sửa</a>
                                <form class="d-inline" action="/admin/destroybanner{{$item->id_logo}}" method="POST">
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
            {{ $banner->onEachSide(3)->links() }}
        </div>
    </div>
@endsection
