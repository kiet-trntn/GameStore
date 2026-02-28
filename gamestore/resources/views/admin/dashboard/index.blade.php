@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 md:px-8 pt-10 pb-20">
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tight mb-1">Tổng quan <span class="text-blue-600">Hệ thống</span></h1>
        <p class="text-gray-500 text-sm font-medium">Chào mừng trở lại! Dưới đây là tình hình kinh doanh của GameX.</p>
    </div>

    {{-- KHỐI 1: 4 THẺ THỐNG KÊ (WIDGETS) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        {{-- Thẻ Doanh Thu --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="text-green-500 mb-2"><i class="fas fa-wallet text-2xl"></i></div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Tổng Doanh Thu</p>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h3>
            </div>
        </div>

        {{-- Thẻ Đơn Hàng --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="text-blue-500 mb-2"><i class="fas fa-shopping-cart text-2xl"></i></div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Tổng Đơn Hàng</p>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">{{ $totalOrders }} <span class="text-sm font-bold text-gray-400">đơn</span></h3>
            </div>
        </div>

        {{-- Thẻ Game thủ --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="text-purple-500 mb-2"><i class="fas fa-users text-2xl"></i></div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Tổng Thành Viên</p>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">{{ $totalUsers }} <span class="text-sm font-bold text-gray-400">user</span></h3>
            </div>
        </div>

        {{-- Thẻ Kho Game --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="text-orange-500 mb-2"><i class="fas fa-gamepad text-2xl"></i></div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Sản phẩm hiện có</p>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">{{ $totalGames }} <span class="text-sm font-bold text-gray-400">game</span></h3>
            </div>
        </div>

    </div>

    {{-- KHỐI 2: BIỂU ĐỒ & ĐƠN HÀNG GẦN ĐÂY --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        {{-- BIỂU ĐỒ DOANH THU (Chiếm 2/3 màn hình) --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-6">Doanh thu <span class="text-blue-600">7 ngày gần nhất</span></h3>
            <div class="relative w-full h-[300px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- DANH SÁCH ĐƠN MỚI NHẤT (Chiếm 1/3 màn hình) --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight">Đơn hàng <span class="text-green-500">Mới</span></h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-blue-500 hover:text-blue-700 uppercase tracking-widest">Xem tất cả</a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentOrders as $order)
                    <div class="flex justify-between items-center border-b border-gray-50 pb-4 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-black text-gray-700">{{ $order->order_code }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-blue-600">{{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
                            @if($order->status == 'completed')
                                <span class="text-[9px] text-green-500 font-black uppercase tracking-widest border border-green-200 bg-green-50 px-2 py-0.5 rounded">Thành công</span>
                            @else
                                <span class="text-[9px] text-yellow-500 font-black uppercase tracking-widest border border-yellow-200 bg-yellow-50 px-2 py-0.5 rounded">Chờ duyệt</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-400 text-sm font-medium">Chưa có đơn hàng nào.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
{{-- Nạp thư viện Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Ép kiểu mảng PHP sang Javascript
    const labels = {!! json_encode($chartLabels) !!};
    const dataValues = {!! json_encode($chartData) !!};

    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Khởi tạo Biểu đồ
    const revenueChart = new Chart(ctx, {
        type: 'line', // Biểu đồ đường (line chart)
        data: {
            labels: labels, // Trục X (Ngày)
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: dataValues, // Trục Y (Tiền)
                borderColor: '#2563EB', // Màu đường vẽ (Xanh dương Tailwind)
                backgroundColor: 'rgba(37, 99, 235, 0.1)', // Màu nền dưới đường
                borderWidth: 3,
                pointBackgroundColor: '#2563EB',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true, // Đổ bóng nền
                tension: 0.4 // Làm cong nét vẽ cho mượt
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }, // Giấu cái ô chú thích đi cho gọn
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            // Định dạng số tiền có dấu chấm trong Tooltip
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: '#f3f4f6' },
                    ticks: {
                        callback: function(value) {
                            // Rút gọn số tiền (Ví dụ: 1000000 -> 1M)
                            if (value >= 1000000) return (value / 1000000) + 'M';
                            if (value >= 1000) return (value / 1000) + 'K';
                            return value;
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection