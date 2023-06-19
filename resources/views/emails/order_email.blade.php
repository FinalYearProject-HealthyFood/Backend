<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hóa Đơn Hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details th,
        .invoice-details td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Hóa Đơn Đặt Hàng</h1>
        </div>

        <div class="greeting">
            <p>Kính chào, {{ $name }},</p>
        </div>
        <div class="body-content">
            @if ($order->status == 'pending')
                <p>Cảm ơn đơn đặt hàng gần đây của bạn. Chúng tôi đang xử lý đơn hàng của bạn.</p>
            @elseif ($order->status == 'accepted')
                <p>Cảm ơn đơn đặt hàng gần đây của bạn. Chúng tôi đã xác nhận đơn hàng của bạn, đơn hàng của bạn đang
                    được vận chuyển đến nơi bạn.</p>
            @elseif ($order->status == 'delivered')
                <p>Đơn hàng đã giao thành công. Cảm ơn bạn đã chọn chúng tôi. Chúc bạn có 1 buổi ăn healthy và vui vẻ.
                </p>
            @else
                <p>Đơn hàng của bạn đã bị hủy bỏ. Chúng tôi xin lỗi bạn vì sự bất tiện này và đã làm mất thời gian của
                    bạn, đơn hàng của bạn hiện tại cửa hàng của chúng tối không thể chu cấp được. Chúng tôi vô cùng xin
                    lỗi.
                </p>
            @endif
            <p>Dưới đây là chi tiết hóa đơn của bạn:</p>
            <!-- Add additional information or instructions here -->
        </div>
        <div class="invoice-details">
            <table>
                <tr>
                    <th>Order ID:</th>
                    <td>{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Tình trạng đơn hàng:</th>
                    <td @style([
                        'color: orange' => $order->status == 'pending',
                        'color: green' => $order->status == 'accepted',
                        'color: blue' => $order->status == 'delivered',
                        'color: red' => $order->status == 'canceled',
                    ])>
                        @if ($order->status == 'pending')
                            Đang xử lý
                        @elseif ($order->status == 'accepted')
                            Đã được chấp nhận
                        @elseif ($order->status == 'delivered')
                            Đã giao
                        @else
                            Đã hủy bỏ
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Ngày đặt:</th>
                    <td>{{ date_format($order->created_at, 'H:i d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Tên khách hàng:</th>
                    <td>{{ $name }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ người nhận:</th>
                    <td>{{ $order->delivery_address }}</td>
                </tr>
                <tr>
                    <th>Số điện thoại người nhận:</th>
                    <td>{{ $order->phone }}</td>
                </tr>
                <tr>
                    <th>Đơn giá:</th>
                    <td>{{ number_format($order->total_price) }} vnđ</td>
                </tr>
                <!-- Add more details as needed -->
            </table>
        </div>
        <div class="body-content">
            <p>Danh sách sản phẩm:</p>
            <!-- Add additional information or instructions here -->
        </div>
        <div class="invoice-details">

            <table>
                <tr>
                    <th>
                        STT
                    </th>
                    <th>
                        Sản phẩm
                    </th>
                    <th>
                        Số lượng
                    </th>
                    <th>
                        Chi tiết
                    </th>
                    <th>
                        Giá
                    </th>
                </tr>
                @if (isset($order))
                    @foreach ($orderItem as $key => $item)
                        @if ($item->meal_id)
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                <td>
                                    {{ $item->meal->name }}
                                </td>
                                <td>
                                    {{ number_format($item->meal->serving_size * $item->quantity) }} grams
                                </td>
                                <td>
                                    @if (count($item->meal->ingredients) > 0)
                                        <ul>

                                            @foreach ($item->meal->ingredients as $key => $ingredient)
                                                <li>
                                                    {{ $ingredient->name }} x
                                                    {{ number_format($ingredient->serving_size * $ingredient->pivot->quantity) }}
                                                    grams</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        None
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($item->total_price) }} vnđ
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    {{ $key }}
                                </td>
                                <td>
                                    {{ $item->ingredient->name }}
                                </td>
                                <td>
                                    {{ number_format($item->ingredient->serving_size * $item->quantity) }} grams
                                </td>
                                <td>
                                    none
                                </td>
                                <td>
                                    {{ number_format($item->total_price) }} vnđ
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                <tr>
                    <th colspan="3">
                        Tổng giá thành
                    </th>
                    <th colspan="2">
                        {{ number_format($order->total_price) }} vnđ
                    </th>
                </tr>
            </table>
        </div>

        <div class="signature">
            <p>Trân trọng,</p>
            <p>Healthy Food Store</p>
        </div>

        <div class="footer">
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với nhóm hỗ trợ của chúng tôi.</p>
            <p>Gmail: healthyfoodstore@gmail.com</p>
            <p>Số điện thoại: 84 83 123 4567</p>
        </div>
    </div>
</body>

</html>
