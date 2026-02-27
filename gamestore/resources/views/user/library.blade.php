@extends('layouts.user')

@section('content')
<main class="container mx-auto px-4 md:px-10 pt-32 pb-20 min-h-screen">
    
    <div class="flex items-end justify-between mb-10">
        <div>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter mb-2">
                Thư viện <span class="text-blue-500">Game</span>
            </h1>
            <p class="text-gray-400 font-medium text-sm">Nơi lưu trữ những siêu phẩm đã xuống tiền.</p>
        </div>
        <div class="hidden md:block">
            <span class="bg-white/10 text-white font-black px-4 py-2 rounded-xl border border-white/10">
                Tổng cộng: <span class="text-blue-400">{{ $orders->count() }}</span> Hóa đơn
            </span>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-10">
            @foreach($orders as $order)
                <div class="glass rounded-[2rem] border border-white/5 overflow-hidden shadow-2xl hover:border-blue-500/30 transition-all duration-500 group">
                    
                    {{-- HEADER HÓA ĐƠN --}}
                    <div class="bg-white/5 px-6 md:px-8 py-5 flex flex-wrap justify-between items-center border-b border-white/5">
                        <div>
                            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">
                                Mã đơn hàng: <span class="text-white text-sm tracking-widest">{{ $order->order_code }}</span>
                            </p>
                            <p class="text-gray-500 text-[11px] font-medium italic">
                                <i class="far fa-clock mr-1"></i> Mua ngày: {{ $order->created_at->format('d/m/Y - H:i') }}
                            </p>
                        </div>
                        <div class="text-right mt-3 md:mt-0">
                            <p class="text-blue-400 font-black text-xl tracking-tighter mb-1">{{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
                            @if($order->status == 'completed')
                                <span class="bg-green-500/20 text-green-400 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest border border-green-500/20">
                                    <i class="fas fa-check-circle mr-1"></i> Thành công
                                </span>
                            @else
                                <span class="bg-red-500/20 text-red-400 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest border border-red-500/20">
                                    <i class="fas fa-times-circle mr-1"></i> Đã hủy
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- DANH SÁCH GAME TRONG ĐƠN HÀNG NÀY --}}
                    <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-6 bg-[#050507]/50">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-5 bg-white/[0.02] p-4 rounded-2xl border border-white/5 hover:bg-white/5 transition-colors">
                                {{-- Hình Game --}}
                                <div class="w-20 h-24 flex-shrink-0 rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ $item->game && $item->game->image ? asset('storage/' . $item->game->image) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=200' }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                </div>
                                
                                {{-- Tên Game --}}
                                <div class="flex-grow">
                                    @if($item->game)
                                        <a href="{{ route('game.detail', $item->game->slug) }}">
                                            <h4 class="text-white font-black text-lg hover:text-blue-400 transition-colors mb-1 truncate max-w-[200px] md:max-w-[300px]">{{ $item->game->title }}</h4>
                                        </a>
                                        <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">{{ $item->game->category->name ?? 'Gaming' }}</p>
                                    @else
                                        <h4 class="text-gray-500 font-black text-lg mb-1 line-through">Game đã bị gỡ</h4>
                                    @endif
                                    
                                    <p class="text-gray-400 text-xs font-bold">Giá lúc mua: <span class="text-white">{{ number_format($item->price, 0, ',', '.') }}₫</span></p>
                                </div>

                                {{-- Nút Tải Game --}}
                                @if($order->status == 'completed' && $item->game)
                                    <button class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-600 hover:bg-blue-500 text-white flex items-center justify-center transition-all shadow-lg shadow-blue-500/30 hover:-translate-y-1 group/btn" title="Tải Game về máy">
                                        {{-- Đổi thẻ <i> thành thẻ <svg> bao mượt, bao không lỗi --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover/btn:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>
    @else
        {{-- NẾU CHƯA MUA GAME NÀO --}}
        <div class="min-h-[40vh] flex flex-col items-center justify-center text-center glass rounded-[3rem] border-white/5 mt-10">
            <i class="fas fa-box-open text-6xl text-gray-600 mb-6"></i>
            <h2 class="text-2xl font-black text-white uppercase italic tracking-tighter mb-3">Thư viện trống rỗng</h2>
            <p class="text-gray-500 mb-8 font-medium">Ba chưa sở hữu tựa game nào. Hãy dạo một vòng cửa hàng nhé!</p>
            <a href="{{ route('game') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-500/30 hover:-translate-y-1">
                Đi Cà Thẻ Ngay
            </a>
        </div>
    @endif

</main>
@endsection