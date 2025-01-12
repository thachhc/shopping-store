<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->get(); // Lấy danh sách tất cả đơn hàng
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderDetails.cartDetail')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(['order' => $order]);
    }


    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();

        return redirect()->route('orders.index');
    }

    public function getProducts($order_id)
    {
        try {
            $order = Order::with('customer')->find($order_id);
            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Order not found']);
            }

            // Sử dụng DB::select để thực hiện câu truy vấn trực tiếp
            $products = DB::select("
            SELECT p.*, cs.sizenumber, cd.product_quantity
            FROM products p
            JOIN code_sizes cs ON p.id = cs.product_id
            JOIN cart_details cd ON cs.id = cd.size_id
            JOIN order_details od ON cd.id = od.id_cart_detail
            WHERE od.order_id = :order_id
        ", ['order_id' => $order_id]);

            $productsData = [];

            // Duyệt qua sản phẩm và tính toán giá và tổng tiền
            foreach ($products as $product) {
                $price = $product->price_sale ?: $product->price; // Nếu có giá sale thì lấy giá sale, nếu không thì lấy giá gốc
                $total = $price * $product->product_quantity;

                $productsData[] = [
                    'name' => $product->name,
                    'quantity' => $product->product_quantity,
                    'size' => $product->sizenumber,
                    'price' => $price,
                    'total' => $total,
                ];
            }

            // Kiểm tra xem $productsData có phải là mảng không
            if (empty($productsData)) {
                return response()->json(['status' => 'error', 'message' => 'No products found']);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_amount' => $order->total_amount,
                    'customer_name' => $order->customer->name,
                    'payment_method' => $order->method_payment,
                    'products' => $productsData
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function search(Request $request)
    {
        // Kiểm tra nếu có từ khóa tìm kiếm
        $searchTerm = $request->input('customer_name');

        // Truy vấn tìm kiếm theo tên khách hàng
        $orders = Order::with('customer') // Lấy dữ liệu khách hàng
            ->whereHas('customer', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->get();

        // Trả về kết quả tìm kiếm qua view
        return view('admin.orders.index', compact('orders'));
    }
}
