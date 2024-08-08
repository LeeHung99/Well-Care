@extends('admin/layout_admin/layout')
@section('noidungchinh')
    <div class="row pt-3">
        <div class="col-xl-6">
            <h2 class="text-center mb-4">Thống kê đơn hàng</h2>
            <div class="mb-3">
                <form id="dateRangeForm" class="form-inline">
                    <div class="row">
                        <div class="col-xl-5 p-0">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="startDate"><b>Ngày bắt đầu</b></label>
                                <input type="text" class="form-control" id="startDate" placeholder="Ngày bắt đầu"
                                    value="{{ $startDate }}" required>
                            </div>
                        </div>
                        <div class="col-xl-5 p-0">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="endDate"><b>Ngày kết thúc</b></label>
                                <input type="text" class="form-control" id="endDate" placeholder="Ngày kết thúc"
                                    value="{{ $endDate }}" required>
                            </div>
                        </div>
                        <div class="col-xl-2 p-0 mt-4">
                            <button type="submit" class="btn btn-primary mb-2">Lọc</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-body">
                    <canvas id="orderChart" height="150"></canvas>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    flatpickr("#startDate", {
                        dateFormat: "Y-m-d"
                    });
                    flatpickr("#endDate", {
                        dateFormat: "Y-m-d"
                    });
                    let orderChart;

                    function createOrUpdateChart(data) {
                        const ctx = document.getElementById('orderChart').getContext('2d');

                        if (orderChart) {
                            orderChart.destroy(); // Destroy the previous chart instance
                        }

                        orderChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Đang xử lý', 'Đang giao hàng', 'Giao hàng thành công', 'Đơn hàng bị hủy'],
                                datasets: [{
                                    label: 'Total Orders',
                                    data: [
                                        data['Đang xử lý'] || 0,
                                        data['Đang giao hàng'] || 0,
                                        data['Giao hàng thành công'] || 0,
                                        data['Đơn hàng bị hủy'] || 0
                                    ],
                                    backgroundColor: [
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(255, 99, 132, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(255, 99, 132, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Số lượng đơn hàng'
                                        },
                                        ticks: {
                                            stepSize: 1 // Ensure the y-axis step size is 1
                                        }
                                    }
                                }
                            }
                        });
                    }
                    const initialData = @json($data);
                    createOrUpdateChart(initialData);
                    $('#dateRangeForm').on('submit', function(e) {
                        e.preventDefault();

                        const startDate = $('#startDate').val();
                        const endDate = $('#endDate').val();

                        $.ajax({
                            url: '{{ url('admin/update-data') }}',
                            type: 'GET',
                            data: {
                                start_date: startDate,
                                end_date: endDate
                            },
                            success: function(data) {
                                createOrUpdateChart(data);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching data:', error);
                            }
                        });
                    });
                });
            </script>
            </script>
        </div>
        <div class="col-xl-6">
            <h2 class="text-center mb-4">Thống kê doanh thu</h2>
            <div class="mb-3">
                <form id="yearForm" class="form-inline">
                    <div class="row">
                        <div class="col-xl-7">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="yearSelect"><b>Chọn năm</b></label>
                                <select id="yearSelect" class="form-control">
                                    @for ($year = 2020; $year <= Carbon\Carbon::now()->year; $year++)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-5  mt-4">
                            <button type="submit" class="btn btn-primary mb-2">Lọc</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-body">
                    <canvas id="revenueChart" height="150"></canvas>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    let revenueChart;

                    function createOrUpdateChart(data) {
                        const ctx = document.getElementById('revenueChart').getContext('2d');

                        if (revenueChart) {
                            revenueChart.destroy();
                        }

                        revenueChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                                    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                                ],
                                datasets: [{
                                    label: 'Doanh thu (VND)',
                                    data: data,
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1,
                                    fill: false
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Doanh thu (VND)'
                                        },
                                        ticks: {
                                            stepSize: 1 // Ensure the y-axis step size is 1
                                        }
                                    }
                                }
                            }
                        });
                    }
                    const initialData = @json(array_values($revenueData));
                    createOrUpdateChart(initialData);
                    $('#yearForm').on('submit', function(e) {
                        e.preventDefault();

                        const selectedYear = $('#yearSelect').val();

                        $.ajax({
                            url: '{{ url('admin/update-revenue-data') }}',
                            type: 'GET',
                            data: {
                                year: selectedYear
                            },
                            success: function(data) {
                                createOrUpdateChart(Object.values(data));
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching data:', error);
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header text-bg-danger">
                    <b>Danh sách sản phẩm sắp hết hàng</b>
                </div>
                <table class="table table-striped border text-center align-middle">
                    <thead>
                        <tr>
                            <th scope="col">ID sản phẩm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Tên Sản Phẩm</th>
                            <th scope="col">Số lượng trong kho</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productshh as $index => $item)
                            <tr>
                                <th scope="row">#{{ $item->id_product }}</th>
                                <td><img src="https://cms.wellcarepharmacy.shop/images/product/{{ $item->avatar }}"
                                        alt="" width="50px"></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->in_stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $productshh->onEachSide(3)->links() }}
            </div>
        </div>
        <div class="col-xl-6 pe-4">
            <div class="card">
                <div class="card-header text-bg-danger">
                    <b>Danh sách sản phẩm hết hàng</b>
                </div>
                <table class="table table-striped border text-center align-middle">
                    <thead>
                        <tr>
                            <th scope="col">ID sản phẩm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Tên Sản Phẩm</th>
                            <th scope="col">Số lượng trong kho</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producthh as $index => $item)
                            <tr>
                                <th scope="row">#{{ $item->id_product }}</th>
                                <td><img src="https://cms.wellcarepharmacy.shop/images/product/{{ $item->avatar }}"
                                        alt="" width="50px"></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->in_stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-xl-12 pe-4">
            <div class="card">
                <div class="card-header text-bg-danger">
                    <b>Top 10 khách hàng mua hàng nhiều nhất</b>
                </div>
                <table class="table table-striped border text-center align-middle">
                    <thead>
                        <tr>
                            <th scope="col">ID khách hàng</th>
                            <th scope="col">Tên khách hàng</th>
                            <th scope="col">Số điện thoại</th>
                            <th scope="col">Số đơn hàng</th>
                            <th scope="col">Tổng tiền đã mua</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topUsers as $index => $item)
                            <tr>
                                <th scope="row">#{{ $item->id_user }}</th>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->purchase_count }}</td>
                                <td><b>{{ number_format($item->total_spent) }} VNĐ</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-xl-12 pe-4">
            <div class="card">
                <div class="card-header text-bg-danger">
                    <b>Top 10 bình luận mới nhất</b>
                </div>
                <table class="table table-striped border text-center align-middle">
                    <thead>
                        <tr>
                            <th scope="col">ID bình luận</th>
                            <th scope="col">ID sản phẩm</th>
                            <th scope="col">Tên khách hàng</th>
                            <th scope="col">Nội dung</th>
                            {{-- <th scope="col">Số đơn hàng</th>
                            <th scope="col">Tổng tiền đã mua</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestComment as $index => $item)
                            <tr>
                                <th scope="row">#{{ $item->id_comment }}</th>
                                <td>{{ $item->id_product }}</td>
                                <td>{{ $item->user['name'] }}</td>
                                <td style="width: 65%">{{ $item->content }}</td>
                                {{-- <td><b>{{ number_format($item->total_spent) }} VNĐ</b></td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header text-bg-danger">
                    <b>Top 10 sản phẩm bán chạy</b>
                </div>
                <table class="table table-striped border text-center align-middle">
                    <thead>
                        <tr>
                            <th scope="col">ID sản phẩm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Tên Sản Phẩm</th>
                            <th scope="col">Số lượng đã bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productbc as $index => $item)
                            <tr>
                                <th scope="row">#{{ $item->id_product }}</th>
                                <td><img src="https://cms.wellcarepharmacy.shop/images/product/{{ $item->avatar }}"
                                        alt="" width="50px"></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->sold }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-xl-6 pe-4">
            <div class="card">
                <div class="card-header text-bg-danger">
                    <b>Top 10 sản phẩm có nhiều bình luận nhất</b>
                </div>
                <table class="table table-striped border text-center align-middle">
                    <thead>
                        <tr>
                            <th scope="col">ID sản phẩm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Tên Sản Phẩm</th>
                            <th scope="col">Số lượt bình luận</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productbl as $index => $item)
                            <tr>
                                <th scope="row">#{{ $item->id_product }}</th>
                                <td><img src="https://cms.wellcarepharmacy.shop/images/product/{{ $item->avatar }}"
                                        alt="" width="50px"></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->comment_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
