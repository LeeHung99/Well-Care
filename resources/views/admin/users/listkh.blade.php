@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Danh sách khách hàng</h2>
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
                    <table class="table table-striped border align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên thành viên</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Email</th>
                                <th scope="col">Địa chỉ</th>
                                <th scope="col">Giới tính</th>
                                <th scope="col">Ngày sinh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kh as $index => $item)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>{{  $item->gender}}</td>
                                    <td>{{ $item->date}}</td>
                                </tr>
                            @endforeach
                                
                        </tbody>
                    </table>
                    {{$kh->onEachSide(3)->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
