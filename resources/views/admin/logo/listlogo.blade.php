@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_banner d-flex my-3">
        <h2 class="me-3">Logo</h2>
        {{-- <a class="btn btn-outline-primary" href="/admin/createbanner">Thêm hình ảnh</a> --}}
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
                            <td ><img src="http://127.0.0.1:8000/images/logo/{{$item->image}}" alt="" width="150px" height="150px"></td>
                            <td>
                               <b> {{$position[$item->position]}}</b>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="/admin/editlogo{{$item->id_logo}}">Sửa</a>
                            </td>
                        </tr>
                    @endforeach
                        
                </tbody>
            </table>
        </div>
    </div>
@endsection
