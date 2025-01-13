<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\SizeCode;

class ProductController extends Controller
{
    /*---------------------------------------------------------USER----------------------------------------------------------------*/   
    public function UserIndex(Request $request, $brandId = null)
    {
        // Validate incoming request parameters
        $validated = $request->validate([
            'category_id' => 'array|exists:categories,id',
            'sizes' => 'array',
            'sizes.*' => 'numeric', // Ensure sizes are numeric
            'brands' => 'array|exists:brands,id',
            'sort_by' => 'nullable|in:newest,sale,featured,,name-asc,name-desc,price-asc,price-desc',
        ]);

        // Fetch all categories, sizes, and brands
        $categories = Category::all();
        $sizes = SizeCode::all();
        $brands = Brand::all();

        // Start building the query for products
        $query = Product::with(['brand', 'category', 'sizes', 'tag']);

        // Filter by brand if provided
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        // Filter by category if provided
        if (!empty($validated['category_id'] ?? null)) {
            $query->whereIn('category_id', $validated['category_id']);
        }

        // Filter by sizes if provided
        if (!empty($validated['sizes'] ?? null)) {
            $query->whereHas('sizes', function ($q) use ($validated) {
                $q->whereIn('sizenumber', $validated['sizes']);
            });
        }

        // Filter by brands if provided
        if (!empty($validated['brands'] ?? null)) {
            $query->whereIn('brand_id', $validated['brands']);
        }

        // Sort products based on the 'sort_by' parameter if provided
        $sortBy = $validated['sort_by'] ?? null;
        if ($sortBy) {
            if ($sortBy === 'newest') {
                // Filter products that have the "New Arrivals" tag and order by creation date
                $query->whereHas('tag', function ($q) {
                    $q->where('name', 'New Arrivals');
                })->orderBy('created_at', 'desc');
            } elseif ($sortBy === 'sale') {
                // Sort by price for sale and offers
                $query->whereNotNull('price_sale')
                    ->whereColumn('price_sale', '<', 'price')
                    ->orderBy('price_sale', 'asc');
            } elseif ($sortBy === 'name-asc') {
                $query->orderBy('name', 'asc');
            } elseif ($sortBy === 'name-desc') {
                $query->orderBy('name', 'desc');
            } elseif ($sortBy === 'price-asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sortBy === 'price-desc') {
                $query->orderBy('price', 'desc');
            } else { // Default to 'featured' or alphabetical order
                // Filter products that have the "Trending" tag and order by name
                $query->whereHas('tag', function ($q) {
                    $q->where('name', 'Trending');
                })->orderBy('name');
            }
        } else {
            // Default sort order if no sort_by parameter is provided
            $query->orderBy('name');
        }

        // Fetch all matching products
        $products = $query->get();

        // Fetch specific brand details if brandId is provided
        $brand = $brandId ? Brand::findOrFail($brandId) : null;

        // Return the view with the filtered data
        return view('shop', compact('products', 'brand', 'categories', 'sizes', 'brands'));
    }

    public function UserSearch(Request $request)
    {
        $query = $request->input('query');

        // Search products by name
        $products = Product::where('name', 'LIKE', '%' . $query . '%')->get();

        // Fetch categories for search filtering if needed
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }
    public function sortBy($query, $sortBy)
    {
        switch ($sortBy) {
            case 'newest':
                return $query->whereHas('tag', function ($q) {
                    $q->where('name', 'New Arrivals');
                })->orderBy('created_at', 'desc');

            case 'sale':
                return $query->whereNotNull('price_sale')
                    ->whereColumn('price_sale', '<', 'price')
                    ->orderBy('price_sale', 'asc');
            case 'featured':
                return $query->whereHas('tag', function ($q) {
                    $q->where('name', 'Trending');
                })->orderBy('name');
            case 'name-asc':
                return $query->orderBy('name', 'asc');
            case 'name-desc':
                return $query->orderBy('name', 'desc');
            case 'price-asc':
                return $query->orderBy('price', 'asc');
            case 'price-desc':
                return $query->orderBy('price', 'desc');
            default:
                return $query->orderBy('name');
        }
    }

    public function productsByBrand(Request $request, $brandId = null)
    {
        // Validate incoming request parameters
        $validated = $request->validate([
            'category_id' => 'array|exists:categories,id',
            'sizes' => 'array',
            'sizes.*' => 'numeric', // Ensure sizes are numeric
            'brands' => 'array|exists:brands,id',
            'sort_by' => 'nullable|in:newest,sale,featured',
        ]);

        // Fetch all categories, sizes, and brands
        $categories = Category::all();
        $sizes = SizeCode::all();
        $brands = Brand::all();

        // Start building the query for products
        $query = Product::with(['brand', 'category', 'sizes', 'tag']);

        // Filter by brand if provided
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        // Filter by category if provided
        if (!empty($validated['category_id'] ?? null)) {
            $query->whereIn('category_id', $validated['category_id']);
        }



        // Filter by brands if provided (this is redundant since we already filtered by brand_id)
        if (!empty($validated['brands'] ?? null)) {
            $query->whereIn('brand_id', $validated['brands']);
        }

        // Sort products based on the 'sort_by' parameter if provided
        $sortBy = $validated['sort_by'] ?? null;
        if ($sortBy) {
            $query = $this->sortBy($query, $sortBy);
        } else {
            // Default sort order if no sort_by parameter is provided
            $query->orderBy('name');
        }

        // Fetch all matching products
        $products = $query->get();

        // Fetch specific brand details if brandId is provided
        $brand = $brandId ? Brand::findOrFail($brandId) : null;

        // Return the view with the filtered data
        return view('shop', compact('products', 'brand', 'categories', 'sizes', 'brands'));
    }

    public function productsByCategory(Request $request, $categoryId = null)
    {
        // Validate incoming request parameters
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'sizes' => 'array',
            'sizes.*' => 'numeric', // Ensure sizes are numeric
            'brands' => 'array|exists:brands,id',
            'sort_by' => 'nullable|in:newest,sale,featured',
        ]);

        // Fetch all categories, sizes, and brands
        $categories = Category::all();
        $sizes = SizeCode::all();
        $brands = Brand::all();

        // Start building the query for products
        $query = Product::with(['brand', 'category', 'sizes', 'tag']);

        // Filter by category if provided
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Filter by category IDs if provided in the request
        if (!empty($validated['category_id'] ?? null)) {
            $query->whereIn('category_id', $validated['category_id']);
        }

        // Filter by sizes if provided
        if (!empty($validated['sizes'] ?? null)) {
            $query->whereHas('sizes', function ($q) use ($validated) {
                $q->whereIn('sizenumber', $validated['sizes']);
            });
        }

        // Filter by brands if provided
        if (!empty($validated['brands'] ?? null)) {
            $query->whereIn('brand_id', $validated['brands']);
        }

        // Sort products based on the 'sort_by' parameter if provided
        $sortBy = $validated['sort_by'] ?? null;
        if ($sortBy) {
            $query = $this->sortBy($query, $sortBy);
        } else {
            // Default sort order if no sort_by parameter is provided
            $query->orderBy('name');
        }

        // Fetch all matching products
        $products = $query->get();

        // Fetch specific category details if categoryId is provided
        $category = $categoryId ? Category::findOrFail($categoryId) : null;

        // Return the view with the filtered data
        return view('shop', compact('products', 'category', 'categories', 'sizes', 'brands'));
    }

    /*---------------------------------------------------------ADMIN----------------------------------------------------------------*/     

    // Show products list for admin
    public function index()
    {
        $products = Product::with(['category', 'brand', 'tag'])->get();
        return view('admin.products.index', compact('products'));
    }

    // Add products form
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $tags = Tag::all();
        return view('admin.products.create', compact('categories', 'brands', 'tags'));
    }

    // Add products
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

        // Save Product
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->price_sale = $request->price_sale;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->tag_id = $request->tag_id;

        // Save images for product
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $destinationPath = public_path('images');
                $image->move($destinationPath, $imageName);
                $imagePaths[] = 'images/' . $imageName;
            }
        }

        $product->image = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
        $product->save();

        // Save sizes and quantities
        $sizes = $request->size;
        $quantities = $request->quantity;

        if (count($sizes) === count($quantities)) {
            for ($i = 0; $i < count($sizes); $i++) {
                SizeCode::create([
                    'sizenumber' => $sizes[$i],
                    'product_id' => $product->id,
                    'quantity' => $quantities[$i],
                ]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Product and sizes created successfully!');
    }

    // Update product form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $tags = Tag::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'tags'));
    }

    // Update product
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

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
            'description' => $request->description,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'tag_id' => $request->tag_id,
        ]);

        $imagePaths = json_decode($product->image, true) ?? [];
        $destinationPath = public_path('images');
        $newImages = [];

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move($destinationPath, $imageName);
                $newImages[] = 'images/' . $imageName;
            }
        }
        // Updated image processing
        if ($request->has('updated_images')) {
            foreach ($request->updated_images as $key => $updatedImage) {
                if ($updatedImage) {
                    // Delete old image
                    $oldImagePath = public_path($imagePaths[$key]);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    // Save new image
                    $imageName = time() . '-' . $updatedImage->getClientOriginalName();
                    $updatedImage->move($destinationPath, $imageName);
                    $imagePaths[$key] = 'images/' . $imageName; // Update image path
                }
            }
        }

        // Removed image processing
        if ($request->removed_images) {
            $removedImages = json_decode($request->removed_images);
            foreach ($removedImages as $key) {
                $oldImagePath = public_path($imagePaths[$key]);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // delete old image path from filesystem if it exists already
                }
                unset($imagePaths[$key]); // remove it from the array of paths to save
            }
        }

        // Save the updated image paths
        $product->image = json_encode(array_merge($imagePaths, $newImages), JSON_UNESCAPED_SLASHES);
        $product->save();

        // Update sizes and quantities
        $product->sizes()->delete();
        if (count($request->size) === count($request->quantity)) {
            foreach ($request->size as $index => $size) {
                $product->sizes()->updateOrCreate(
                    ['sizenumber' => $size],
                    ['quantity' => $request->quantity[$index]]
                );
            }
        }
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            foreach (json_decode($product->image) as $imagePath) {
                $oldImagePath = public_path($imagePath);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa!');
    }

    // Search for products by name
    public function search(Request $request)
    {
        $query = Product::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        $products = $query->with(['category', 'brand', 'tag'])->get();
        return view('admin.products.index', compact('products'));
    }

    // Show product details (user)
    public function show($id)
    {
        // Get the product from the database
        $product = Product::find($id);
        // Check if the product is found
        if (!$product) {
            abort(404, 'Product not found');
        }
        // Decode the JSON string and assign it to the 'images' property
        $product->images = $product->image ? json_decode($product->image, true) : [];
        return view('user.detail', compact('product'));
    }
}
