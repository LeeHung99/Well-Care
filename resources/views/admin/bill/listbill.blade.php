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
                                <th scope="col">Tình Trạng Đơn Hàng</th>
                                <th scope="col">Tình Trạng Thanh Toán</th>
                                <th scope="col"><i class="fa-regular fa-clock"></i> Ngày mua</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bill as $index => $item)
                                <tr>
                                    <th scope="row">#{{ $item->id_bill }}</th>
                                    <td>{{ $item->user_name }}</td>
                                    <td>
                                        @php
                                            foreach ($transport_status as $key2 => $status2) {
                                                if ($item->transport_status == $key2) {
                                                    echo $status2;
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            if ($item->transport_status == 2) {
                                                echo $payment_status[1];
                                            } else {
                                                foreach ($payment_status as $key1 => $status1) {
                                                    if ($item->payment_status == $key1) {
                                                        echo $status1;
                                                    }
                                                }
                                            }

                                        @endphp
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                            href="/admin/billdetail{{ $item->id_bill }}/{{ $item->id_user }}"><i
                                                class="fa-regular fa-eye"></i> Chi tiết</a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $bill->onEachSide(3)->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
