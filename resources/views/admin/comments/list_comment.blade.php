<style>

</style>
@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="title_post d-flex my-3">
        <h2 class="me-3">Danh sách phản hồi</h2>
    </div>
    @if (Session::exists('thongbao'))
        <h4 class="alert alert-info text-center">{{ Session::get('thongbao') }}</h4>
    @endif
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="main-post">
        <div class="row">
            <div class="col-xl-12">
                <div class="list-post">
                    <table class="table table-striped border align-middle">
                        {{-- @dd($data) --}}
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Người phản hồi</th>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col">Nội dung</th>
                                <th scope="col">Ngày phản hồi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $item)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $item->user['name'] }}</td>
                                    <td class="product-css">{{ $item->product['name'] }}</td>
                                    <td style="width: 65%">{{ $item->content }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete-button"
                                            data-id="{{ $item->id_comment }}">
                                            Xóa
                                        </button>
                                        <form id="delete-form-{{ $item->id_comment }}"
                                            action="{{ route('destroycomment', ['id' => $item->id_comment]) }}"
                                            method="GET" style="display: none;">
                                            {{-- @csrf --}}
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const formId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Bạn có chắc chắn không?',
                    text: "Bạn sẽ không thể hoàn tác điều này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${formId}`).submit();
                    }
                });
            });
        });
    });
</script>
