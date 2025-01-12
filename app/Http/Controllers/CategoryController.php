<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Tạo mới danh mục
        $category = Category::create([
            'name' => $validated['name'],
        ]);

        // Trả về dữ liệu danh mục mới dưới dạng JSON
        return response()->json([
            'id' => $category->id,
            'name' => $category->name
        ]);
    }

    public function edit(Category $category)
    {
        // Kiểm tra xem request có phải là AJAX không
        if (request()->ajax()) {
            return response()->json([
                'category' => $category
            ]);
        }

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return response()->json([
            'category' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index');
    }
}
