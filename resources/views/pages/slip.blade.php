@extends('boilerplate')

@section('title', 'Parcel Shipping Label')

@section('main-content')
    <style>
        @page {
            size: A5;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 14px;
        }

        .label {
            border: 2px dashed #000;
            padding: 10px;
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
            padding: 4px;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }

        .capitalize {
            text-transform: capitalize;
        }

        h2 {
            margin: 0 0 10px 0;
            font-size: 18px;
            text-align: center;
            text-transform: uppercase;
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
        <!-- Non-Admin View -->
        <div class="label">
            {{-- <h2>Shipping Label</h2> --}}

            <!-- Main Information Table -->
            <table style="width:100%; border-collapse: collapse; font-size: 13px; margin-bottom: 10px;" border="1">
                <tr>
                    <th style="width: 33%; text-align: left; padding: 6px;">Customer Information</th>
                    <th style="width: 33%; text-align: left; padding: 6px;">Shipment Information</th>
                    <th style="width: 34%; text-align: left; padding: 6px;"></th>
                </tr>
                <tr>
                    <td style="padding: 6px;">
                        <strong>Name:</strong> {{ $order->customer_name ?? 'N/A' }}<br>
                        <strong>Contact 1:</strong> {{ $order->phone ?? 'N/A' }}<br>
                        {{-- <strong>Contact 2:</strong> {{ $order->second_phone ?? 'N/A' }}<br> --}}
                        <strong>Delivery Address:</strong> {{ $order->address ?? 'N/A' }}
                    </td>
                    <td style="padding: 6px;">
                        {{-- <strong>Pieces:</strong> {{ count($products) }}<br> --}}
                        <strong>Order Ref:</strong> #{{ $order->order_id ?? 'N/A' }}<br>
                        <strong>Tracking No:</strong> {{ $order->tracking_id ?? 'N/A' }}<br>
                        {{-- <strong>Origin:</strong> {{ $seller->store_address ?? 'N/A' }}<br> --}}
                        <strong>Ship Date:</strong> {{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->format('d M Y') : 'N/A' }}<br>
                        <strong>Destination:</strong> {{ $order->address ?? 'N/A' }}<br>
                        {{-- <strong>Return City:</strong> {{ $seller->store_address ?? 'N/A' }} --}}
                    </td>
                    <td style="padding: 6px;">

                    </td>
                </tr>
            </table>

            <!-- Shipper Information Table -->
            <table style="width:100%; border-collapse: collapse; font-size: 13px; margin-bottom: 10px;" border="1">
                <tr>
                    <th style="width: 33%; text-align: left; padding: 6px;">Shipper Information</th>
                    <th style="width: 33%; text-align: left; padding: 6px;">Remarks</th>
                    <th style="width: 34%; text-align: left; padding: 6px;">Order Information</th>
                </tr>
                <tr>
                    <td style="padding: 6px;">
                        <strong>Name:</strong> {{ $seller->store_name ?? 'N/A' }}<br>
                        <strong>Email:</strong> {{ $seller->store_email ?? 'N/A' }}<br>
                        <strong>Contact:</strong> {{ $seller->store_phone ?? 'N/A' }}<br>
                        <strong>Return Address:</strong> {{ $seller->store_address ?? 'N/A' }}
                    </td>
                    <td style="padding: 6px;">
                        Please call before delivering.
                    </td>
                    <td style="padding: 6px;">
                        @php
                            $totalAmount = 0;
                            foreach ($products as $product) {
                                $totalAmount += $product['quantity'] * $product['price'];
                            }
                        @endphp
                        <strong>Amount:</strong> {{ number_format($totalAmount, 2) }}/-<br>
                        <strong>Order Date:</strong> {{ $order->order_date ?? 'N/A' }}<br>
                        <strong>Order Type:</strong> Cash On Delivery
                    </td>
                </tr>
            </table>

            <!-- Order Details Table -->
            <table style="width:100%; border-collapse: collapse; font-size: 13px;" border="1">
                <tr>
                    <td style="padding: 6px;">
                        <strong>Order Details:</strong>
                        @foreach ($products as $product)
                            [ {{ $product['quantity'] }} x {{ $product['product_name'] }} - Rs
                            {{ $product['price'] * $product['quantity'] }} ]
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>

    @endif
@endsection
