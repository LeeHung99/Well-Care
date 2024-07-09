<?php

namespace App\Http\Controllers;

use App\Models\Sick;
use App\Models\Objects;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Image_products;
use App\Models\Third_categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $perpage = 15;
        $data = Products::with('Third_categories')
            ->paginate($perpage);
        return view('admin/product/list_product', ['data' => $data]);
    }

    public function edit($id)
    {
        $data = Products::with('Third_categories')->where('id_product', $id)->first();
        $third_cate = Third_categories::all();

        $sicks = Sick::all();
        $sick_arr = explode(',',  $data->sick);
        $sick_arr = array_map('trim', $sick_arr);
        $sick_name = Sick::whereIn('id_sick', $sick_arr)->get();

        $objects = Objects::all();
        $object_arr = explode(',',  $data->object);
        $object_arr = array_map('trim', $object_arr);
        $object_name = Objects::whereIn('id_object', $object_arr)->get();

        $image_product = Image_products::where('id_image_product', $data->id_image_product)->get();
        return view('admin/product/edit_product', [
            'data' => $data,
            'third_cate' => $third_cate,
            'objects' => $objects,
            'object_name' => $object_name,
            'sicks' => $sicks,
            'sick_name' => $sick_name,
            'image_product' => $image_product,
        ]);
    }

    public function updateproduct(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'string|max:255',
        //     'price' => 'numeric',
        //     'in_stock' => 'integer',
        //     'brand' => 'string|max:255',
        //     'hide' => 'in:0,1',
        //     'category' => 'exists:third_categories,id_third_category',
        //     'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'object.*' => 'exists:objects,id_object',
        //     'sick.*' => 'exists:sicks,id_sick',
        // ]);

        $data = Products::findOrFail($id);
        $imageProduct = Image_products::where('id_image_product', $data->id_image_product)->first();

        // Nếu $imageProduct null, tạo mới một bản ghi Image_products
        if (!$imageProduct) {
            $imageProduct = new Image_products();
            $imageProduct->save(); // Lưu để có id_image_product mới
            $data->id_image_product = $imageProduct->id_image_product;
            $data->save();
        }

        // Update product details
        $data->update([
            'name' => $request->name,
            'price' => $request->price,
            'in_stock' => $request->in_stock,
            'brand' => $request->brand,
            'hide' => $request->hide,
            'id_third_category' => $request->category,
            'id_object' => $request->obj,
            'id_sick' => $request->sick,
        ]);

        // Handle image deletions
        if ($request->has('deletedImages') && $request->deletedImages != null) {
            $deletedImages = explode(',', $request->deletedImages);
            foreach ($deletedImages as $deletedImage) {
                if (strpos($deletedImage, ':') !== false) {
                    list($imageId, $field) = explode(':', $deletedImage);
                    if ($imageProduct->$field) {
                        $filePath = public_path($imageProduct->$field);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $imageProduct->$field = null;
                    }
                }
            }
        }

        // upload avatar_sub
        if ($request->hasFile('avatar_sub')) {
            $imageFields = ['image_1', 'image_2', 'image_3', 'image_4'];
            foreach ($request->file('avatar_sub') as $image) {
                foreach ($imageFields as $field) {
                    if (!$imageProduct->$field) {
                        $filename = time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('images/product_sub'), $filename);
                        $imageProduct->$field = $filename;
                        break;
                    }
                }
            }
        }

        $imageProduct->save();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move(public_path('images/product'), $avatarName);
            $data->avatar = $avatarName; // Lưu đường dẫn của avatar vào bảng products
        }

        return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công!');
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

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move(public_path('images/product'), $avatarName); // Lưu avatar vào 'public/images/product'
            $avatarPath = $avatarName;
        }
        // dd($request);

        $product = Products::create([
            'name' => $request->name,
            'price' => $request->price,
            'in_stock' => $request->in_stock,
            'brand' => $request->brand,
            'hide' => $request->hide,
            'id_third_category' => $request->category,
            'avatar' => $avatarPath,
            'id_sick' => $request->sick,
            'id_object' => $request->obj,
        ]);

        if ($request->hasFile('avatar_sub')) {
            $imageProduct = new Image_products();

            foreach ($request->file('avatar_sub') as $index => $subImage) {
                $subImageName = time() . '_' . $subImage->getClientOriginalName();
                $subImage->move(public_path('images/product_sub'), $subImageName);

                $imageColumn = 'image_' . ($index + 1);
                $imageProduct->$imageColumn = $subImageName;
            }

            $imageProduct->save();

            // Cập nhật id_image_product cho sản phẩm
            $product->id_image_product = $imageProduct->id_image_product;
            $product->save();
        }

        return redirect()->back()->with('success', 'Thêm sản phẩm thành công!');
    }


    public function destroy(Request $request){
        // dd($request->id);
        $id = $request->id;
        $product = Products::where('id_product', $id)->first();
        if($product){   
            $product->delete();
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa');
        } else{
            return redirect()->back()->with('error', 'Không có sản phẩm tương tự');
        }
    }
}
