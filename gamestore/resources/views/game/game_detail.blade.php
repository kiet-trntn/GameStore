@extends('layouts.user')

@section('content')

<main class="container mx-auto px-4 md:px-10 pt-32 pb-20">
        
    {{-- PHẦN 1: HERO SECTION --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-20">
        {{-- Ảnh đại diện to --}}
        <div class="lg:col-span-7">
            <div class="relative group rounded-[2.5rem] overflow-hidden border border-white/10 shadow-2xl aspect-video">
                <img src="{{ $game->image ? asset('storage/' . $game->image) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200' }}" 
                     class="w-full h-full object-cover transition duration-700 group-hover:scale-105" alt="{{ $game->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0d0d0f]/60 to-transparent"></div>
                
                {{-- Nút Play Trailer (Chỉ hiện nếu có link Youtube) --}}
                @if($game->trailer_link)
                <div class="absolute inset-0 flex items-center justify-center">
                    <a href="{{ $game->trailer_link }}" target="_blank" class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-2xl shadow-blue-500/50 hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Thông tin chữ --}}
        <div class="lg:col-span-5 flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-6">
                <span class="bg-blue-600/20 text-blue-400 text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest border border-blue-400/20">
                    {{ $game->category->name }}
                </span>
                <span class="text-gray-500 text-xs font-bold uppercase tracking-widest italic flex items-center gap-2">
                    <i class="fas fa-eye"></i> {{ number_format($game->views) }} Lượt Xem
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none mb-4">
                {{ $game->title }}
            </h1>
            
            <p class="text-gray-400 text-sm font-bold uppercase tracking-widest mb-8">
                Phát triển bởi: <span class="text-white">{{ $game->developer ?? 'Đang cập nhật' }}</span>
            </p>

            <div class="glass p-8 rounded-[2rem] border-white/10 mb-8 shadow-inner bg-white/5 backdrop-blur-md">
                <div class="flex items-end gap-4 mb-6">
                    @if($game->sale_price)
                        <span class="text-4xl font-black text-white tracking-tighter">{{ number_format($game->sale_price, 0, ',', '.') }}đ</span>
                        <span class="text-lg text-gray-500 line-through mb-1 font-bold">{{ number_format($game->price, 0, ',', '.') }}đ</span>
                        @php
                            // Tự động tính phần trăm giảm giá
                            $percent = round((($game->price - $game->sale_price) / $game->price) * 100);
                        @endphp
                        <span class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-md mb-2">-{{ $percent }}%</span>
                    @else
                        <span class="text-4xl font-black text-white tracking-tighter">{{ number_format($game->price, 0, ',', '.') }}đ</span>
                    @endif
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <button class="flex-grow bg-blue-600 hover:bg-blue-500 text-white font-black text-xs uppercase tracking-widest py-5 rounded-2xl shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-1">Mua Ngay</button>
                    <button class="btn-add-cart glass px-8 py-5 rounded-2xl hover:bg-white/10 transition-all border border-white/10 text-white" data-id="{{ $game->id }}">
                        <i class="fas fa-cart-plus text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- PHẦN 2: BỘ SƯU TẬP ẢNH (SCREENSHOTS) --}}
    @if($game->screenshots && count($game->screenshots) > 0)
    <div class="mb-20">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-1 h-6 bg-blue-600 rounded-full"></div>
            <h2 class="text-xl font-black text-white uppercase italic tracking-widest">Ảnh thực tế in-game</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Lặp mảng hình ảnh lưu trong DB, lấy tối đa 4 hình --}}
            @foreach(array_slice($game->screenshots, 0, 4) as $key => $imagePath)
                @if($key == 3 && count($game->screenshots) > 4)
                    {{-- Hình thứ 4 nếu còn dư ảnh thì làm hiệu ứng làm mờ +12 ẢNH --}}
                    <div class="aspect-video rounded-3xl overflow-hidden border border-white/10 relative cursor-pointer group">
                        <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-full object-cover opacity-40 transition duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 flex items-center justify-center text-xs font-black uppercase tracking-widest text-white">
                            +{{ count($game->screenshots) - 3 }} ẢNH
                        </div>
                    </div>
                @else
                    <div class="aspect-video rounded-3xl overflow-hidden border border-white/10 cursor-zoom-in">
                        <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-full object-cover hover:scale-110 transition duration-700">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- PHẦN 3: MÔ TẢ & CẤU HÌNH --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mb-24">
        {{-- Cột Mô tả --}}
        <div class="lg:col-span-7">
            <h3 class="text-2xl font-black text-white uppercase italic tracking-tight mb-8">Mô tả <span class="text-blue-500 underline decoration-2 underline-offset-8">chi tiết</span></h3>
            <div class="text-gray-300 leading-relaxed space-y-4 text-sm md:text-base font-medium prose prose-invert">
                {!! nl2br(e($game->description)) !!}
            </div>
        </div>

        {{-- Cột Cấu hình --}}
        <div class="lg:col-span-5">
            <div class="glass rounded-[2.5rem] p-10 border-white/5 relative overflow-hidden bg-white/5 backdrop-blur-lg">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 blur-[60px] rounded-full"></div>
                <h3 class="text-xl font-black text-white uppercase italic tracking-tight mb-8">Cấu hình <span class="text-blue-500">Yêu cầu</span></h3>
                
                <div class="text-sm text-gray-300 whitespace-pre-line leading-loose">
                    {{ $game->requirements ?? 'Đang cập nhật thông tin cấu hình...' }}
                </div>
            </div>
        </div>
    </div>

    {{-- PHẦN 4: GỢI Ý GAME CÙNG THỂ LOẠI --}}
    @if($relatedGames->count() > 0)
    <section>
        <div class="flex items-center justify-between mb-10">
            <h3 class="text-2xl font-black text-white uppercase italic tracking-tighter">Có thể bạn <span class="text-blue-500">cũng thích</span></h3>
            <a href="{{ route('game', ['category' => $game->category->slug]) }}" class="text-xs font-black text-gray-500 uppercase tracking-widest hover:text-white transition">Xem danh mục →</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedGames as $related)
            <a href="{{ route('game.detail', $related->slug) }}" class="group glass p-3 rounded-[2rem] border-white/5 hover:border-blue-500/30 transition-all duration-500 bg-white/5">
                <div class="aspect-[3/4] rounded-3xl overflow-hidden mb-4 relative">
                    <img src="{{ $related->image ? asset('storage/' . $related->image) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=400' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0d0d0f] via-transparent to-transparent opacity-80"></div>
                </div>
                <div class="px-2 pb-2 text-center">
                    <h4 class="font-bold text-white group-hover:text-blue-400 transition-colors truncate mb-1">{{ $related->title }}</h4>
                    <span class="text-blue-400 font-black tracking-tighter">{{ number_format($related->sale_price ?? $related->price, 0, ',', '.') }}₫</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</main>



@endsection
