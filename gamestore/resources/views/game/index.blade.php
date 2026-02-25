@extends('layouts.user')

@section('content')

<main class="container mx-auto px-4 md:px-10 pt-32 pb-20">

    <!-- Tiêu đề và Tìm kiếm nhanh -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
        <div>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter">
                Khám phá <span class="text-blue-500">Thư viện</span>
            </h1>
            <p class="text-gray-500 mt-2 font-medium">Hiện có 1,420 tựa game sẵn sàng cho bạn</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" placeholder="Tìm tên game..." class="bg-white/5 border border-white/10 rounded-xl py-3 px-5 text-sm w-64 md:w-80 focus:outline-none focus:border-blue-500 transition-all">
            </div>
            <button class="glass p-3 rounded-xl hover:bg-white/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
        
        <!-- Sidebar (Bộ lọc chi tiết) -->
        <aside class="hidden lg:block space-y-8 sticky top-32 h-fit">
            
            {{-- 1. LỌC THEO THỂ LOẠI --}}
            <div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Thể loại</h3>
                <div class="flex flex-wrap gap-2">
                    {{-- Nút Tất cả: Vẫn giữ lại price và search nếu có --}}
                    <a href="{{ route('game', ['price' => request('price'), 'search' => request('search')]) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('category') ? 'bg-blue-600 text-white shadow-[0_0_10px_rgba(37,99,235,0.5)]' : 'bg-white/5 border border-white/5 text-gray-400 hover:bg-white/10' }}">
                        Tất cả
                    </a>

                    @foreach($categories as $cat)
                        {{-- Khi bấm Thể loại, nhớ mang theo price và search --}}
                        <a href="{{ route('game', ['category' => $cat->slug, 'price' => request('price'), 'search' => request('search')]) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('category') == $cat->slug ? 'bg-blue-600 text-white shadow-[0_0_10px_rgba(37,99,235,0.5)]' : 'bg-white/5 border border-white/5 text-gray-400 hover:bg-white/10' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- 2. KHOẢNG GIÁ (Dùng Form tự động Submit) --}}
            <div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Khoảng giá</h3>
                
                <form action="{{ route('game') }}" method="GET">
                    {{-- Giữ lại Category và Search khi lọc giá --}}
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                    <div class="space-y-3">
                        <label class="flex items-center gap-3 group cursor-pointer">
                            {{-- Đổi type="checkbox" thành type="radio" để khách chỉ chọn 1 khoảng giá --}}
                            <input type="radio" name="price" value="under_250" onchange="this.form.submit()" {{ request('price') == 'under_250' ? 'checked' : '' }}
                                   class="w-5 h-5 border-white/10 bg-white/5 text-blue-600 focus:ring-blue-600 transition">
                            <span class="text-sm text-gray-400 group-hover:text-white transition">Dưới 250,000₫</span>
                        </label>
                        
                        <label class="flex items-center gap-3 group cursor-pointer">
                            <input type="radio" name="price" value="250_to_500" onchange="this.form.submit()" {{ request('price') == '250_to_500' ? 'checked' : '' }}
                                   class="w-5 h-5 border-white/10 bg-white/5 text-blue-600 focus:ring-blue-600 transition">
                            <span class="text-sm text-gray-400 group-hover:text-white transition">250,000₫ - 500,000₫</span>
                        </label>
                        
                        <label class="flex items-center gap-3 group cursor-pointer">
                            <input type="radio" name="price" value="over_500" onchange="this.form.submit()" {{ request('price') == 'over_500' ? 'checked' : '' }}
                                   class="w-5 h-5 border-white/10 bg-white/5 text-blue-600 focus:ring-blue-600 transition">
                            <span class="text-sm text-gray-400 group-hover:text-white transition">Trên 500,000₫</span>
                        </label>

                        {{-- Nút Xóa Lọc Giá (Chỉ hiện khi đang chọn giá) --}}
                        @if(request('price'))
                        <div class="pt-2">
                            <a href="{{ route('game', ['category' => request('category'), 'search' => request('search')]) }}" 
                               class="text-xs text-red-400 hover:text-red-300 transition underline decoration-dashed">
                                Xóa lọc giá
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            {{-- 3. KHUYẾN MÃI (SĂN SALE) --}}
            <div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Khuyến mãi</h3>
                <form action="{{ route('game') }}" method="GET">
                    {{-- Giữ lại các bộ lọc khác nếu có --}}
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('price')) <input type="hidden" name="price" value="{{ request('price') }}"> @endif

                    <label class="flex items-center gap-3 group cursor-pointer bg-red-500/10 border border-red-500/20 p-3 rounded-2xl hover:bg-red-500/20 transition-all">
                        <input type="checkbox" name="on_sale" value="true" onchange="this.form.submit()" {{ request('on_sale') == 'true' ? 'checked' : '' }}
                            class="w-5 h-5 rounded-md border-red-500/30 bg-red-500/20 text-red-500 focus:ring-red-500 transition">
                        <span class="text-sm font-bold text-red-400 group-hover:text-red-300 transition">🔥 Chỉ hiện game Đang Sale</span>
                    </label>

                    {{-- Nút Xóa Lọc Sale --}}
                    @if(request('on_sale'))
                    <div class="pt-3 pl-1">
                        <a href="{{ route('game', ['category' => request('category'), 'price' => request('price'), 'search' => request('search')]) }}" 
                        class="text-xs text-gray-500 hover:text-white transition underline decoration-dashed">
                            Hiển thị tất cả
                        </a>
                    </div>
                    @endif
                </form>
            </div>
            
        </aside>

        <!-- Danh Sách Thẻ Game (Game Cards) -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                
                @forelse($games as $game)
                <div class="group relative bg-white/[0.02] rounded-[2rem] overflow-hidden border border-white/5 hover:border-blue-500/40 transition-all duration-500 hover:-translate-y-2">
                    
                    {{-- Nhấn vào hình là bay vô trang chi tiết --}}
                    <a href="{{ route('game.detail', $game->slug) }}" class="block">
                        <div class="aspect-[3/4] overflow-hidden relative">
                            <img src="{{ $game->image ? asset('storage/' . $game->image) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600' }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $game->title }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-transparent to-transparent opacity-90"></div>
                            
                            {{-- Khu vực Badge (Nhãn) --}}
                            <div class="absolute top-4 left-4 flex gap-2">
                                @if($game->is_featured)
                                    <span class="bg-blue-600 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase shadow-lg">Hot</span>
                                @endif
                                
                                @if($game->sale_price)
                                    @php $percent = round((($game->price - $game->sale_price) / $game->price) * 100); @endphp
                                    <span class="bg-red-500 text-white text-[10px] font-black px-2.5 py-1 rounded-lg uppercase shadow-lg border border-red-400/20">-{{ $percent }}%</span>
                                @endif
                            </div>
                        </div>
                    </a>
        
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <a href="{{ route('game.detail', $game->slug) }}">
                                <h3 class="font-bold text-lg text-white group-hover:text-blue-400 transition-colors truncate">{{ $game->title }}</h3>
                            </a>
                        </div>
                        <p class="text-gray-500 text-xs mb-4 uppercase tracking-widest">{{ $game->category->name ?? 'Chưa phân loại' }}</p>
                        
                        <div class="flex justify-between items-center">
                            {{-- Giá tiền --}}
                            <div>
                                @if($game->sale_price)
                                    <span class="text-xs text-gray-500 line-through block">{{ number_format($game->price, 0, ',', '.') }}đ</span>
                                    <span class="font-black text-blue-400 text-xl tracking-tighter">{{ number_format($game->sale_price, 0, ',', '.') }}₫</span>
                                @else
                                    <span class="font-black text-blue-400 text-xl tracking-tighter">{{ number_format($game->price, 0, ',', '.') }}₫</span>
                                @endif
                            </div>
                            
                            {{-- Nút giỏ hàng --}}
                            <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white/[0.02] border border-white/5 rounded-3xl">
                        <i class="fas fa-ghost text-4xl text-gray-600 mb-4"></i>
                        <p class="text-gray-400 text-lg font-bold">Không tìm thấy tựa game nào.</p>
                    </div>
                @endforelse
        
            </div>
        
            <div class="mt-16 flex justify-center">
                {{ $games->links('pagination::tailwind') }}
            </div>
        </div>
        
    </div>

    <!-- Top bán chạy -->
    <div class="mt-24">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-1.5 h-8 bg-blue-600 rounded-full shadow-[0_0_15px_rgba(37,99,235,0.5)]"></div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white uppercase italic">Top <span class="text-blue-500">Bán Chạy</span></h2>
        </div>
    
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            @forelse($topSellingGames as $index => $topGame)
            <a href="{{ route('game.detail', $topGame->slug) }}" class="group flex items-center gap-6 glass p-2 rounded-[2rem] border-white/5 hover:border-blue-500/30 transition-all duration-500">
                <div class="relative w-32 h-40 flex-shrink-0 overflow-hidden rounded-[1.5rem]">
                    <img src="{{ $topGame->image ? asset('storage/' . $topGame->image) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=300' }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $topGame->title }}">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                    
                    {{-- Logic vẽ số thứ tự (Top 1 màu xanh, Top 2-4 màu kính) --}}
                    <div class="absolute -top-2 -left-2 w-12 h-12 {{ $index == 0 ? 'bg-blue-600' : 'bg-white/10 backdrop-blur-xl border border-white/10' }} rounded-2xl flex items-center justify-center shadow-xl rotate-[-12deg] group-hover:rotate-0 transition-transform">
                        <span class="text-2xl font-black text-white italic">{{ $index + 1 }}</span>
                    </div>
                </div>
                
                <div class="flex-grow pr-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold {{ $index == 0 ? 'text-blue-400' : 'text-gray-400' }} uppercase tracking-widest">{{ $topGame->category->name ?? 'Gaming' }}</span>
                        
                        {{-- Chỉ đánh sao cho Top 1 --}}
                        @if($index == 0)
                        <div class="flex text-yellow-500 text-[8px]">
                            ★★★★★
                        </div>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-black text-white group-hover:text-blue-400 transition-colors mb-2 leading-tight line-clamp-2">{{ $topGame->title }}</h3>
                    
                    <div class="flex items-center justify-between">
                        {{-- Hiển thị giá --}}
                        @if($topGame->sale_price)
                            <span class="text-lg font-black text-white">{{ number_format($topGame->sale_price, 0, ',', '.') }}₫</span>
                        @else
                            <span class="text-lg font-black text-white">{{ number_format($topGame->price, 0, ',', '.') }}₫</span>
                        @endif
                        
                        {{-- Lượt mua giả lập (Lấy Lượt xem làm lượt mua luôn cho oai) --}}
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">{{ number_format($topGame->views) }} lượt xem</span>
                    </div>
                </div>
            </a>
            @empty
                <div class="col-span-full text-gray-500 italic">Hệ thống đang cập nhật danh sách...</div>
            @endforelse
    
        </div>
    </div>
</main>

@endsection