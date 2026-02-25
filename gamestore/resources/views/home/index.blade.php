@extends('layouts.user')

@section('title', 'Trang chủ - Game Store')

@section('content')

    <!-- SlideShow -->
    <section class="pt-28 pb-8">
        <div class="container mx-auto px-4 md:px-10">
            <div class="swiper mainHeroSwiper rounded-[2rem] overflow-hidden shadow-2xl relative border border-white/5">
                <div class="swiper-wrapper">
                    
                    <div class="swiper-slide relative h-[450px] md:h-[600px] overflow-hidden group">
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1920" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-[5000ms] group-active:scale-110 scale-105">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-16 lg:p-24">
                            <div class="max-w-2xl transform transition-all duration-700">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="h-[1px] w-8 bg-blue-500"></span>
                                    <span class="text-blue-400 text-xs font-bold tracking-[0.3em] uppercase">Trending Now</span>
                                </div>
                                <h2 class="text-4xl md:text-6xl font-bold mb-6 tracking-tight leading-none text-white">
                                    ELDER REALM <br>
                                    <span class="font-extralight italic">AWAKENING</span>
                                </h2>
                                <div class="flex items-center gap-6">
                                    <button class="bg-white text-black px-8 py-3.5 rounded-full font-bold text-sm hover:bg-blue-500 hover:text-white transition-all duration-300 shadow-xl">
                                        Mua Ngay — 599k
                                    </button>
                                    <button class="text-white text-sm font-semibold flex items-center gap-2 hover:gap-4 transition-all">
                                        Xem chi tiết <span>→</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="swiper-slide relative h-[450px] md:h-[600px] overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?auto=format&fit=crop&w=1920" 
                             class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute inset-0 flex flex-col justify-center p-8 md:p-16 lg:p-24">
                            <div class="max-w-xl">
                                <span class="inline-block px-3 py-1 rounded-md border border-red-500/50 text-red-500 text-[10px] font-bold mb-4 uppercase">Flash Sale -50%</span>
                                <h2 class="text-4xl md:text-6xl font-bold mb-6 tracking-tighter text-white">CYBER STRIKE</h2>
                                <p class="text-gray-400 text-sm md:text-base mb-8 line-clamp-2 max-w-sm">Dấn thân vào cuộc chiến tương lai đầy kịch tính với đồ họa thế hệ mới.</p>
                                <button class="bg-blue-600 px-8 py-3.5 rounded-full font-bold text-sm hover:scale-105 transition-transform shadow-lg shadow-blue-500/20">
                                    Săn Deal Ngay
                                </button>
                            </div>
                        </div>
                    </div>
    
                </div>
    
                <div class="swiper-pagination !bottom-8 !text-left !px-16 container"></div>
                
                <div class="absolute top-1/2 -translate-y-1/2 right-8 flex flex-col gap-4 z-10 hidden md:flex">
                    <div class="swiper-button-next !static !w-10 !h-10 !m-0 after:!text-xs border border-white/10 rounded-full bg-white/5 hover:bg-white/20 transition backdrop-blur-md"></div>
                    <div class="swiper-button-prev !static !w-10 !h-10 !m-0 after:!text-xs border border-white/10 rounded-full bg-white/5 hover:bg-white/20 transition backdrop-blur-md"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Khám phá danh mục -->
    <section class="container mx-auto px-4 md:px-10 py-12">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white uppercase italic">
                    Khám phá <span class="text-blue-500 underline decoration-2 underline-offset-8">Danh mục</span>
                </h2>
                <p class="text-gray-500 text-sm mt-2">Tìm kiếm phong cách chơi game riêng của bạn</p>
            </div>
            <div class="hidden md:flex gap-2">
                <div class="w-12 h-[2px] bg-blue-600"></div>
                <div class="w-4 h-[2px] bg-white/20"></div>
            </div>
        </div>
    
        @php
            // Mảng quy định độ rộng to/nhỏ của các ô (Masonry layout)
            $gridClasses = [
                0 => 'col-span-2 md:col-span-2 lg:col-span-2', // Ô to đầu tiên
                1 => 'col-span-1',                             // Ô nhỏ 1
                2 => 'col-span-1',                             // Ô nhỏ 2
                3 => 'col-span-2'                              // Ô to cuối cùng
            ];
            
            // Giữ lại mảng này làm "Ảnh Dự Phòng" (Fallback) 
            // Đề phòng trường hợp ba tạo danh mục mà quên up ảnh
            $defaultImages = [
                0 => 'https://images.unsplash.com/photo-1552824730-10360ec57a1b?auto=format&fit=crop&w=800',
                1 => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=400',
                2 => 'https://images.unsplash.com/photo-1580234811497-9bd7fd0f5ee6?auto=format&fit=crop&w=400',
                3 => 'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?auto=format&fit=crop&w=800',
            ];
        @endphp
    
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($categories as $index => $category)
                @php
                    // Tính toán class to/nhỏ
                    $class = $gridClasses[$index % 4];
                    
                    // QUAN TRỌNG: Logic lấy ảnh
                    // Nếu $category->image có dữ liệu trong DB -> Dùng asset() nối đường dẫn.
                    // Nếu trống -> Lấy ảnh dự phòng tương ứng.
                    $imageSrc = $category->image ? asset('storage/' . $category->image) : $defaultImages[$index % 4];
                @endphp
    
                <a href="{{ route('game', ['category' => $category->slug]) }}" class="{{ $class }} group relative h-64 overflow-hidden rounded-[2rem] border border-white/5 shadow-xl">
                    {{-- Đổ biến $imageSrc vào đây --}}
                    <img src="{{ $imageSrc }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $category->name }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                    
                    <div class="absolute inset-x-0 bottom-0 p-6 {{ $index % 4 == 3 ? 'text-right' : '' }}">
                        <h3 class="text-xl font-bold text-white tracking-wide {{ $index % 4 == 3 ? 'uppercase italic tracking-tighter' : '' }}">
                            {{ $category->name }}
                        </h3>
                        
                        @if($index % 4 == 3)
                            <p class="text-gray-400 text-[10px] mt-1 uppercase tracking-[0.2em]">Cảm giác mạnh</p>
                        @else
                            <p class="text-blue-400 text-xs font-semibold opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-widest mt-1">
                                {{ $category->games_count ?? 0 }} Trò chơi →
                            </p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-white col-span-full text-center py-10">Chưa có danh mục nào được hiển thị.</p>
            @endforelse
        </div>
    </section>

    <!-- Game hot trong tuần -->
    <section class="container mx-auto px-4 md:px-10 py-16 relative z-10">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full shadow-[0_0_15px_rgba(37,99,235,0.5)]"></div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white uppercase italic">Top 5 Hot <span class="text-gray-500 font-light">Tuần Này</span></h2>
            </div>
            
            <div class="flex gap-3">
                <button class="hot-prev w-11 h-11 glass rounded-2xl flex items-center justify-center hover:bg-white hover:text-black transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button class="hot-next w-11 h-11 glass rounded-2xl flex items-center justify-center hover:bg-white hover:text-black transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>
    
        <div class="swiper hotGamesSwiper !overflow-visible">
            <div class="swiper-wrapper">
                
                {{-- Đổ 5 game hot từ biến $hotGames --}}
                @forelse($hotGames as $index => $game)
                    <div class="swiper-slide group">
                        <a href="{{ route('game.detail', $game->slug) }}" class="block relative aspect-[3/4.2] rounded-[2rem] overflow-hidden border border-white/10 bg-[#121215] shadow-lg transition-all duration-500 group-hover:-translate-y-2 group-hover:shadow-blue-500/20 group-hover:shadow-2xl">
                            
                            {{-- Ảnh đại diện Game --}}
                            @if($game->image)
                                <img src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="absolute inset-0 w-full h-full bg-gray-800 flex items-center justify-center">
                                    <span class="text-gray-500 text-sm">No Image</span>
                                </div>
                            @endif
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-transparent to-transparent opacity-90"></div>
                            
                            {{-- Gắn tag TOP 1 -> 5 cho tất cả --}}
                            <div class="absolute top-4 left-4">
                                <span class="glass px-3 py-1 rounded-lg text-[10px] font-black {{ $index == 0 ? 'text-yellow-400 border-yellow-400/30' : 'text-blue-400 border-blue-400/20' }} uppercase tracking-tighter border shadow-lg bg-black/40 backdrop-blur-md">
                                    Top {{ $index + 1 }}
                                </span>
                            </div>
    
                            {{-- Thông tin Game --}}
                            <div class="absolute inset-x-0 bottom-0 p-6">
                                <h3 class="text-lg font-bold text-white mb-2 leading-tight line-clamp-2 group-hover:text-blue-400 transition-colors">
                                    {{ $game->title }}
                                </h3>
                                
                                <div class="flex items-center justify-between">
                                    {{-- Hiển thị Giá --}}
                                    <div>
                                        @if($game->sale_price)
                                            <div class="text-gray-400 text-xs line-through mb-0.5">{{ number_format($game->price, 0, ',', '.') }}đ</div>
                                            <span class="text-blue-400 font-black text-base">{{ number_format($game->sale_price, 0, ',', '.') }} <span class="text-[10px] font-normal text-gray-500 italic">đ</span></span>
                                        @else
                                            <span class="text-blue-400 font-black text-base">{{ number_format($game->price, 0, ',', '.') }} <span class="text-[10px] font-normal text-gray-500 italic">đ</span></span>
                                        @endif
                                    </div>
                                    
                                    {{-- Icon xem chi tiết --}}
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4v16m8-8H4" stroke-width="3"/></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-gray-500 italic w-full text-center py-10 border border-dashed border-white/10 rounded-2xl bg-white/5">
                        <i class="fas fa-star mb-2 text-2xl text-gray-600"></i><br>
                        Chưa có game hot nào được đánh dấu sao.
                    </div>
                @endforelse
    
            </div>
        </div>
    </section>

    <!-- Banner quảng cáo -->
    <section class="container mx-auto px-4 md:px-10 py-12">
        <div class="relative w-full min-h-[350px] md:h-[450px] rounded-[3rem] overflow-hidden border border-white/10 shadow-2xl group bg-[#0a0a0c]">
            
            <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1920" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60 scale-105 group-hover:scale-110 transition-transform duration-[3000ms]">
            
            <div class="absolute inset-0 bg-gradient-to-r from-[#08080a] via-[#08080a]/80 to-blue-600/10"></div>
            
            <div class="absolute inset-0 flex flex-col md:flex-row items-center justify-between px-8 md:px-20 py-10 gap-10">
                
                <div class="max-w-xl z-10 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 bg-blue-600/20 backdrop-blur-xl border border-blue-500/30 px-4 py-2 rounded-2xl mb-6">
                        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-blue-400">Đặc quyền Premium</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tighter leading-[1.1]">
                        NÂNG CẤP <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">TRẢI NGHIỆM</span>
                    </h2>
                    
                    <p class="text-gray-400 text-sm md:text-base mb-8 max-w-md leading-relaxed">
                        Chỉ từ <span class="text-white font-bold">99k/tháng</span>, nhận ngay kho game 500+ tựa game bom tấn, giảm giá độc quyền và quà tặng vật phẩm hàng tuần.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <button class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-2xl font-bold text-sm transition-all duration-300 shadow-[0_0_30px_rgba(37,99,235,0.4)] hover:shadow-blue-500/60">
                            Đăng ký ngay — Miễn phí 7 ngày
                        </button>
                        <a href="#" class="text-gray-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Xem bảng giá</a>
                    </div>
                </div>
    
                <div class="hidden lg:grid grid-cols-2 gap-4 z-10">
                    <div class="glass p-5 rounded-[2rem] border-white/5 flex flex-col items-center text-center w-40">
                        <span class="text-2xl mb-2">🎮</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">500+ Games</span>
                    </div>
                    <div class="glass p-5 rounded-[2rem] border-white/5 flex flex-col items-center text-center w-40 mt-8">
                        <span class="text-2xl mb-2">💎</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Vật phẩm hiếm</span>
                    </div>
                    <div class="glass p-5 rounded-[2rem] border-white/5 flex flex-col items-center text-center w-40">
                        <span class="text-2xl mb-2">⚡</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Ưu tiên tải</span>
                    </div>
                    <div class="glass p-5 rounded-[2rem] border-white/5 flex flex-col items-center text-center w-40 mt-8">
                        <span class="text-2xl mb-2">🎁</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Quà sinh nhật</span>
                    </div>
                </div>
            </div>
    
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-blue-600/10 blur-[100px] rounded-full"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-purple-600/10 blur-[100px] rounded-full"></div>
        </div>
    </section>

    <!-- Highlight game -->
    <section class="container mx-auto px-4 md:px-10 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            
            <div class="space-y-6">
                <div class="group flex items-center justify-between bg-white/5 border border-white/10 p-4 rounded-2xl mb-8 shadow-lg shadow-black/20">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.5)]"></div>
                        <h3 class="text-lg font-black text-white uppercase tracking-tighter italic">Game Mới</h3>
                    </div>
                    <a href="#" class="text-[10px] font-bold text-gray-500 hover:text-green-400 uppercase tracking-widest transition-colors">Tất cả</a>
                </div>
                
                <div class="flex flex-col gap-2">
                    @forelse($newGames as $game)
                        <a href="{{ route('game.detail', $game->slug) }}" class="group flex items-center gap-4 p-3 rounded-2xl hover:bg-white/5 transition-all border border-transparent hover:border-white/10">
                            <img src="{{ $game->image ? asset('storage/' . $game->image) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=150' }}" 
                                 class="w-16 h-20 object-cover rounded-xl transition-transform duration-300 group-hover:scale-105" alt="{{ $game->title }}">
                            <div class="flex-grow">
                                <h4 class="text-sm font-bold text-white group-hover:text-green-400 transition-colors line-clamp-1">{{ $game->title }}</h4>
                                <span class="text-sm font-black text-blue-400">
                                    {{ number_format($game->sale_price ?? $game->price, 0, ',', '.') }} đ
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500 text-sm italic pl-2">Chưa có dữ liệu.</p>
                    @endforelse
                </div>
            </div>
    
            <div class="space-y-6">
                <div class="group flex items-center justify-between bg-white/5 border border-white/10 p-4 rounded-2xl mb-8 shadow-lg shadow-black/20">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-orange-500 rounded-full shadow-[0_0_10px_rgba(249,115,22,0.5)]"></div>
                        <h3 class="text-lg font-black text-white uppercase tracking-tighter italic">Phổ Biến</h3>
                    </div>
                    <a href="#" class="text-[10px] font-bold text-gray-500 hover:text-orange-400 uppercase tracking-widest transition-colors">Xem Top</a>
                </div>
                
                <div class="flex flex-col gap-2">
                    @forelse($popularGames as $game)
                        <a href="{{ route('game.detail', $game->slug) }}" class="group flex items-center gap-4 p-3 rounded-2xl hover:bg-white/5 transition-all border border-transparent hover:border-white/10">
                            <img src="{{ $game->image ? asset('storage/' . $game->image) : 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=150' }}" 
                                 class="w-16 h-20 object-cover rounded-xl transition-transform duration-300 group-hover:scale-105" alt="{{ $game->title }}">
                            <div class="flex-grow">
                                <h4 class="text-sm font-bold text-white group-hover:text-orange-400 transition-colors line-clamp-1">{{ $game->title }}</h4>
                                <span class="text-sm font-black text-blue-400">
                                    {{ number_format($game->sale_price ?? $game->price, 0, ',', '.') }} đ
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500 text-sm italic pl-2">Chưa có dữ liệu.</p>
                    @endforelse
                </div>
            </div>
    
            <div class="space-y-6">
                <div class="group flex items-center justify-between bg-white/5 border border-white/10 p-4 rounded-2xl mb-8 shadow-lg shadow-black/20">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-blue-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                        <h3 class="text-lg font-black text-white uppercase tracking-tighter italic">Sắp Ra Mắt</h3>
                    </div>
                    <a href="#" class="text-[10px] font-bold text-gray-500 hover:text-blue-400 uppercase tracking-widest transition-colors">Lịch trình</a>
                </div>
                
                <div class="flex flex-col gap-2">
                    @forelse($upcomingGames as $game)
                        <a href="{{ route('game.detail', $game->slug) }}" class="group flex items-center gap-4 p-3 rounded-2xl hover:bg-white/5 transition-all border border-transparent hover:border-white/10">
                            <img src="{{ $game->image ? asset('storage/' . $game->image) : 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=150' }}" 
                                 class="w-16 h-20 object-cover rounded-xl transition-transform duration-300 group-hover:scale-105" alt="{{ $game->title }}">
                            <div class="flex-grow">
                                <h4 class="text-sm font-bold text-white group-hover:text-blue-400 transition-colors line-clamp-1">{{ $game->title }}</h4>
                                {{-- Vì là sắp ra mắt nên tui để chữ Coming Soon màu đỏ cho ngầu, sau này ba làm cột release_date thì mình in ngày ra --}}
                                {{-- In ngày ra mắt thật từ Database (định dạng Ngày/Tháng/Năm) --}}
                                <span class="text-xs text-red-400 font-bold tracking-widest uppercase">
                                    {{ $game->release_date->format('d/m/Y') }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500 text-sm italic pl-2">Chưa có dữ liệu.</p>
                    @endforelse
                </div>
            </div>
    
        </div>
    </section>

    <!-- Tin tức về game -->
    <section class="container mx-auto px-4 md:px-10 py-16">
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full shadow-[0_0_15px_rgba(37,99,235,0.5)]"></div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white uppercase italic">Tin tức <span class="text-gray-500 font-light">Mới nhất</span></h2>
            </div>
            <button class="glass px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-all">Tất cả bài viết</button>
        </div>
    
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7 group cursor-pointer relative overflow-hidden rounded-[2.5rem] border border-white/10 shadow-2xl h-[500px]">
                <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200" 
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-[#08080a]/40 to-transparent"></div>
                
                <div class="absolute inset-0 p-8 md:p-12 flex flex-col justify-end">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="bg-blue-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest text-white">Sự kiện</span>
                        <span class="text-gray-400 text-xs font-bold italic">03 Tháng 2, 2026</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight group-hover:text-blue-400 transition-colors">
                        Lễ trao giải Game of the Year 2026: Những ứng cử viên sáng giá nhất đã lộ diện
                    </h3>
                    <p class="text-gray-400 text-sm md:text-base line-clamp-2 max-w-2xl mb-6">
                        Đêm vinh danh những siêu phẩm ngành game sắp bắt đầu. Cùng điểm qua danh sách những tựa game đang dẫn đầu cuộc đua năm nay...
                    </p>
                    <div class="flex items-center gap-2 text-blue-400 font-bold uppercase text-xs tracking-widest">
                        Đọc tiếp <span>→</span>
                    </div>
                </div>
            </div>
    
            <div class="lg:col-span-5 flex flex-col gap-6">
                
                <a href="#" class="group flex gap-4 glass p-4 rounded-[2rem] border-white/5 hover:border-white/20 transition-all shadow-lg">
                    <div class="w-32 h-24 flex-shrink-0 overflow-hidden rounded-2xl border border-white/5">
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="text-blue-500 text-[10px] font-bold uppercase tracking-widest mb-1">Cập nhật</span>
                        <h4 class="text-sm font-bold text-white line-clamp-2 leading-snug group-hover:text-blue-400 transition-colors">
                            Bản cập nhật 2.0 của Cyber Strike: Thêm hệ thống Ray-tracing thế hệ mới
                        </h4>
                        <p class="text-[10px] text-gray-500 mt-2 font-bold italic uppercase tracking-tighter">1 giờ trước</p>
                    </div>
                </a>
    
                <a href="#" class="group flex gap-4 glass p-4 rounded-[2rem] border-white/5 hover:border-white/20 transition-all shadow-lg">
                    <div class="w-32 h-24 flex-shrink-0 overflow-hidden rounded-2xl border border-white/5">
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="text-purple-500 text-[10px] font-bold uppercase tracking-widest mb-1">Phần cứng</span>
                        <h4 class="text-sm font-bold text-white line-clamp-2 leading-snug group-hover:text-blue-400 transition-colors">
                            Lộ diện console thế hệ tiếp theo: Hiệu năng gấp đôi, hỗ trợ 8K Native
                        </h4>
                        <p class="text-[10px] text-gray-500 mt-2 font-bold italic uppercase tracking-tighter">5 giờ trước</p>
                    </div>
                </a>
    
                <a href="#" class="group flex gap-4 glass p-4 rounded-[2rem] border-white/5 hover:border-white/20 transition-all shadow-lg">
                    <div class="w-32 h-24 flex-shrink-0 overflow-hidden rounded-2xl border border-white/5">
                        <img src="https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?auto=format&fit=crop&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="text-green-500 text-[10px] font-bold uppercase tracking-widest mb-1">Cộng đồng</span>
                        <h4 class="text-sm font-bold text-white line-clamp-2 leading-snug group-hover:text-blue-400 transition-colors">
                            Giải đấu E-Sports quốc tế sắp sửa đổ bộ vào Việt Nam vào mùa hè này
                        </h4>
                        <p class="text-[10px] text-gray-500 mt-2 font-bold italic uppercase tracking-tighter">Hôm qua</p>
                    </div>
                </a>
    
            </div>
        </div>
    </section>

@endsection