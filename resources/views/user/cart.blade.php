@extends('layouts.front')

@section('content')
    <div class="container">
        <h1>Giỏ hàng của bạn</h1>

        @if ((empty($cartItems)))
            <p>Giỏ hàng của bạn đang trống.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Hình ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Size</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}">
                            </td>

                            {{-- <td>
                                @php
                                    $images = json_decode($item->product_image, true);
                                    $isArray = is_array($images);
                                    $count = is_array($images) ? count($images) : 0;
                                @endphp

                                <pre>
                                    Images: {{ print_r($images, true) }}
                                    Is Array: {{ $isArray ? 'true' : 'false' }}
                                    Count: {{ $count }}
                                    First Image: {{ $images[0] ?? 'Không tồn tại' }}
                                </pre>

                                @if ($isArray && $count > 0)
                                    <img src="{{ asset($images[0]) }}" alt="Product Thumbnail" class="thumb">
                                @else
                                    <p>Không có hình ảnh nào.</p>
                                @endif

                            </td> --}}

                            {{-- <td>
                                @php
                                    $originalString = $item->product_image;
                                    $decodedString = html_entity_decode($originalString); // Giải mã nếu cần
                                    $images = json_decode($decodedString, true);
                                    $isArray = is_array($images);
                                    $count = $isArray ? count($images) : 0;
                                @endphp

                                <pre>
                                        Original Product Image: {{ $originalString }}
                                        Decoded Product Image: {{ $decodedString }}
                                        Images: {{ print_r($images, true) }}
                                        Is Array: {{ $isArray ? 'true' : 'false' }}
                                        Count: {{ $count }}
                                        JSON Error: {{ json_last_error_msg() }}
                                        First Image: {{ $images[0] ?? 'Không tồn tại' }}
                                    </pre>

                                @if ($isArray && $count > 0)
                                    <img src="{{ asset($images[0]) }}" alt="Product Thumbnail" class="thumb">
                                @else
                                    <p>Không có hình ảnh nào.</p>
                                @endif
                            </td> --}}

                            <td>
                                @php
                                    $originalString = $item->product_image;

                                    $decodedString = json_decode($originalString, true);

                                    // Kiểm tra xem chuỗi có phải là JSON hay không
                                    if (is_string($decodedString)) {
                                        //  Nếu chuỗi vẫn là JSON, tiếp tục giải mã lần thứ hai
                                        $decodedString = json_decode($decodedString, true);
                                    }

                                    $images = $decodedString;
                                    $isArray = is_array($images);
                                    $count = $isArray ? count($images) : 0;
                                @endphp

                                {{-- <pre>
                                    Original Product Image: {{ $originalString }}
                                    Images After Double Decode: {{ print_r($images, true) }}
                                    Is Array: {{ $isArray ? 'true' : 'false' }}
                                    Count: {{ $count }}
                                    JSON Error: {{ json_last_error_msg() }}
                                    First Image: {{ $images[0] ?? 'Không tồn tại' }}
                                </pre> --}}

                                @if ($isArray && $count > 0)
                                    <img src="{{ asset($images[0]) }}" width="80px" alt="Product Thumbnail" class="thumb">
                                @else
                                    <p>Không có hình ảnh nào.</p>
                                @endif
                            </td>

                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->size_number }}</td>
                            <td>
                                <form action="{{ route('cart.decrease', $item->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">-</button>
                                </form>
                                {{ $item->product_quantity }}
                                <form action="{{ route('cart.increase', $item->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">+</button>
                                </form>
                            </td>
                            <td>{{ number_format($item->product_price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ number_format($item->product_price * $item->product_quantity, 0, ',', '.') }} VNĐ</td>
                            <td>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h4>Tổng giá: {{ number_format($totalPrice, 0, ',', '.') }} VNĐ</h4>
        @endif

        <a href="{{ route('dashboard') }}" class="btn btn-primary">Tiếp tục mua sắm</a>

    </div>
@endsection
