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