@extends('layouts.front')

@section('meta')
<meta name="description" content="Place your order">
@endsection

@section('title')
<title>Place Order</title>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/order/order.css') }}">
@endsection

@section('content')
<div class="container">
    <!-- <h1>Đặt hàng</h1> -->

    <!-- Display cart items -->
    <div class="cart-items">
        <!-- <h2>Giỏ hàng của bạn</h2> -->
        <table class="table">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Kích thước</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->size_number }}</td>
                    <td>{{ number_format($item->product_price, 0, ',', '.') }} VNĐ</td>
                    <td>{{ $item->product_quantity }}</td>
                    <td>{{ number_format($item->product_price * $item->product_quantity, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Order form -->
    <div class="order-form">
        <h2>Thông tin đặt hàng</h2>
        <form action="{{ route('order.place') }}" method="POST">
            @csrf
            <!-- Add hidden input to pass the items array -->
            <input type="hidden" name="items" id="items" value="{{ json_encode($cartItems) }}">

            <div class="form-group">
                <label for="method_payment">Phương thức thanh toán</label>
                <select name="method_payment" id="method_payment" class="form-control">
                    <option value="cash">Thanh toán khi nhận hàng</option>
                    <option value="credit_card">Thẻ tín dụng</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Đặt hàng</button>
        </form>
    </div>

    <!-- Order summary -->
    <div class="order-summary">
        <h3>Tổng số tiền: {{ number_format($totalPrice, 0, ',', '.') }} VNĐ</h3>
    </div>
</div>
@endsection