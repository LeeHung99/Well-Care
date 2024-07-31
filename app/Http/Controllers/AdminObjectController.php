<?php

namespace App\Http\Controllers;

use App\Models\Objects;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Requests\ObjectValid;
use Illuminate\Support\Facades\Log;

class AdminObjectController extends Controller
{
    /**
     * function index with search
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $search = $request->get('search');

        $query = Objects::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->paginate($perPage);

        return view('admin.object.list_object', compact('data', 'search'));
    }

    /**
     * function index without search
     */
    // public function index()
    // {
    //     $perPage = 10;
    //     $data = Objects::orderBy('created_at', 'desc')->paginate($perPage);
    //     return view('admin.object.list_object', compact('data'));
    // }
    public function storeView()
    {
        return view('admin.object.store_object');
    }

    public function store(ObjectValid $request)
    {
        // dd($request);
        try {
            $data = Objects::create([
                'name' => $request->name,
                'hide' => $request->hide
            ]);

            return redirect()->route('object')->with('success', 'Thêm thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating object: ' . $e->getMessage());
            // return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['error' => 'Thêm thất bại'], 500);
        }
    }

    public function editView($id)
    {
        // dd($id);
        $data = Objects::where('id_object', $id)->first();
        if (!$data) {
            return response()->json(['error' => 'Không có bệnh tương tự']);
        }
        return view('admin.object.edit_object', compact('data'));
    }
    public function update(ObjectValid $request, $id)
    {
        // dd($request, $id);
        try {
            $data = Objects::where('id_object', $id)->first();
            $data->update([
                'name' => $request->name,
                'hide' => $request->hide,
            ]);

            return redirect()->route('object')->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating object: ' . $e->getMessage());
            // return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['error' => 'Cập nhật thất bại!'], 500);
        }
    }
    public function destroy($id)
    {
        $data = Objects::where('id_object', $id)->first();

        $productsCount = Products::where('id_object', $id)->count();
        if ($productsCount > 0) {
            return redirect()->back()->with('error', 'Không thể xóa đối tượng vì có sản phẩm đang tham chiếu.');
            // return response()->json(['error' => 'Không thể xóa Sick này vì có sản phẩm đang tham chiếu']);
        }

        $data->delete();
        return redirect()->back()->with('success', 'Xóa thành công!');
    }
}
