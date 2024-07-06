<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Third_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    public function index()
    {
        $perpage = 15;
        $data = Products::with('Third_categories')
            ->paginate($perpage);
        // dd($data,$thirdCategory);
        return view('admin/product/list_product', ['data' => $data]);
    }

    public function edit($id)
    {
        $data = Products::with('Third_categories')->where('id_product', $id)->first();
        $third_cate = Third_categories::all();
        // dd($data);
        return view('admin/product/edit_product', ['data' => $data, 'third_cate' => $third_cate]);
    }

    public function updateproduct(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'name' => 'string|max:255',
            // 'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'numeric',
            'in_stock' => 'integer',
            'brand' => 'string|max:255',
            'hide' => 'in:0,1',
            'category' => 'exists:third_categories,id_third_category',
        ]);
        $data = Products::find($id);

        if ($data) {
            $data->update([
                'name' => $request->name,
                'price' => $request->price,
                'in_stock' => $request->in_stock,
                'brand' => $request->brand,
                'hide' => $request->hide,
                'id_third_category' => $request->category,
                'avatar' => $request->avatar,
            ]);

            // Handle avatar update if a new file is uploaded
            // if ($request->avatar) {
            //     // Logic to handle avatar upload
            //     // Example:
            //     $data->update([
            //         'avatar' => $request->avatar,
            //     ]);
            //     $path = $request->avatar->store('product', 'public');
            //     $data->avatar = $path;
            //     $data->save();
            // }

            // Redirect back or to another route upon successful update
            return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công!');
        } else {
            // Handle case where product is not found
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm để cập nhật!');
        }
    }

    public function storeView()
    {
        $third_cate = Third_categories::all();
        return view('admin/product/store_product', ['third_cate' => $third_cate]);
    }
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'string|max:255',
        //     'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'price' => 'numeric',
        //     'in_stock' => 'integer',
        //     'brand' => 'string|max:255',
        //     'hide' => 'in:0,1',
        //     'category' => 'exists:third_categories,id_third_category',
        // ]);

        // đường dẫn avatar
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = $avatar->store('images/product', 'public'); // Lưu avatar vào 'storage/app/public/images/product'
        }
        $product = Products::create([
            'name' => $request->name,
            'price' => $request->price,
            'in_stock' => $request->in_stock,
            'brand' => $request->brand,
            'hide' => $request->hide,
            'id_third_category' => $request->category,
            'avatar' => $request->avatar, // Thay bằng đường dẫn hình ảnh
        ]);
    
        return redirect()->back()->with('success', 'Thêm sản phẩm thành công!');
    }
}
