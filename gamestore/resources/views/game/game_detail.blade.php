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

    {{-- ======================================= --}}
    {{-- KHU VỰC ĐÁNH GIÁ & BÌNH LUẬN CỦA GAME THỦ --}}
    {{-- ======================================= --}}
    <div class="mt-32 border-t border-white/5 pt-20" id="review-section">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h3 class="text-3xl md:text-4xl font-black text-white uppercase italic tracking-tighter mb-2">
                    Đánh giá <span class="text-yellow-400">Cộng đồng</span>
                </h3>
                <p class="text-gray-400 font-medium text-sm">Hội anh em đồng dâm nói gì về tựa game này?</p>
            </div>
            <div class="hidden md:block">
                <span class="bg-yellow-500/20 text-yellow-400 font-black px-4 py-2 rounded-xl border border-yellow-500/20">
                    <i class="fas fa-star mr-1"></i> {{ $game->reviews->count() }} Đánh giá
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- CỘT TRÁI: FORM VIẾT ĐÁNH GIÁ --}}
            <div class="lg:col-span-4">
                <div class="glass p-8 rounded-[2.5rem] border-white/5 sticky top-32 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-20 -left-20 w-40 h-40 bg-yellow-500/10 blur-[60px] rounded-full"></div>
                    
                    <h4 class="text-xl font-black text-white uppercase italic tracking-tight mb-6">Viết cảm nhận của ba</h4>

                    @auth
                        <form action="{{ route('review.store', $game->id) }}" method="POST">
                            @csrf
                            
                            {{-- Khung chọn số sao (1-5) --}}
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Chấm điểm siêu phẩm</label>
                                <div class="flex gap-2" id="star-rating-container">
                                    {{-- "Má" xài thẻ SVG siêu nhẹ thay cho FontAwesome để chống lỗi tàng hình --}}
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg data-rating="{{ $i }}" class="star-btn w-10 h-10 text-gray-600 cursor-pointer hover:scale-110 transition-all duration-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                {{-- Ô input tàng hình để gửi số sao lên Controller --}}
                                <input type="hidden" name="rating" id="rating-input" value="0">
                            </div>

                            <div class="mb-8">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Bình luận</label>
                                <textarea name="comment" rows="4" placeholder="Cốt truyện hay không ba? Đồ họa xịn không?" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:outline-none focus:border-yellow-500 transition-all resize-none"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black text-xs uppercase tracking-[0.2em] py-4 rounded-2xl shadow-lg shadow-yellow-500/20 transition-all hover:-translate-y-1">
                                Chốt Đánh Giá
                            </button>
                        </form>
                    @else
                        {{-- Nếu chưa đăng nhập thì chặn lại không cho viết --}}
                        <div class="text-center py-8 bg-white/5 rounded-3xl border border-white/10">
                            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-lock text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-400 text-sm mb-6 font-medium px-4">Đăng nhập để lại dấu ấn của ba cho siêu phẩm này nhé!</p>
                            <a href="{{ route('login') }}" class="inline-block bg-white text-black px-8 py-3 rounded-full font-black text-xs uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-xl">
                                Đăng nhập ngay
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- CỘT PHẢI: DANH SÁCH BÌNH LUẬN ĐÃ CÓ --}}
            <div class="lg:col-span-8 space-y-6">
                @if($game->reviews && $game->reviews->count() > 0)
                    @foreach($game->reviews as $review)
                        <div class="glass p-6 md:p-8 rounded-[2rem] border-white/5 flex gap-5 md:gap-6 hover:bg-white/[0.03] transition-colors">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=EAB308&color=000" class="w-12 h-12 md:w-14 md:h-14 rounded-full border-2 border-white/10 shadow-lg">
                            </div>
                            <div class="flex-grow">
                                <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-3 gap-2">
                                    <div>
                                        <h5 class="text-white font-black text-lg">{{ $review->user->name }}</h5>
                                        <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex gap-1">
                                        {{-- In ra đúng số sao vàng dựa vào rating của khách --}}
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 md:w-5 md:h-5 {{ $i <= $review->rating ? 'text-yellow-400 drop-shadow-[0_0_8px_rgba(250,204,21,0.5)]' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-300 text-sm md:text-base leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Không có đánh giá nào --}}
                    <div class="min-h-[30vh] flex flex-col items-center justify-center text-center glass rounded-[2.5rem] border-white/5">
                        <i class="fas fa-comment-slash text-5xl text-gray-600 mb-6"></i>
                        <h5 class="text-xl font-black text-white uppercase italic tracking-tighter mb-2">Chưa có đánh giá nào</h5>
                        <p class="text-gray-500 font-medium">Siêu phẩm này vẫn đang chờ người "bóc tem". Ba thử liền đi!</p>
                    </div>
                @endif
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

@section('scripts')
<script>
    // ==========================================
    // XỬ LÝ CLICK CHỌN SỐ SAO (RATING)
    // ==========================================
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');

    if (stars.length > 0) {
        stars.forEach(star => {
            star.addEventListener('click', function() {
                // Lấy số sao vừa click
                let ratingValue = this.getAttribute('data-rating');
                
                // Nạp vô thẻ input tàng hình để mốt submit form
                ratingInput.value = ratingValue;
                
                // Duyệt qua 5 ngôi sao, thằng nào bé hơn hoặc bằng thì tô màu Vàng, lớn hơn thì tô Xám
                stars.forEach(s => {
                    if(s.getAttribute('data-rating') <= ratingValue) {
                        s.classList.remove('text-gray-600');
                        s.classList.add('text-yellow-400', 'drop-shadow-[0_0_10px_rgba(250,204,21,0.6)]');
                    } else {
                        s.classList.remove('text-yellow-400', 'drop-shadow-[0_0_10px_rgba(250,204,21,0.6)]');
                        s.classList.add('text-gray-600');
                    }
                });
            });
        });
    }
</script>
@endsection