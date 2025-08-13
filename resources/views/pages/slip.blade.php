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

        .section {
            margin-bottom: 10px;
        }

        h2 {
            margin: 0;
            font-size: 18px;
            text-align: center;
            text-transform: uppercase;
        }

        .info {
            display: flex;
            justify-content: space-between;
        }

        .box {
            border: 1px solid #000;
            padding: 5px;
            width: 48%;
        }

        .box strong {
            display: block;
            margin-bottom: 5px;
        }

        .capitalize {
            text-transform: capitalize;
        }

        .second {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .flex {
            display: flex;
            gap: 1.5rem;
        }

        .w-1/2 {
            width: 50%;
        }

        .space-y-1 > * + * {
            margin-top: 0.25rem;
        }

        strong {
            font-weight: bold;
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
                        <p>{{ $product['quantity'] }}x {{ $product['product_name'] }} - Rs {{ number_format($product['price']) }}</p>
                    @endforeach
            </div>
            </div>
        @endforeach
    @else
        <!-- Non-Admin View -->
        <div class="label">
            <h2>Shipping Label</h2>

            <!-- Seller & Customer -->
            <div class="info">
                <div class="box">
                    <strong>From (Seller):</strong>
                    <p class="capitalize"><strong>Store:</strong> {{ $seller->store_name ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $seller->store_phone ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $seller->store_email ?? 'N/A' }}</p>
                    <p class="capitalize"><strong>Address:</strong> {{ $seller->store_address ?? 'N/A' }}</p>
                </div>
                <div class="box">
                    <strong>To (Customer):</strong>
                    <p class="capitalize"><strong>Name:</strong> {{ $order->customer_name ?? 'N/A' }}</p>
                    <p><strong>Phone 1:</strong> {{ $order->phone ?? 'N/A' }}</p>
                    <p><strong>Phone 2:</strong> {{ $order->second_phone ?? 'N/A' }}</p>
                    <p class="capitalize"><strong>Address:</strong> {{ $order->address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Order Details -->
            <div class="section">
                <strong>Order ID:</strong> {{ $order->order_id ?? 'N/A' }} <br>
                <strong>Tracking ID:</strong> {{ $order->tracking_id ?? 'N/A' }}<br>
                <strong>Date:</strong> {{ $order->order_date ?? 'N/A' }}<br>
                <strong>Payment Method:</strong> Cash on Delivery
            </div>

            <!-- Product Summary -->
            <div class="section">
                <strong>Products:</strong><br>
                @foreach ($products as $product)
                    {{ $product['quantity'] }}x {{ $product['product_name'] }} - Rs {{ number_format($product['price'], 2) }}<br>
                @endforeach
            </div>
        </div>
    @endif
@endsection
