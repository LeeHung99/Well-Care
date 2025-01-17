<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\LoginValid;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

Paginator::useBootstrap();

class AdminController extends Controller
{
    private function getData($startDate, $endDate)
    {
        $statuses = ['Đang xử lý', 'Đang giao hàng', 'Giao hàng thành công', 'Đơn hàng bị hủy'];
        $transport_status = [
            '0' => 'Đang xử lý',
            '1' => 'Đang giao hàng',
            '2' => 'Giao hàng thành công',
            '3' => 'Đơn hàng bị hủy'
        ];

        $data = DB::table('bills')
            ->select(DB::raw('transport_status, COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('transport_status')
            ->get()
            ->mapWithKeys(function ($item) use ($transport_status) {
                return [$transport_status[$item->transport_status] => $item->count];
            })
            ->toArray();

        foreach ($statuses as $status) {
            if (!isset($data[$status])) {
                $data[$status] = 0;
            }
        }

        return response()->json($data);
    }
    private function getRevenueData($year)
    {
        $data = DB::table('bills')
            ->select(DB::raw('MONTH(created_at) as month, SUM(total_amount) as revenue'))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('revenue', 'month')
            ->toArray();
        $revenueData = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenueData[$month] = $data[$month] ?? 0;
        }
        return $revenueData;
    }
    public function index()
    {
        $perpage = 5;
        $productshh = DB::table('products')
            ->where('in_stock', '<=', 10)
            ->where('in_stock', '>', 0)
            ->paginate($perpage);
        $producthh = DB::table('products')
            ->where('in_stock', 0)
            ->get();
        $productbc = DB::table('products')
            ->orderBy('sold', 'desc')
            ->limit(10)
            ->get();
        $productbl = DB::table('products')
            ->join('comments', 'products.id_product', '=', 'comments.id_product')
            ->select('products.id_product', 'products.id_third_category', 'products.id_image_product', 'products.id_sick', 'products.id_object', 'products.name', 'products.avatar', 'products.price', 'products.short_des', 'products.description', 'products.in_stock', 'products.hot', 'products.sold', 'products.sale', 'products.symptom', 'products.brand', 'products.origin', 'products.hide', 'products.created_at', 'products.updated_at', DB::raw('COUNT(comments.id_comment) as comment_count'))
            ->groupBy('products.id_product', 'products.id_third_category', 'products.id_image_product', 'products.id_sick', 'products.id_object', 'products.name', 'products.avatar', 'products.price', 'products.short_des', 'products.description', 'products.in_stock', 'products.hot', 'products.sold', 'products.sale', 'products.symptom', 'products.brand', 'products.origin', 'products.hide', 'products.created_at', 'products.updated_at')
            ->orderBy('comment_count', 'desc')
            ->limit(10)
            ->get();
        $topUsers = DB::table('users')
            ->join('bills', 'users.id_user', '=', 'bills.id_user')
            ->select(
                'users.id_user',
                'users.name',
                'users.phone',
                DB::raw('COUNT(bills.id_bill) as purchase_count'),
                DB::raw('SUM(bills.total_amount) as total_spent')
            )
            ->groupBy('users.id_user', 'users.name', 'users.phone')
            ->orderBy('purchase_count', 'desc')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        $latestComment = Comments::with('user', 'product')
            ->orderBy('created_at', 'desc')->limit(10)->get();
        $startDate = Carbon::now()->subDays(7)->toDateString();
        $endDate = Carbon::now()->toDateString();
        $data = $this->getData($startDate, $endDate);

        $currentYear = Carbon::now()->year;
        $revenueData = $this->getRevenueData($currentYear);

        return view('index', [
            'productshh' => $productshh,
            'producthh' => $producthh,
            'productbc' => $productbc,
            'productbl' => $productbl,
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'revenueData' => $revenueData,
            'selectedYear' => $currentYear,
            'topUsers' => $topUsers,
            'latestComment' => $latestComment,
        ]);
    }
    public function updateData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Vui lòng cung cấp cả ngày bắt đầu và ngày kết thúc.'], 400);
        }

        $statuses = ['Đang xử lý', 'Đang giao hàng', 'Giao hàng thành công', 'Đơn hàng bị hủy'];
        $transport_status = [
            '0' => 'Đang xử lý',
            '1' => 'Đang giao hàng',
            '2' => 'Giao hàng thành công',
            '3' => 'Đơn hàng bị hủy'
        ];

        $data = DB::table('bills')
            ->select(DB::raw('transport_status, COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('transport_status')
            ->get()
            ->mapWithKeys(function ($item) use ($transport_status) {
                return [$transport_status[$item->transport_status] => $item->count];
            })
            ->toArray();

        foreach ($statuses as $status) {
            if (!isset($data[$status])) {
                $data[$status] = 0;
            }
        }

        return response()->json($data);
    }
    public function updateRevenueData(Request $request)
    {
        $year = $request->input('year');

        if (!$year) {
            return response()->json(['error' => 'Vui lòng cung cấp năm.'], 400);
        }

        $revenueData = $this->getRevenueData($year);

        return response()->json($revenueData);
    }

    public function createAdminUser()
    {
        $user = new User();
        $user->name = 'user Test';
        $user->pass = Hash::make('password123');
        $user->phone = '111111111';
        $user->email = 'user@test.com';
        $user->role = 1;
        $user->save();

        return 'Admin user created successfully!';
    }
    public function loginAdmin()
    {
        return view('/login.loginView');
    }
    public function loginVerify(LoginValid $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role != 0) {
                $request->session()->regenerate();

                if ($user->role == 1) {
                    return redirect()->route('dashboard')->with('success', 'Chào mừng Admin!');
                } elseif ($user->role == 2) {
                    // dd($user);
                    return redirect()->route('dashboard')->with('success', 'Chào mừng Editor!');
                } elseif ($user->role == 3) {
                    return redirect()->route('dashboard')->with('success', 'Chào mừng Post Editor!');
                }
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Tài khoản không có quyền truy cập.');
            }
        }

        return redirect()->route('login')->with('error', 'Tài khoản hoặc mật khẩu không đúng');
    }
    public function exit()
    {
        auth()->guard('web')->logout();
        return redirect('/login')->with('thongbao', 'Bạn đã thoát thành công');
    }
}
