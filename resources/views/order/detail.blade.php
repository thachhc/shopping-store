@extends('layouts.front')

@section('meta')
<meta name="description" content="Order Details Page">
@endsection

@section('title')
<title>Order Details</title>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/order/detail.css') }}">
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <h1 class="page-title">Order List</h1>

    @foreach ($ordersWithDetails as $order)
    <div class="order-summary">
        <h2 class="order-status @if($order->status == 'Pending') order-status-pending
                                @elseif($order->status == 'Processed') order-status-processed
                                @elseif($order->status == 'In Transit') order-status-in-transit
                                @elseif($order->status == 'Delivered') order-status-delivered
                                @endif">
            <i class="fas 
                        @if($order->status == 'Pending') fa-clock
                        @elseif($order->status == 'Processed') fa-check-circle
                        @elseif($order->status == 'In Transit') fa-truck
                        @elseif($order->status == 'Delivered') fa-gift
                        @endif status-icon"></i>
            Order #{{ $order->id }} Details
        </h2>
        <div class="order-info">
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} VND</p>
            <p><strong>Payment Method:</strong> {{ $order->method_payment }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
        </div>

        <h3 class="order-items-title">Product List</h3>
        <div class="order-items">
            @foreach ($order->items as $item)
            <div class="order-item">
                <h4>{{ $item->product_name }}</h4>
                <p><strong>Size:</strong> {{ $item->size_number }}</p>
                <p><strong>Quantity:</strong> {{ $item->product_quantity }}</p>
                <p><strong>Price:</strong> {{ number_format($item->product_price_sale ?? $item->product_price, 0, ',', '.') }} VND</p>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection
