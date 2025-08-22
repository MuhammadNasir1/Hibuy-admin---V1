@extends('boilerplate')

@section('title', 'Parcel Shipping Label')

@section('main-content')
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 14px;
        }

        .label {
            border: 1px dashed #000;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 10px;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .qr {
            width: 80px;
            height: 80px;
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            font-size: 11px;
            color: #666;
            margin: auto;
        }

        .remarks td,
        .order-details td {
            font-size: 13px;
        }
    </style>

    @if ($isAdmin)
        <!-- Admin View -->
        @foreach ($grouped_products as $storeId => $group)
            <div class="flex gap-6 second border border-gray-400 rounded-md p-4 mt-4">
                <!-- Order Info -->
                <div class="w-1/2 space-y-1">
                    <p><strong>Order ID:</strong> {{ $order->order_id ?? 'N/A' }}</p>
                    <p><strong>Tracking ID:</strong> {{ $order->tracking_id ?? 'N/A' }}</p>
                    <p><strong>Store:</strong> {{ $group['store'] ? $group['store']->store_name : 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $group['store'] ? $group['store']->store_phone : 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $group['store'] ? $group['store']->store_address : 'N/A' }}</p>
                </div>

                <!-- Product Summary -->
                <div class="w-1/2 space-y-1">
                    <p><strong>Products:</strong></p>
                    @foreach ($group['products'] as $product)
                        <p>{{ $product['quantity'] }}x {{ $product['product_name'] }} - Rs
                            {{ $product['price'] * $product['quantity'] }}</p>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <!-- Non-Admin View (Shipping Label Design) -->
        <div class="label">
            <table>
                <tr>
                    <th colspan="2">Customer Information</th>
                    <th colspan="2">Shipment Information</th>
                    <th colspan="2">Order Information</th>
                </tr>
                <tr>
                    <td><b>Name:</b></td>
                    <td>{{ $order->customer_name ?? 'N/A' }}</td>
                    <td><b>Ship Date:</b></td>
                    <td> {{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y') : 'N/A' }}</td>
                    <td rowspan="5" colspan="2" style="text-align:center;">
                        {{-- <div class="qr">QR Code</div> --}}
                        @php
                            $totalAmount = 0;
                            foreach ($products as $product) {
                                $totalAmount += $product['quantity'] * $product['price'];
                            }
                        @endphp
                        <div><b>Amount:</b> {{ number_format($totalAmount, 2) }}/-</div>
                        <div><b>Date:</b>
                            {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') : 'N/A' }}
                        </div>
                        <div><b>Order Type:</b> Cash On Delivery</div>
                    </td>
                </tr>
                <tr>
                    <td><b>Contact:</b></td>
                    <td>{{ $order->phone ?? 'N/A' }}</td>
                    <td><b>Order Ref:</b></td>
                    <td>#{{ $order->order_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><b>Delivery Address:</b></td>
                    <td>{{ $order->address ?? 'N/A' }}</td>
                    <td><b>Tracking No:</b></td>
                    <td>{{ $order->tracking_id ?? 'N/A' }}</td>
                </tr>
                {{-- <tr>
                    <td><b></b></td><td></td>
                    <td><b>Destination:</b></td><td>{{ $order->address ?? 'N/A' }}</td>
                </tr> --}}

            </table>

            <table>
                <tr>
                    <th colspan="4">Shipper Information</th>
                </tr>
                <tr>
                    <td><b>Name:</b></td>
                    <td>{{ $seller->store_name ?? 'N/A' }}</td>
                    <td><b>Contact:</b></td>
                    <td>{{ $seller->store_phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><b>Email:</b></td>
                    <td colspan="3">{{ $seller->store_email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><b>Pickup Address:</b></td>
                    <td colspan="3">{{ $seller->store_address ?? 'N/A' }}</td>
                </tr>
            </table>
            <table class="order-details">
                <tr>
                    <th>Order Details</th>
                </tr>
                <tr>
                    <td>
                        @foreach ($products as $product)
                            [
                            {{ $product['quantity'] }} x {{ $product['product_name'] }}

                            @if (!empty($product['parent_option']['name']) && !empty($product['parent_option']['value']))
                                ({{ $product['parent_option']['name'] }}: {{ $product['parent_option']['value'] }})
                            @endif

                            @if (!empty($product['child_option']['name']) && !empty($product['child_option']['value']))
                                ({{ $product['child_option']['name'] }}: {{ $product['child_option']['value'] }})
                            @endif

                            - Rs {{ number_format($product['price'] * $product['quantity'], 2) }}
                            ]
                        @endforeach

                    </td>
                </tr>
            </table>
        </div>
    @endif
@endsection
