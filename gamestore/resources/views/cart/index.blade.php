@extends('layouts.user')

@section('content')

<main class="container mx-auto px-4 md:px-10 pt-32 pb-20">
        
    <div id="cart-content">
        <h1 class="text-4xl font-black text-white uppercase italic tracking-tighter mb-10">
            Giỏ hàng <span class="text-blue-500">(02)</span>
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-8 space-y-4">
                
                <div class="group relative glass p-4 md:p-6 rounded-[2rem] border-white/5 flex items-center gap-6 hover:border-blue-500/30 transition-all duration-500">
                    <div class="w-24 h-32 md:w-32 md:h-40 flex-shrink-0 rounded-2xl overflow-hidden shadow-2xl shadow-black/50">
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=400" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg md:text-xl font-black text-white group-hover:text-blue-400 transition-colors mb-1">Black Myth: Wukong</h3>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Hành động • Soul-like</p>
                            </div>
                            <button class="text-gray-600 hover:text-red-500 p-2 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        
                        <div class="mt-6 flex items-end justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl font-black text-white tracking-tighter">950k</span>
                                <span class="text-sm text-gray-500 line-through font-bold">1.250k</span>
                                <span class="bg-blue-600/20 text-blue-400 text-[10px] font-black px-2 py-0.5 rounded border border-blue-400/20 uppercase">-20%</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] italic">Digital Edition</span>
                        </div>
                    </div>
                </div>

                <div class="group relative glass p-4 md:p-6 rounded-[2rem] border-white/5 flex items-center gap-6 hover:border-blue-500/30 transition-all duration-500">
                    <div class="w-24 h-32 md:w-32 md:h-40 flex-shrink-0 rounded-2xl overflow-hidden shadow-2xl shadow-black/50">
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=400" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg md:text-xl font-black text-white group-hover:text-blue-400 transition-colors mb-1">Cyberpunk 2077</h3>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Nhập vai • Thế giới mở</p>
                            </div>
                            <button class="text-gray-600 hover:text-red-500 p-2 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        <div class="mt-6">
                            <span class="text-2xl font-black text-white tracking-tighter">550.000₫</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-32 glass p-8 rounded-[2.5rem] border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/10 blur-[60px] rounded-full"></div>
                    
                    <h3 class="text-xl font-black text-white uppercase italic tracking-tight mb-8">Thông tin <span class="text-blue-500">Đơn hàng</span></h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Tạm tính</span>
                            <span class="text-white font-bold tracking-tight">1.800.000₫</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Khuyến mãi</span>
                            <span class="text-red-500 font-bold tracking-tight">-300.000₫</span>
                        </div>
                        <div class="pt-4 border-t border-white/5 flex justify-between">
                            <span class="text-white font-black uppercase italic tracking-tighter">Tổng cộng</span>
                            <span class="text-3xl font-black text-blue-400 tracking-tighter">1.500k</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black text-xs uppercase tracking-[0.2em] py-5 rounded-2xl shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-1">
                            Thanh toán ngay
                        </button>
                        <p class="text-[9px] text-gray-500 text-center font-bold uppercase tracking-widest">Đảm bảo an toàn 100% với SSL</p>
                    </div>

                    <div class="mt-8 pt-8 border-t border-white/5">
                        <div class="relative group">
                            <input type="text" placeholder="Nhập mã giảm giá..." class="w-full bg-white/5 border border-white/5 rounded-xl py-3 px-4 text-xs focus:outline-none focus:border-blue-500/50 transition-all">
                            <button class="absolute right-2 top-1.5 bg-white/10 hover:bg-white/20 text-white text-[10px] font-black px-3 py-1.5 rounded-lg transition-all uppercase">Áp dụng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="empty-cart" class="hidden min-h-[60vh] flex flex-col items-center justify-center text-center">
        <div class="relative mb-8">
            <div class="absolute inset-0 bg-blue-600/20 blur-[100px] rounded-full"></div>
            <div class="relative w-40 h-40 glass rounded-full flex items-center justify-center text-6xl shadow-2xl">🛒</div>
        </div>
        <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-4">Giỏ hàng của bạn đang trống</h2>
        <p class="text-gray-500 max-w-sm mb-10 font-medium">Có vẻ như bạn chưa chọn được siêu phẩm nào. Hãy khám phá thư viện game đồ sộ của chúng tôi ngay!</p>
        <a href="index.html" class="bg-white text-black px-10 py-4 rounded-full font-black text-xs uppercase tracking-[0.2em] hover:bg-blue-600 hover:text-white transition-all shadow-xl">
            Quay lại Cửa hàng
        </a>
    </div>

</main>

@endsection