@extends('layouts.admin') {{-- Nhớ đổi lại tên layout admin của ba nếu khác nha --}}

@section('content')
<div class="container mx-auto px-4 md:px-8 pt-10 pb-20">
    
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tight mb-1">Quản lý <span class="text-blue-600">Đơn hàng</span></h1>
            <p class="text-gray-500 text-sm font-medium">Đối soát hóa đơn và xử lý sự cố giao dịch.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tổng đơn:</span>
            <span class="text-lg font-black text-blue-600 ml-2">{{ $orders->total() }}</span>
        </div>
    </div>

    {{-- THANH TÌM KIẾM ĐƠN HÀNG --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET">
            <div class="flex gap-4 items-center">
                <div class="relative flex-grow max-w-lg">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                           placeholder="Nhập mã đơn (VD: GAMEX-...), tên hoặc email khách hàng..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none text-sm transition-all">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/20">
                    Tra cứu
                </button>
                
                {{-- Nếu đang tìm kiếm thì hiện nút Bỏ lọc --}}
                @if(request('keyword'))
                    <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-red-500 font-bold text-sm transition-colors">
                        <i class="fas fa-times-circle mr-1"></i> Bỏ lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- BẢNG DANH SÁCH ĐƠN HÀNG --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Mã Đơn</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Khách hàng</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Thời gian</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Tổng Tiền</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Trạng Thái</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="font-black text-gray-700">{{ $order->order_code }}</span>
                            <br>
                            <span class="text-[10px] text-gray-400 font-bold tracking-widest">{{ $order->payment_method }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-gray-800">{{ $order->user->name ?? 'Tài khoản đã xóa' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-600">{{ $order->created_at->format('d/m/Y') }}</span>
                            <br>
                            <span class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <span class="font-black text-blue-600">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($order->status == 'completed')
                                <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest border border-green-200">Thành công</span>
                            @elseif($order->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest border border-yellow-200">Chờ duyệt</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest border border-red-200">Đã Hủy</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            {{-- FORM ĐỔI TRẠNG THÁI --}}
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-block">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="text-xs font-bold border-gray-200 rounded-lg text-gray-600 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white cursor-pointer transition-colors py-2 px-3">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Thành công</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Hủy đơn</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="text-gray-400 mb-2"><i class="fas fa-box-open text-4xl"></i></div>
                            <p class="text-gray-500 font-medium">Chưa có đơn hàng nào phát sinh.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- NÚT PHÂN TRANG --}}
        <div class="p-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection