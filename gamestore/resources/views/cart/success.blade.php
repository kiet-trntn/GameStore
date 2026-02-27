@extends('layouts.user')

@section('content')
<main class="container mx-auto px-4 md:px-10 pt-40 pb-20 min-h-[80vh] flex items-center justify-center">
    
    <div class="glass relative p-10 md:p-16 rounded-[3rem] border-white/10 shadow-2xl text-center max-w-2xl w-full overflow-hidden">
        {{-- Hiệu ứng ánh sáng nền --}}
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-green-500/20 blur-[100px] rounded-full"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-blue-600/20 blur-[100px] rounded-full"></div>

        <div class="relative z-10 flex flex-col items-center">
            {{-- Icon Checkmark tỏa sáng --}}
            <div class="w-28 h-28 bg-green-500/10 rounded-full flex items-center justify-center mb-8 border border-green-500/30 shadow-[0_0_50px_rgba(34,197,94,0.3)] animate-bounce">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter mb-4">
                Thanh toán <span class="text-green-400">Thành công!</span>
            </h1>
            
            <p class="text-gray-400 text-sm md:text-base font-medium leading-relaxed mb-8 max-w-md mx-auto">
                Cảm ơn ba đã ủng hộ GameX! Các siêu phẩm đã được thêm vào thư viện của ba. Biên lai chi tiết đã được gửi qua Email đăng ký.
            </p>

            {{-- Mã đơn hàng --}}
            <div class="bg-white/5 border border-white/10 rounded-2xl py-4 px-8 mb-10 inline-block">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-1">Mã đơn hàng của ba</p>
                <p class="text-2xl font-black text-white tracking-widest">{{ session('order_code') }}</p>
            </div>

            {{-- Các nút điều hướng --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                {{-- Nút này mốt mình sẽ link tới trang Thư Viện Game (Lịch sử) --}}
                <a href="#" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-500/30 hover:-translate-y-1">
                    <i class="fas fa-gamepad mr-2"></i> Tới Thư viện Game
                </a>
                
                <a href="{{ route('home') }}" class="glass hover:bg-white/10 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all border border-white/10 hover:-translate-y-1">
                    Về Trang chủ
                </a>
            </div>
        </div>
    </div>

</main>
@endsection