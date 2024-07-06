<?php

namespace App\Http\Controllers;

use App\Models\Image_products;
use App\Models\Objects;
use App\Models\Sick;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Third_categories;
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
        $sick = Sick::all();
        $object = Objects::all();
        // dd($sick, $object);
        return view('admin/product/store_product', ['third_cate' => $third_cate, 'sick' => $sick, 'object' => $object]);
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
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move(public_path('images/product'), $avatarName); // Lưu avatar vào 'public/images/product'
            $avatarPath = 'images/product/' . $avatarName;
        }

        $sick = implode(', ', $request->sick);
        $object = implode(', ', $request->obj);
        $product = Products::create([
            'name' => $request->name,
            'price' => $request->price,
            'in_stock' => $request->in_stock,
            'brand' => $request->brand,
            'hide' => $request->hide,
            'id_third_category' => $request->category,
            'avatar' => $avatarPath,
            'sick' => $sick ? $sick : null,
            'object' => $object ? $object : null,
        ]);

        if ($request->hasFile('avatar_sub')) {
            $imageProduct = new Image_products();
            
            foreach ($request->file('avatar_sub') as $index => $subImage) {
                $subImageName = time() . '_' . $subImage->getClientOriginalName();
                $subImage->move(public_path('images/product_sub'), $subImageName);
                
                $imageColumn = 'image_' . ($index + 1);
                $imageProduct->$imageColumn = 'images/product_sub/' . $subImageName;
            }
            
            $imageProduct->save();
    
            // Cập nhật id_image_product cho sản phẩm
            $product->id_image_product = $imageProduct->id_image_product;
            $product->save();
        }

        // if ($request->hasFile('avatar_sub')) {
        //     foreach ($request->file('avatar_sub') as $subImage) {
        //         $subImageName = time() . '_' . $subImage->getClientOriginalName();
        //         $subImage->move(public_path('images/product_sub'), $subImageName);

        //         $imageProduct = Image_products::create([
        //             'image_1' => 'images/product_sub/' . $subImageName,
        //         ]);

        //         $product->update(['id_image_product' => $imageProduct->id_image_product]);
        //     }
        // }

        return redirect()->back()->with('success', 'Thêm sản phẩm thành công!');
    }
}
