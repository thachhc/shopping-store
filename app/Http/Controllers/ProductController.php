<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\CodeSize;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::with(['category', 'brand', 'tag'])->get();
        return view('admin.products.index', compact('products'));
    }

    // Form thêm sản phẩm
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $tags = Tag::all();
        return view('admin.products.create', compact('categories', 'brands', 'tags'));
    }

    // Xử lý thêm sản phẩm mới
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric',
    //         'price_sale' => 'nullable|numeric', // Để trường này có thể để trống
    //         'description' => 'nullable|string',
    //         'brand_id' => 'required|exists:brands,id',
    //         'category_id' => 'required|exists:categories,id',
    //         'tag_id' => 'required|exists:tags,id',
    //         'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $product = new Product();
    //     $product->name = $request->name;
    //     $product->price = $request->price;

    //     // Chỉ gán giá trị price_sale nếu nó được cung cấp và không rỗng
    //     if ($request->filled('price_sale')) {
    //         $product->price_sale = $request->price_sale;
    //     } else {
    //         $product->price_sale = null; // Đặt giá trị là null nếu không có dữ liệu
    //     }

    //     $product->description = $request->description;
    //     $product->brand_id = $request->brand_id;
    //     $product->category_id = $request->category_id;
    //     $product->tag_id = $request->tag_id;

    //     $imagePaths = [];

    //     if ($request->hasFile('images')) {
    //         $index = 1;
    //         foreach ($request->file('images') as $image) {
    //             $imageName = time() . '-' . $image->getClientOriginalName();
    //             $destinationPath = public_path('images');
    //             $image->move($destinationPath, $imageName);
    //             $imagePaths['image' . $index] = 'images/' . $imageName;
    //             $index++;
    //         }
    //     }

    //     $product->image = json_encode($imagePaths);
    //     $product->save();

    //     return redirect()->route('codesizes.create', ['product_id' => $product->id]);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'description' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'tag_id' => 'required|exists:tags,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'size' => 'required|array',
            'size.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ]);

        // Lưu sản phẩm
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->price_sale = $request->price_sale;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->tag_id = $request->tag_id;

        // Lưu ảnh sản phẩm
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $destinationPath = public_path('images');
                $image->move($destinationPath, $imageName);
                $imagePaths[] = 'images/' . $imageName;
            }
        }

        $product->image = json_encode($imagePaths);
        $product->save();

        // Lưu size và quantity
        $sizes = $request->size;
        $quantities = $request->quantity;

        for ($i = 0; $i < count($sizes); $i++) {
            CodeSize::create([
                'sizenumber' => $sizes[$i],
                'product_id' => $product->id,
                'quantity' => $quantities[$i],
            ]);
        }

        // Sau khi lưu sản phẩm và size, chuyển hướng về trang danh sách sản phẩm
        return redirect()->route('products.index')->with('success', 'Product and sizes created successfully!');
    }



    // Form sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $tags = Tag::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'tags'));
    }

    // Xử lý cập nhật sản phẩm
    public function update(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'description' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'tag_id' => 'required|exists:tags,id',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Định nghĩa đường dẫn lưu hình ảnh
        $destinationPath = public_path('images');

        // Xử lý hình ảnh cũ
        $existingImages = json_decode($product->image, true);

        // Cập nhật hình ảnh mới
        $newImages = [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move($destinationPath, $imageName);
                $newImages[] = 'images/' . $imageName;
            }
        }

        // Xử lý hình ảnh đã cập nhật
        if ($request->has('updated_images')) {
            foreach ($request->updated_images as $key => $updatedImage) {
                if ($updatedImage) {
                    // Xóa hình ảnh cũ
                    $oldImagePath = public_path($existingImages[$key]);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }

                    // Lưu hình ảnh mới
                    $imageName = time() . '-' . $updatedImage->getClientOriginalName();
                    $updatedImage->move($destinationPath, $imageName);
                    $existingImages[$key] = 'images/' . $imageName; // Cập nhật hình ảnh mới vào mảng
                }
            }
        }

        // Xử lý hình ảnh bị xóa
        if ($request->removed_images) {
            $removedImages = json_decode($request->removed_images);
            foreach ($removedImages as $key) {
                $oldImagePath = public_path($existingImages[$key]);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Xóa hình ảnh khỏi hệ thống
                }
                unset($existingImages[$key]); // Xóa hình ảnh khỏi mảng
            }
        }

        // Cập nhật hình ảnh trong database
        $product->image = json_encode(array_merge($existingImages, $newImages));

        // Cập nhật thông tin khác của sản phẩm
        $product->name = $request->name;
        $product->price = $request->price;
        $product->price_sale = $request->price_sale;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->tag_id = $request->tag_id;

        $product->save();

        // Chuyển hướng tùy thuộc vào giá trị của redirect_to_codesize
        if ($request->redirect_to_codesize == "1") {
            return redirect()->route('codesizes.editByProduct', ['product_id' => $productId]);
        } else {
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật!');
        }
    }

    // Xử lý xóa sản phẩm
    public function destroy($id)
    {
        // Lấy sản phẩm từ database
        $product = Product::findOrFail($id);

        // Xóa hình ảnh khỏi thư mục nếu có
        if ($product->image) {
            foreach (json_decode($product->image) as $imagePath) {
                if (file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
            }
        }
        // Xóa sản phẩm khỏi database
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa!');
    }

    public function search(Request $request)
    {
        $query = Product::query();

        // Kiểm tra nếu có tham số tìm kiếm
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $products = $query->with(['category', 'brand', 'tag'])->get();

        return view('admin.products.index', compact('products'));
    }
}
