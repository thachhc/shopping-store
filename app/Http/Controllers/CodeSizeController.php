<?php

namespace App\Http\Controllers;

use App\Models\SizeCode;
use Illuminate\Http\Request;

class CodeSizeController extends Controller
{
    // Hiển thị form để thêm size và quantity
    public function create(Request $request)
    {
        $productId = $request->get('product_id');
        return view('admin.SizeCodes.create', compact('productId'));
    }

    // Lưu thông tin size và quantity vào bảng SizeCodes
    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required|array',
            'size.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id',
        ]);

        $sizeInputs = $request->input('size');
        $quantityInputs = $request->input('quantity');
        $productId = $request->input('product_id');

        for ($i = 0; $i < count($sizeInputs); $i++) {
            SizeCode::create([
                'sizenumber' => $sizeInputs[$i],
                'product_id' => $productId,
                'quantity' => $quantityInputs[$i],
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Sizes added successfully!');
    }

    public function editByProduct($productId)
    {
        // Lấy tất cả các SizeCode dựa trên product_id
        $sizes = SizeCode::where('product_id', $productId)->get();

        return view('admin.SizeCodes.edit', [
            'sizes' => $sizes,
            'productId' => $productId
        ]);
    }

    public function updateByProduct(Request $request, $productId)
    {
        $request->validate([
            'size' => 'required|array',
            'size.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ]);

        $sizeInputs = $request->input('size');
        $quantityInputs = $request->input('quantity');

        // Lấy tất cả các SizeCode có cùng product_id để cập nhật
        $existingSizes = SizeCode::where('product_id', $productId)->get();

        // Cập nhật kích thước và số lượng
    foreach ($existingSizes as $existingSize) {
        $index = array_search($existingSize->sizenumber, $sizeInputs);

        if ($index !== false) {
            // Nếu tìm thấy, cập nhật số lượng
            if (isset($quantityInputs[$index])) {
                $existingSize->update([
                    'quantity' => $quantityInputs[$index],
                ]);
            }
            unset($sizeInputs[$index]);
            unset($quantityInputs[$index]);
        } else {
            // Xóa kích thước nếu không còn trong danh sách
            $existingSize->delete();
        }
    }

    // Thêm các kích thước mới nếu có
    foreach ($sizeInputs as $index => $size) {
        // Kiểm tra xem quantity có tồn tại cho size không
        if (isset($quantityInputs[$index])) {
            SizeCode::create([
                'sizenumber' => $size,
                'product_id' => $productId,
                'quantity' => $quantityInputs[$index],
            ]);
        }
    }
        return redirect()->route('products.index')->with('success', 'Sizes updated successfully!');
    }
}
