@extends('layout')
@section('title', 'Dashboard')
@section('nav-title', 'Dashboard')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4  my-3">
            <!-- Card 1 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        💰 <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Total Sales</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">$166,580</h2>
                <p class="text-green-500 text-sm">⬆ 5% in the last 1 month</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        📦 <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Total Products</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">5,679</h2>
                <p class="text-green-500 text-sm">⬆ 2% in the last 1 month</p>
            </div>


            <!-- Card 3 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        🔄 <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Active Sellers</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">5,679</h2>
                <p class="text-green-500 text-sm">⬆ 2% in the last 1 month</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        ⭐ <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Active Buyers</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,801</h2>
                <p class="text-red-500 text-sm">⬇ 3% in the last 1 month</p>
            </div>

            <!-- Card 5 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        🎁 <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Current Orders</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,801</h2>
                <p class="text-red-500 text-sm">⬇ 3% in the last 1 month</p>
            </div>

            <!-- Card 6 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        📑 <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Current Orders</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">$166,580</h2>
                <p class="text-green-500 text-sm">⬆ 5% in the last 1 month</p>
            </div>

            <!-- Card 7 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        ❌ <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Reviews</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,801</h2>
                <p class="text-red-500 text-sm">⬇ 3% in the last 1 month</p>
            </div>

            <!-- Card 8 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        ❓ <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Pending Queries</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,801</h2>
                <p class="text-red-500 text-sm">⬇ 3% in the last 1 month</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-3">
            <!-- Sales Data Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <h2 class="text-lg font-semibold">Sales Data</h2>
                <canvas id="salesChart" class="mt-4"></canvas>
                <div class="flex items-center mt-4 space-x-4">
                    <div class="flex items-center cursor-pointer" id="toggleThisMonth">
                        <span class="w-3 h-3 bg-blue-500 rounded-full inline-block mr-2"></span>
                        <span class="text-gray-600 text-sm">This month</span>
                    </div>
                    <div class="flex items-center cursor-pointer" id="togglePrevMonth">
                        <span class="w-3 h-3 bg-black rounded-full inline-block mr-2"></span>
                        <span class="text-gray-600 text-sm">Prev month</span>
                    </div>
                </div>
            </div>

            <!-- Top Selling Stores Table -->
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Top Selling Stores</h2>
                    <button class="bg-blue-500 text-white px-4 py-1 rounded-lg text-sm flex items-center">
                        This Month ▼
                    </button>
                </div>
                <table class="w-full mt-4">
                    <thead>
                        <tr class="text-gray-600 text-sm border-b">
                            <th class="text-left py-2">No.</th>
                            <th class="text-left py-2">Store</th>
                            <th class="text-left py-2">Earning</th>
                            <th class="text-left py-2">Item Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-3">1</td>
                            <td class="flex items-center space-x-3">
                                <img src="{{ asset('asset/Ellipse 2.png') }}" class="w-10 h-10 rounded-md" alt="Store Logo">
                                <div>
                                    <p class="font-semibold">Store Name</p>
                                    <p class="text-sm text-gray-500">Category</p>
                                </div>
                            </td>
                            <td class="text-blue-500 font-semibold">$35</td>
                            <td class="text-gray-600">498 pcs</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3">2</td>
                            <td class="flex items-center space-x-3">
                                <img src="{{ asset('asset/Ellipse 2.png') }}" class="w-10 h-10 rounded-md" alt="Store Logo">
                                <div>
                                    <p class="font-semibold">Store Name</p>
                                    <p class="text-sm text-gray-500">Category</p>
                                </div>
                            </td>
                            <td class="text-blue-500 font-semibold">$55</td>
                            <td class="text-gray-600">367 pcs</td>
                        </tr>
                        <tr>
                            <td class="py-3">3</td>
                            <td class="flex items-center space-x-3">
                                <img src="{{ asset('asset/Ellipse 2.png') }}" class="w-10 h-10 rounded-md" alt="Store Logo">
                                <div>
                                    <p class="font-semibold">Store Name</p>
                                    <p class="text-sm text-gray-500">Category</p>
                                </div>
                            </td>
                            <td class="text-blue-500 font-semibold">$48</td>
                            <td class="text-gray-600">255 pcs</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                datasets: [
                    {
                        label: 'This Month',
                        data: [2200, 1500, 2300, 1700, 2100, 900, 2500],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderRadius: 5,
                        hidden: false // Default visible
                    },
                    {
                        label: 'Prev Month',
                        data: [1800, 1100, 1500, 1300, 1600, 700, 2000],
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        borderRadius: 5,
                        hidden: false // Default visible
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Toggle visibility for "This Month"
        document.getElementById("toggleThisMonth").addEventListener("click", function () {
            salesChart.data.datasets[0].hidden = !salesChart.data.datasets[0].hidden;
            salesChart.update();
        });

        // Toggle visibility for "Prev Month"
        document.getElementById("togglePrevMonth").addEventListener("click", function () {
            salesChart.data.datasets[1].hidden = !salesChart.data.datasets[1].hidden;
            salesChart.update();
        });
    </script>
@endsection
