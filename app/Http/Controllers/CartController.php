<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Facades\DB;   // Import DB
use Illuminate\Support\Facades\Log; // Import Log
use App\Models\Cart;
use App\Models\CartDetail;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            // Ghi log thông tin yêu cầu
            Log::info('Dữ liệu request: ', $request->all());

            // Lấy thông tin `customer_id` từ session hoặc thông qua cách khác
            $customerId = Auth::id(); // Nếu bạn dùng auth, thay đổi theo cách bạn lưu thông tin user
            if (!$customerId) {
                return redirect()->back()->withErrors('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
            }

            $sizeId = $request->input('size_id');
            $quantity = $request->input('quantity', 1);

            // Kiểm tra xem giỏ hàng đã tồn tại chưa
            $cart = DB::table('carts')->where('customer_id', $customerId)->first();

            if (!$cart) {
                // Tạo mới giỏ hàng
                $cartId = DB::table('carts')->insertGetId([
                    'customer_id' => $customerId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $cartId = $cart->id;
            }

            // Kiểm tra xem sản phẩm đã có trong chi tiết giỏ hàng chưa
            $cartDetail = DB::table('cart_details')->where([
                ['id_cart', '=', $cartId],
                ['size_id', '=', $sizeId],
            ])->first();

            if ($cartDetail) {
                // Cập nhật số lượng nếu đã tồn tại
                DB::table('cart_details')->where('id', $cartDetail->id)->update([
                    'product_quantity' => $cartDetail->product_quantity + $quantity,
                    'updated_at' => now(),
                ]);
            } else {
                // Thêm mới nếu chưa tồn tại
                DB::table('cart_details')->insert([
                    'id_cart' => $cartId,
                    'size_id' => $sizeId,
                    'product_quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
        } catch (\Exception $e) {
            Log::error('Lỗi thêm vào giỏ hàng: ', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors('Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng.');
        }
    }

    public function index()
    {
        try {
            $customerId = Auth::id(); // Lấy customer_id từ Auth
            if (!$customerId) {
                return redirect()->route('login')->withErrors('Vui lòng đăng nhập để xem giỏ hàng.');
            }

            $cart = DB::table('carts')->where('customer_id', $customerId)->first();

            if (!$cart) {
                return view('user.cart', [
                    'cartItems' => [],
                    'totalPrice' => 0,
                ]);
            }

            // Lấy chi tiết giỏ hàng
            $cartItems = DB::table('cart_details')
                ->join('code_sizes', 'cart_details.size_id', '=', 'code_sizes.id')
                ->join('products', 'code_sizes.product_id', '=', 'products.id')
                ->select(
                    'cart_details.*',
                    'products.name as product_name',
                    'products.price as product_price',
                    'products.image as product_image',  // Lấy cột image
                    'code_sizes.sizenumber as size_number'
                )
                ->where('cart_details.id_cart', $cart->id)
                ->get();

            // Thêm ảnh đầu tiên vào mỗi item nếu có
            foreach ($cartItems as $item) {
                // Giải mã JSON (dữ liệu trong cột product_image)
                $images = json_decode($item->product_image, true);

                // Xử lý đường dẫn ảnh
                if (is_array($images) && count($images) > 0) {
                    // Lấy ảnh đầu tiên trong danh sách
                    $item->first_image = asset($images[0]);
                } else {
                    // Nếu không có ảnh, sử dụng ảnh mặc định
                    $item->first_image = asset('images/default-image.jpg');
                }
            }
            foreach ($cartItems as $item) {
                $item->decoded_images = json_decode(stripslashes($item->product_image), true) ?? [];
            }
            // Tính tổng giá
            $totalPrice = $cartItems->reduce(function ($carry, $item) {
                return $carry + ($item->product_price * $item->product_quantity);
            }, 0);

            return view('user.cart', compact('cartItems', 'totalPrice'));
        } catch (\Exception $e) {
            Log::error('Lỗi hiển thị giỏ hàng: ', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors('Đã xảy ra lỗi khi hiển thị giỏ hàng.');
        }
    }


    public function remove($id)
    {
        try {
            // Xóa mục giỏ hàng dựa vào ID trong bảng `cart_details`
            DB::table('cart_details')->where('id', $id)->delete();

            // Điều hướng lại đến trang giỏ hàng với thông báo thành công
            return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
        } catch (\Exception $e) {
            Log::error('Lỗi xóa sản phẩm trong giỏ hàng:', ['message' => $e->getMessage()]);

            // Điều hướng lại với thông báo lỗi
            return redirect()->route('cart.index')->withErrors('Xóa sản phẩm thất bại.');
        }
    }

    public function increaseQuantity($id)
    {
        try {
            // Lấy mục giỏ hàng
            $cartItem = DB::table('cart_details')->where('id', $id)->first();

            // Lấy thông tin size sản phẩm để kiểm tra số lượng trong kho
            $size = DB::table('code_sizes')->where('id', $cartItem->size_id)->first();

            if (!$cartItem || !$size) {
                return redirect()->route('cart.index')->withErrors('Sản phẩm không tồn tại.');
            }

            // Kiểm tra số lượng không vượt quá số lượng trong kho
            if ($cartItem->product_quantity >= $size->quantity) {
                return redirect()->route('cart.index')->withErrors('Không đủ hàng trong kho.');
            }

            // Tăng số lượng sản phẩm trong giỏ hàng
            DB::table('cart_details')->where('id', $id)->increment('product_quantity');

            return redirect()->route('cart.index')->with('success', 'Đã tăng số lượng sản phẩm.');
        } catch (\Exception $e) {
            Log::error('Lỗi tăng số lượng sản phẩm:', ['message' => $e->getMessage()]);
            return redirect()->route('cart.index')->withErrors('Không thể tăng số lượng sản phẩm.');
        }
    }

    public function decreaseQuantity($id)
    {
        try {
            // Lấy mục giỏ hàng
            $cartItem = DB::table('cart_details')->where('id', $id)->first();

            if (!$cartItem) {
                return redirect()->route('cart.index')->withErrors('Sản phẩm không tồn tại.');
            }

            // Kiểm tra số lượng không được nhỏ hơn 1
            if ($cartItem->product_quantity <= 1) {
                return redirect()->route('cart.index')->withErrors('Số lượng sản phẩm không thể nhỏ hơn 1.');
            }

            // Giảm số lượng sản phẩm trong giỏ hàng
            DB::table('cart_details')->where('id', $id)->decrement('product_quantity');

            return redirect()->route('cart.index')->with('success', 'Đã giảm số lượng sản phẩm.');
        } catch (\Exception $e) {
            Log::error('Lỗi giảm số lượng sản phẩm:', ['message' => $e->getMessage()]);
            return redirect()->route('cart.index')->withErrors('Không thể giảm số lượng sản phẩm.');
        }
    }

    public function indexForDisplay()
    {
        try {
            $customerId = Auth::id(); // Lấy customer_id từ Auth
            if (!$customerId) {
                return redirect()->route('login')->withErrors('Vui lòng đăng nhập để xem giỏ hàng.');
            }

            $cart = DB::table('carts')->where('customer_id', $customerId)->first();

            if (!$cart) {
                return view('user.cart', [
                    'cartItems' => [],
                    'totalPrice' => 0,
                ]);
            }

            $cartItems = DB::table('cart_details')
                ->join('code_sizes', 'cart_details.size_id', '=', 'code_sizes.id')
                ->join('products', 'code_sizes.product_id', '=', 'products.id')
                ->select(
                    'cart_details.*',
                    'products.name as product_name',
                    'products.price as product_price',
                    'products.image as product_image',
                    'code_sizes.sizenumber as size_number'
                )
                ->where('cart_details.id_cart', $cart->id)
                ->get();

            $totalPrice = $cartItems->reduce(function ($carry, $item) {
                return $carry + ($item->product_price * $item->product_quantity);
            }, 0);

            return view('user.cart', compact('cartItems', 'totalPrice'));
        } catch (\Exception $e) {
            Log::error('Lỗi hiển thị giỏ hàng: ', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors('Đã xảy ra lỗi khi hiển thị giỏ hàng.');
        }
    }
}
