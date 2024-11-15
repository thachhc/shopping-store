<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Tạo mới danh mục
        $tag = Tag::create([
            'name' => $validated['name'],
        ]);

        // Trả về dữ liệu danh mục mới dưới dạng JSON
        return response()->json([
            'id' => $tag->id,
            'name' => $tag->name
        ]);
    }

    public function edit(Tag $tag)
    {
        // Kiểm tra xem request có phải là AJAX không
        if (request()->ajax()) {
            return response()->json([
                'tag' => $tag
            ]);
        }

        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag->update($validated);

        return response()->json([
            'tag' => $tag
        ]);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index');
    }
}
