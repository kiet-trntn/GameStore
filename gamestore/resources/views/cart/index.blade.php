@extends('layouts.user')

@section('content')

<main class="container mx-auto px-4 md:px-10 pt-32 pb-20 min-h-screen">
        
    @if($cartItems->count() > 0)
    {{-- TRẠNG THÁI: GIỎ HÀNG CÓ ĐỒ --}}
    <div id="cart-content">
        <h1 class="text-4xl font-black text-white uppercase italic tracking-tighter mb-10">
            Giỏ hàng <span class="text-blue-500" id="cart-title-count">({{ str_pad($cartItems->count(), 2, '0', STR_PAD_LEFT) }})</span>
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- Cột Trái: Danh Sách Game --}}
            <div class="lg:col-span-8 space-y-4" id="cart-items-wrapper">
                
                @foreach($cartItems as $item)
                <div class="cart-item group relative glass p-4 md:p-6 rounded-[2rem] border-white/5 flex items-center gap-6 hover:border-blue-500/30 transition-all duration-500" id="cart-item-{{ $item->id }}">
                    <div class="w-24 h-32 md:w-32 md:h-40 flex-shrink-0 rounded-2xl overflow-hidden shadow-2xl shadow-black/50">
                        <img src="{{ $item->game->image ? asset('storage/' . $item->game->image) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=400' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('game.detail', $item->game->slug) }}">
                                    <h3 class="text-lg md:text-xl font-black text-white group-hover:text-blue-400 transition-colors mb-1">{{ $item->game->title }}</h3>
                                </a>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $item->game->category->name ?? 'Gaming' }}</p>
                            </div>
                            
                            {{-- Nút Xóa (Gắn data-id để JS nhận diện) --}}
                            <button class="btn-remove-cart text-gray-600 hover:text-red-500 p-2 transition-colors" data-id="{{ $item->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        
                        <div class="mt-6 flex items-end justify-between">
                            <div class="flex items-center gap-3">
                                @if($item->game->sale_price)
                                    <span class="text-2xl font-black text-white tracking-tighter">{{ number_format($item->game->sale_price, 0, ',', '.') }}₫</span>
                                    <span class="text-sm text-gray-500 line-through font-bold">{{ number_format($item->game->price, 0, ',', '.') }}₫</span>
                                    @php $percent = round((($item->game->price - $item->game->sale_price) / $item->game->price) * 100); @endphp
                                    <span class="bg-blue-600/20 text-blue-400 text-[10px] font-black px-2 py-0.5 rounded border border-blue-400/20 uppercase">-{{ $percent }}%</span>
                                @else
                                    <span class="text-2xl font-black text-white tracking-tighter">{{ number_format($item->game->price, 0, ',', '.') }}₫</span>
                                @endif
                            </div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] italic">Digital Edition</span>
                        </div>
                    </div>
                </div>
                @endforeach
                
            </div>

            {{-- Cột Phải: Tổng Tiền Đơn Hàng --}}
            <div class="lg:col-span-4">
                <div class="sticky top-32 glass p-8 rounded-[2.5rem] border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/10 blur-[60px] rounded-full"></div>
                    
                    <h3 class="text-xl font-black text-white uppercase italic tracking-tight mb-8">Thông tin <span class="text-blue-500">Đơn hàng</span></h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Tạm tính</span>
                            <span class="text-white font-bold tracking-tight" id="subtotal-display">{{ number_format($subTotal, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Khuyến mãi</span>
                            <span class="text-red-500 font-bold tracking-tight">0₫</span>
                        </div>
                        <div class="pt-4 border-t border-white/5 flex justify-between">
                            <span class="text-white font-black uppercase italic tracking-tighter">Tổng cộng</span>
                            <span class="text-3xl font-black text-blue-400 tracking-tighter" id="total-display">{{ number_format($subTotal, 0, ',', '.') }}₫</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black text-xs uppercase tracking-[0.2em] py-5 rounded-2xl shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-1">
                            Thanh toán ngay
                        </button>
                        <p class="text-[9px] text-gray-500 text-center font-bold uppercase tracking-widest">Đảm bảo an toàn 100% với SSL</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- TRẠNG THÁI: GIỎ HÀNG TRỐNG --}}
    <div id="empty-cart" class="{{ $cartItems->count() > 0 ? 'hidden' : '' }} min-h-[60vh] flex flex-col items-center justify-center text-center">
        <div class="relative mb-8">
            <div class="absolute inset-0 bg-blue-600/20 blur-[100px] rounded-full"></div>
            <div class="relative w-40 h-40 glass rounded-full flex items-center justify-center text-6xl shadow-2xl">🛒</div>
        </div>
        <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-4">Giỏ hàng của bạn đang trống</h2>
        <p class="text-gray-500 max-w-sm mb-10 font-medium">Có vẻ như bạn chưa chọn được siêu phẩm nào. Hãy khám phá thư viện game đồ sộ của chúng tôi ngay!</p>
        <a href="{{ route('game') }}" class="bg-white text-black px-10 py-4 rounded-full font-black text-xs uppercase tracking-[0.2em] hover:bg-blue-600 hover:text-white transition-all shadow-xl">
            Quay lại Cửa hàng
        </a>
    </div>

</main>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-remove-cart').forEach(button => {
        button.addEventListener('click', function() {
            let cartId = this.getAttribute('data-id');
            let url = `/gio-hang/xoa/${cartId}`;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Bật Popup xác nhận trước khi xóa
            Swal.fire({
                title: 'Khoan đã!',
                text: "Ba chắc chắn muốn đá tựa game này khỏi giỏ chứ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#2563EB',
                confirmButtonText: 'Đúng, xóa đi!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // 1. Hiệu ứng mờ dần và Xóa HTML game đó đi
                            let itemRow = document.getElementById('cart-item-' + cartId);
                            itemRow.style.opacity = '0';
                            setTimeout(() => { 
                                itemRow.remove(); 
                                
                                // Nếu xóa xong mà giỏ hàng trống trơn (count = 0)
                                if (data.cart_count == 0) {
                                    document.getElementById('cart-content').classList.add('hidden');
                                    document.getElementById('empty-cart').classList.remove('hidden');
                                }
                            }, 300);

                            // 2. Cập nhật lại số lượng và Tiền trên màn hình
                            document.getElementById('cart-title-count').innerText = `(${data.cart_count.toString().padStart(2, '0')})`;
                            document.getElementById('subtotal-display').innerText = data.sub_total;
                            document.getElementById('total-display').innerText = data.sub_total;

                            // Cập nhật số đỏ trên Header (nếu có)
                            let badge = document.getElementById('cart-count-badge');
                            if(badge) {
                                badge.innerText = data.cart_count;
                                if(data.cart_count == 0) badge.classList.add('hidden');
                            }

                            // 3. Báo thành công
                            Swal.fire({
                                icon: 'success',
                                title: 'Đã xóa!',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }
                    })
                    .catch(error => console.error(error));
                }
            });
        });
    });
</script>
@endsection