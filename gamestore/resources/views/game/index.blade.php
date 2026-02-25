@extends('layouts.user')

@section('content')

<main class="container mx-auto px-4 md:px-10 pt-32 pb-20">

    <!-- Tiêu đề và Tìm kiếm nhanh -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
        <div>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter">
                Khám phá <span class="text-blue-500">Thư viện</span>
            </h1>
            <p class="text-gray-500 mt-2 font-medium">Hiện có {{ number_format($totalGames) }} tựa game sẵn sàng cho bạn</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" id="ajax-search" value="{{ request('search') }}" placeholder="Tìm tên game..." class="bg-white/5 border border-white/10 rounded-xl py-3 px-5 text-sm w-64 md:w-80 focus:outline-none focus:border-blue-500 text-white transition-all">
            </div>
            <button class="glass p-3 rounded-xl hover:bg-white/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
        </div>
    </div>

    <div id="game-list-container" class="lg:col-span-3">
        @include('game.partials.game_grid')
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


@section('scripts')
<script>
    let searchTimer; // Biến dùng để trì hoãn (debounce) gõ phím
    
    document.getElementById('ajax-search').addEventListener('input', function() {
        clearTimeout(searchTimer);
        let keyword = this.value;
        
        // Lấy URL hiện tại để giữ nguyên các bộ lọc Thể loại, Giá...
        let url = new URL(window.location.href);
        url.searchParams.set('search', keyword);

        // Đợi khách hàng ngừng gõ 500ms (nửa giây) rồi mới gửi Request (tránh sập server)
        searchTimer = setTimeout(() => {
            // Thêm hiệu ứng mờ nhẹ để khách biết web đang tải
            document.getElementById('game-list-container').style.opacity = '0.5';

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Khai báo đây là AJAX
                }
            })
            .then(response => response.text())
            .then(html => {
                // Nhét HTML mới vào thay thế
                document.getElementById('game-list-container').innerHTML = html;
                document.getElementById('game-list-container').style.opacity = '1';
                
                // Đổi đường dẫn URL trên thanh trình duyệt mà không cần tải trang
                window.history.pushState({}, '', url);
            })
            .catch(error => console.error('Lỗi AJAX:', error));
        }, 500);
    });
</script>

@endsection

@endsection

