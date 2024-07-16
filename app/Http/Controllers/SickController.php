<?php

namespace App\Http\Controllers;

use App\Models\Sick;
use Illuminate\Http\Request;
use App\Http\Requests\SickValid;
use App\Models\Products;
use Illuminate\Support\Facades\Log;

class SickController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $data = Sick::orderBy('created_at', 'desc')->paginate($perPage);
        // dd($data);
        return view('admin.sick.list_sick', compact('data'));
    }
    public function storeView()
    {
        return view('admin.sick.store_sick');
    }
    public function store(SickValid $request)
    {
        try {
            // dd($request);
            $sick = Sick::create([
                'name' => $request->name,
                'symptom' => $request->symptom,
                'description' => $request->description,
                'hide' => $request->hide
            ]);

            return redirect()->back()->with('success', 'Thêm bệnh thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating sick: ' . $e->getMessage());
            // return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['error' => 'Thêm bệnh thất bại'], 500);
        }
    }

    public function editView($id)
    {
        // dd($id);
        $data = Sick::where('id_sick', $id)->first();
        if (!$data) {
            return response()->json(['error' => 'Không có bệnh tương tự']);
        }
        return view('admin.sick.edit_sick', compact('data'));
    }

    public function update(SickValid $request, $id)
    {
        // dd($request, $id);
        try {
            $data = Sick::where('id_sick', $id)->first();
            $data->update([
                'name' => $request->name,
                'symptom' => $request->symptom,
                'description' => $request->description,
                'hide' => $request->hide,
            ]);

            return redirect()->back()->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating sick: ' . $e->getMessage());
            // return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['error' => 'Cập nhật thất bại!'], 500);
        }
    }

    public function destroy($id){
        $sick = Sick::where('id_sick', $id)->first();

        $productsCount = Products::where('id_sick', $id)->count();
        if ($productsCount > 0) {
            return redirect()->back()->with('error', 'Không thể xóa bệnh này vì có sản phẩm đang tham chiếu đến nó.');
            // return response()->json(['error' => 'Không thể xóa Sick này vì có sản phẩm đang tham chiếu đến nó.']);
        }

        $sick->delete();
        return redirect()->back()->with('success', 'Xóa thành công!');
    }
}
