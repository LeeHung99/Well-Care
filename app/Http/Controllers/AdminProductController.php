<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductEditValid;
use App\Http\Requests\ProductValid;
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
    public function index(Request $request)
    {
        $perpage = 15;
        $sort_by = $request->get('sort_by', 'created_at');
        $sort_order = $request->get('sort_order', 'desc');
        $search = $request->get('search');

        $query  = Products::with('Third_categories');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('brand', 'like', '%' . $search . '%');
        }

        $data = $query->orderBy($sort_by, $sort_order)
            ->paginate($perpage);

        return view('admin.product.list_product', [
            'data' => $data,
            'sort_by' => $sort_by,
            'sort_order' => $sort_order
        ]);
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

    public function updateproduct(ProductEditValid $request, $id)
    {

        // dd($request);
        $data = Products::findOrFail($id);
        $imageProduct = Image_products::where('id_image_product', $data->id_image_product)->first();

        if (!$imageProduct) {
            $imageProduct = new Image_products();
            $imageProduct->save(); // Lưu để có id_image_product mới
            $data->id_image_product = $imageProduct->id_image_product;
            $data->save();
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

        // Update product details
        $data->update([
            'name' => $request->name,
            'price' => $request->price,
            'in_stock' => $request->in_stock,
            'brand' => $request->brand,
            'sale' => $request->sale,
            'symptom' => $request->symptom,
            'origin' => $request->origin,
            'unit' => $request->unit,
            'short_des' => $request->short_des,
            'description' => $request->description,
            'avatar' => $data->avatar,
            'hide' => $request->hide,
            'id_third_category' => $request->category,
            'id_sick' => $request->sick,
            'id_object' => $request->obj,
            'hot' => $request->hot,
            'hide' => $request->hide,
        ]);

        // Handle image deletions
        if ($request->has('deletedImages') && $request->deletedImages != null) {
            $deletedImages = explode(',', $request->deletedImages);
            foreach ($deletedImages as $deletedImage) {
                if (strpos($deletedImage, ':') !== false) {
                    list($imageId, $field) = explode(':', $deletedImage);
                    if ($imageProduct->$field) {
                        $filePath = public_path('images/product_sub/' . $imageProduct->$field);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $imageProduct->$field = null;
                    }
                }
            }
            $imageProduct->save(); // Lưu thay đổi sau khi xóa
        }


        return redirect()->route('product')->with('success', 'Cập nhật sản phẩm thành công!');
    }


    public function storeView()
    {
        $third_cate = Third_categories::all();
        $sick = Sick::all();
        $object = Objects::all();
        return view('admin/product/store_product', ['third_cate' => $third_cate, 'sick' => $sick, 'object' => $object]);
    }
    public function store(ProductValid $request)
    {

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move(public_path('images/product'), $avatarName); // Lưu avatar vào 'public/images/product'
            $avatarPath = $avatarName;
        }
        // dd($request);

        if ($request->sale == '') {
            $sale = 0;
        } else {
            $sale = $request->sale;
        }
        $product = Products::create([
            'id_image_product' => 1,
            'name' => $request->name,
            'price' => $request->price,
            'in_stock' => $request->in_stock,
            'brand' => $request->brand,
            'sale' => $sale,
            'symptom' => $request->symptom,
            'origin' => $request->origin,
            'unit' => $request->unit,
            'short_des' => $request->short_des,
            'description' => $request->description,
            'hide' => $request->hide,
            'id_third_category' => $request->category,
            'avatar' => $avatarPath,
            'id_sick' => $request->sick,
            'id_object' => $request->obj,
            'hot' => $request->hot,
            'hide' => $request->hide,
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

        return redirect()->route('product')->with('success', 'Thêm sản phẩm thành công!');
    }


    public function destroy(Request $request)
    {
        // dd($request->id);
        $id = $request->id;
        $product = Products::where('id_product', $id)->first();
        if ($product) {
            $product->delete();
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa');
        } else {
            return redirect()->back()->with('error', 'Không có sản phẩm tương tự');
        }
    }
}
