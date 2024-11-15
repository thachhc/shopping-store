<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Hiển thị danh sách các thương hiệu.
     */
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Hiển thị form tạo mới thương hiệu.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Lưu thương hiệu mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Tạo mới thương hiệu
        $brand = Brand::create($validated);

        // Kiểm tra nếu là yêu cầu AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu đã được thêm thành công!',
                'brand' => $brand,
            ]);
        }

        // Yêu cầu thông thường, chuyển hướng với thông báo thành công
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được thêm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa thương hiệu.
     */
    public function edit(Brand $brand)
    {
        // Kiểm tra nếu là yêu cầu AJAX
        if (request()->ajax()) {
            return response()->json(['brand' => $brand]);
        }

        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Cập nhật thương hiệu đã tồn tại.
     */
    public function update(Request $request, Brand $brand)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Cập nhật thương hiệu
        $brand->update($validated);

        // Kiểm tra nếu là yêu cầu AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu đã được cập nhật thành công!',
                'brand' => $brand,
            ]);
        }

        // Yêu cầu thông thường, chuyển hướng với thông báo thành công
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được cập nhật thành công!');
    }

    /**
     * Xóa thương hiệu.
     */
    public function destroy(Request $request, Brand $brand)
    {
        $brand->delete();

        // Kiểm tra nếu là yêu cầu AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thương hiệu đã được xóa thành công!',
            ]);
        }

        // Yêu cầu thông thường, chuyển hướng với thông báo thành công
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được xóa thành công!');
    }
}
