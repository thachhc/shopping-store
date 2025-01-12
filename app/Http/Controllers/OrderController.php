<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Facades\DB;   // Import DB
use Illuminate\Support\Facades\Log; // Import Log
use Illuminate\Http\Request;


class OrderController extends Controller
{

    protected $table = 'order';

    public function index(Request $request)
    {
        // Get the authenticated customer ID
        $customerId = Auth::id();
        if (!$customerId) {
            return redirect()->route('login')->withErrors('Vui lòng đăng nhập để xem giỏ hàng.');
        }

        // Retrieve selected item IDs from the request
        $selectedItems = $request->input('selected_items', []);
        // dd($selectedItems); // Debug: Check the selected items

        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->withErrors('Vui lòng chọn ít nhất một sản phẩm.');
        }

        // Fetch details of the selected items, including size_id
        $cartItems = DB::table('cart_details')
            ->join('code_sizes', 'cart_details.size_id', '=', 'code_sizes.id')
            ->join('products', 'code_sizes.product_id', '=', 'products.id')
            ->select(
                'cart_details.id as cart_id', // Ensure uniqueness
                'cart_details.size_id', // Include size_id
                'cart_details.product_quantity',
                'products.name as product_name',
                'products.price as product_price',
                'products.price_sale as product_price_sale',
                'products.image as product_image',
                'code_sizes.sizenumber as size_number'
            )
            ->whereIn('cart_details.id', $selectedItems) // Filter by selected item IDs
            ->get();

        // Ensure items were retrieved
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->withErrors('Không tìm thấy sản phẩm đã chọn.');
        }

        // Calculate the total price for the selected items
        $totalPrice = $cartItems->sum(function ($item) {
            $itemPrice = $item->product_price_sale ?? $item->product_price ?? 0;
            return $itemPrice * $item->product_quantity;
        });

        return view('order.order', compact('cartItems', 'totalPrice'));
    }

    public function buyNow(Request $request)
    {
        // Get the authenticated customer ID
        $customerId = Auth::id();
        if (!$customerId) {
            return redirect()->route('login')->withErrors('Vui lòng đăng nhập để đặt hàng.');
        }

        // Validate the `items` input
        $validated = $request->validate([
            'items' => 'required|string' // Ensure `items` is provided as a string
        ]);

        // Decode the JSON `items` input
        $items = json_decode($validated['items'], true);

        // Validate the decoded items array
        if (!is_array($items) || empty($items)) {
            return redirect()->route('cart.index')->withErrors('Dữ liệu sản phẩm không hợp lệ.');
        }

        // Prepare the cart items for the view
        $cartItems = collect();
        $totalPrice = 0;

        foreach ($items as $item) {
            // Ensure size_id and quantity are present and valid
            if (!isset($item['size_id'], $item['quantity']) || !is_numeric($item['size_id']) || !is_numeric($item['quantity'])) {
                return redirect()->route('cart.index')->withErrors('Dữ liệu sản phẩm không hợp lệ.');
            }

            // Fetch the product associated with the size_id
            $product = DB::table('code_sizes')
                ->join('products', 'code_sizes.product_id', '=', 'products.id')
                ->select(
                    'products.id as product_id',
                    'products.name as product_name',
                    'products.price as product_price',
                    'products.price_sale as product_price_sale',
                    'products.image as product_image',
                    'code_sizes.sizenumber as size_number'
                )
                ->where('code_sizes.id', $item['size_id'])
                ->first();

            if (!$product) {
                return redirect()->route('cart.index')->withErrors('Không tìm thấy sản phẩm.');
            }

            // Calculate item price
            $itemPrice = $product->product_price_sale ?? $product->product_price ?? 0;
            $itemTotal = $itemPrice * $item['quantity'];
            $totalPrice += $itemTotal;

            // Add the item to the cartItems collection
            $cartItems->push((object) [
                'cart_id' => uniqid(), // Generate a unique ID for the item
                'size_id' => $item['size_id'],
                'product_quantity' => $item['quantity'],
                'product_name' => $product->product_name,
                'product_price' => $product->product_price,
                'product_price_sale' => $product->product_price_sale,
                'product_image' => $product->product_image,
                'size_number' => $product->size_number,
            ]);

            // dd($cartItems); // Debug: Check the cart items
        }

        // Return the order view with the calculated cart items and total price
        return view('order.ordernow', compact('cartItems', 'totalPrice'));
    }

    public function show()
    {
        // Get the authenticated customer ID
        $customerId = Auth::id();
        if (!$customerId) {
            return redirect()->route('login')->withErrors('Vui lòng đăng nhập để xem các đơn hàng.');
        }

        // Fetch all orders for the authenticated customer
        $orders = Order::with('orderDetails')->where('customer_id', $customerId)->get();

        if ($orders->isEmpty()) {
            return redirect()->route('home')->withErrors('Bạn chưa có đơn hàng nào.');
        }

        // Fetch the details of each order item
        $ordersWithDetails = $orders->map(function ($order) {
            $orderItems = DB::table('order_details')
                ->join('cart_details', 'order_details.id_cart_detail', '=', 'cart_details.id')
                ->join('code_sizes', 'cart_details.size_id', '=', 'code_sizes.id')
                ->join('products', 'code_sizes.product_id', '=', 'products.id')
                ->select(
                    'products.name as product_name',
                    'products.price as product_price',
                    'products.price_sale as product_price_sale',
                    'cart_details.product_quantity',
                    'code_sizes.sizenumber as size_number',
                    'products.image as product_image'
                )
                ->where('order_details.order_id', $order->id)
                ->get();

            $order->items = $orderItems;
            return $order;
        });

        return view('order.detail', compact('ordersWithDetails'));
    }


    public function placeOrder(Request $request)
    {
        try {
            Log::info('Dữ liệu request đặt hàng: ', $request->all());

            // Get the authenticated customer ID
            $customerId = Auth::id();
            if (!$customerId) {
                return redirect()->back()->withErrors('Vui lòng đăng nhập để đặt hàng.');
            }

            // Decode the order items from the request
            $orderItems = json_decode($request->input('items', '[]'), true);
            // dd($orderItems); // Debug: See the contents of the order items

            if (empty($orderItems)) {
                return response()->json(['error' => 'No items received.'], 400);
            }

            // Create a new order
            $order = Order::create([
                'customer_id' => $customerId,
                'total_amount' => 0, // Will be updated later
                'method_payment' => $request->input('method_payment', 'cash'),
                'status' => 'pending',
            ]);

            $totalPrice = 0; // Initialize total price

            foreach ($orderItems as $item) {
                $sizeId = $item['size_id'] ?? null;
                $quantityToOrder = $item['product_quantity'] ?? 0;

                if (!$sizeId || $quantityToOrder <= 0) {
                    return redirect()->back()->withErrors('Dữ liệu sản phẩm không hợp lệ.');
                }

                // Fetch size details from code_sizes table
                $size = DB::table('code_sizes')->where('id', $sizeId)->first();
                // dd($size); // Debug: Check if the size data is correctly retrieved

                if (!$size) {
                    return redirect()->back()->withErrors("Kích thước sản phẩm không tồn tại (Size ID: $sizeId).");
                }

                // Ensure requested quantity is within stock
                if ($quantityToOrder > $size->quantity) {
                    return redirect()->back()->withErrors('Số lượng yêu cầu vượt quá số lượng có sẵn trong kho.');
                }

                // Fetch product information linked to size
                $product = DB::table('products')
                    ->join('code_sizes', 'products.id', '=', 'code_sizes.product_id')
                    ->where('code_sizes.id', $sizeId)
                    ->select('products.id as product_id', 'products.price', 'products.price_sale')
                    ->first();
                // dd($product); // Debug: Check if product data is correctly retrieved

                if (!$product) {
                    return redirect()->back()->withErrors('Sản phẩm không tồn tại.');
                }

                // Calculate item total
                $itemPrice = $product->price_sale ?? $product->price ?? 0;
                $itemTotal = $itemPrice * $quantityToOrder;

                // Update total price
                $totalPrice += $itemTotal;

                // Get cart detail ID
                $cartDetail = DB::table('cart_details')->where('size_id', $sizeId)->first();
                // dd($cartDetail); // Debug: Check if cart detail is correctly retrieved

                if (!$cartDetail) {
                    return redirect()->back()->withErrors('Cart detail không tồn tại.');
                }

                // Add order detail
                DB::table('order_details')->insert([
                    'order_id' => $order->id,
                    'id_cart_detail' => $cartDetail->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Decrease stock quantity in code_sizes table
                DB::table(table: 'code_sizes')->where('id', $sizeId)->decrement('quantity', $quantityToOrder);
            }

            // Update total amount in order
            $order->update(['total_amount' => $totalPrice]);
            // dd($order); // Debug: Confirm the final order object

            return redirect()->to('/orderdetails')->with('success', 'Đơn hàng đã được đặt thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi đặt hàng: ', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors('Đã xảy ra lỗi khi đặt hàng.');
        }
    }

    public function placeOrderNow(Request $request)
    {
        try {
            Log::info('Dữ liệu request đặt hàng: ', $request->all());

            // Get the authenticated customer ID
            $customerId = Auth::id();
            if (!$customerId) {
                return redirect()->back()->withErrors('Vui lòng đăng nhập để đặt hàng.');
            }

            // Decode the order items from the request
            $orderItems = json_decode($request->input('items', '[]'), true);
            // dd($orderItems); // Debug: See the contents of the order items

            if (empty($orderItems)) {
                return response()->json(['error' => 'No items received.'], 400);
            }

            // Create a new order
            $order = Order::create([
                'customer_id' => $customerId,
                'total_amount' => 0, // Will be updated later
                'method_payment' => $request->input('method_payment', 'cash'),
                'status' => 'pending',
            ]);

            // dd(vars: $order); // Debug: Check if the order is created successfully

            $totalPrice = 0; // Initialize total price

            foreach ($orderItems as $item) {
                $sizeId = $item['size_id'] ?? null;
                $quantityToOrder = $item['quantity'] ?? 0;

                if (!$sizeId || $quantityToOrder <= 0) {
                    return redirect()->back()->withErrors('Dữ liệu sản phẩm không hợp lệ.');
                }

                // Fetch size details from code_sizes table
                $size = DB::table('code_sizes')->where('id', $sizeId)->first();
                // dd($size); // Debug: Check if the size data is correctly retrieved

                if (!$size) {
                    return redirect()->back()->withErrors("Kích thước sản phẩm không tồn tại (Size ID: $sizeId).");
                }

                // Ensure requested quantity is within stock
                if ($quantityToOrder > $size->quantity) {
                    return redirect()->back()->withErrors('Số lượng yêu cầu vượt quá số lượng có sẵn trong kho.');
                }

                // Fetch product information linked to size
                $product = DB::table('products')
                    ->join('code_sizes', 'products.id', '=', 'code_sizes.product_id')
                    ->where('code_sizes.id', $sizeId)
                    ->select('products.id as product_id', 'products.price', 'products.price_sale')
                    ->first();
                dd($product); // Debug: Check if product data is correctly retrieved

                if (!$product) {
                    return redirect()->back()->withErrors('Sản phẩm không tồn tại.');
                }

                // Calculate item total
                $itemPrice = $product->price_sale ?? $product->price ?? 0;
                $itemTotal = $itemPrice * $quantityToOrder;

                // Update total price
                $totalPrice += $itemTotal;

                // Add order detail
                DB::table('order_details')->insert([
                    'order_id' => $order->id,
                    'id_cart_detail' => $cartDetail->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Decrease stock quantity in code_sizes table
                DB::table(table: 'code_sizes')->where('id', $sizeId)->decrement('quantity', $quantityToOrder);
            }

            // Update total amount in order
            $order->update(['total_amount' => $totalPrice]);
            dd($order); // Debug: Confirm the final order object

            return redirect()->to('/orderdetails')->with('success', 'Đơn hàng đã được đặt thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi đặt hàng: ', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors('Đã xảy ra lỗi khi đặt hàng.');
        }
    }



    
}
