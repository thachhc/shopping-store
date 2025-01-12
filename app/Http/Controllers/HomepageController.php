<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SizeCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomepageController extends Controller
{
    // Display homepage with all dynamic content
    public function index()
    {
        // Fetch random products for display
        $randomProducts = Product::with('brand', 'category')->inRandomOrder()->take(10)->get();

        // Fetch all brands and categories
        $brands = Brand::all();
        $categories = Category::all();

        // Fetch "Don't Miss" images
        [$poster1Images, $poster2Images] = $this->getDontMissImages();

        return view('welcome', compact('randomProducts', 'brands', 'categories', 'poster1Images', 'poster2Images'));
    }

    // Fetch "Don't Miss" images from storage
    private function getDontMissImages()
    {
        $dmPath = public_path('storage/DM');
        $dontMissImages = [];

        if (File::exists($dmPath)) {
            foreach (File::files($dmPath) as $file) {
                if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $dontMissImages[] = asset("storage/DM/" . $file->getFilename());
                }
            }
        }

        shuffle($dontMissImages);

        // Split the images into two arrays
        $poster1Images = array_slice($dontMissImages, 0, ceil(count($dontMissImages) / 2));
        $poster2Images = array_slice($dontMissImages, ceil(count($dontMissImages) / 2));

        return [$poster1Images, $poster2Images];
    }

    // Display products for a specific brand
    public function productsByBrand(Request $request, $brandId)
    {
        $categories = Category::all(); // Fetch all categories
        $brand = Brand::findOrFail($brandId); // Fetch specific brand

        // Start query for brand products
        $products = Product::where('brand_id', $brandId);

        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $products->where('category_id', $request->category_id);
        }

        $sizes = SizeCode::all();

        // Execute query
        $products = $products->get();

        return view('browse.browse', compact('products', 'brand', 'categories'));
    }

    // Display product details
    public function show($id)
    {
        $product = Product::with(['brand', 'category', 'codeSizes', 'tag'])->findOrFail($id);

        return view('user.detail', compact('product'));
    }
}