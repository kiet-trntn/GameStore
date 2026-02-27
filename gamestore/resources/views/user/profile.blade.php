@extends('layouts.user')

@section('content')
<main class="container mx-auto px-4 md:px-10 pt-32 pb-20 min-h-screen">
    
    <div class="mb-10">
        <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter mb-2">
            Hồ sơ <span class="text-blue-500">Cá nhân</span>
        </h1>
        <p class="text-gray-400 font-medium text-sm">Quản lý thông tin và bảo mật tài khoản</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        {{-- CỘT TRÁI: THẺ THÀNH VIÊN VIP --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="glass p-8 rounded-[2.5rem] border-white/5 relative overflow-hidden shadow-2xl group">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-blue-600/20 blur-[60px] rounded-full group-hover:bg-blue-500/30 transition-all duration-700"></div>
                
                <div class="flex flex-col items-center text-center relative z-10">
                    <div class="relative mb-6">
                        {{-- Avatar tự động tạo từ Tên của user --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563EB&color=fff&size=150" 
                             class="w-32 h-32 rounded-full border-4 border-white/10 shadow-[0_0_30px_rgba(37,99,235,0.3)] group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-[#08080a] rounded-full" title="Đang Online"></span>
                    </div>
                    
                    <h2 class="text-2xl font-black text-white mb-1 truncate w-full">{{ $user->name }}</h2>
                    <p class="text-xs text-blue-400 font-bold uppercase tracking-widest mb-6">Game thủ Premium</p>
                    
                    <div class="w-full pt-6 border-t border-white/10 flex justify-between text-left">
                        <div>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1">Ngày tham gia</p>
                            <p class="text-sm text-white font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1">Trạng thái</p>
                            <p class="text-sm text-green-400 font-bold"><i class="fas fa-shield-alt mr-1"></i> Đã xác thực</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: FORM CẬP NHẬT & ĐỔI MẬT KHẨU --}}
        <div class="lg:col-span-8 space-y-10">
            
            {{-- FORM 1: THÔNG TIN CÁ NHÂN --}}
            <div class="glass p-8 md:p-10 rounded-[2.5rem] border-white/5 shadow-2xl relative overflow-hidden">
                <h3 class="text-xl font-black text-white uppercase italic tracking-tight mb-8">Thông tin <span class="text-blue-500">Cơ bản</span></h3>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tên hiển thị</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:outline-none focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email đăng nhập</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:outline-none focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-500/30 hover:-translate-y-1">
                        Lưu Thay Đổi
                    </button>
                </form>
            </div>

            {{-- FORM 2: ĐỔI MẬT KHẨU --}}
            <div class="glass p-8 md:p-10 rounded-[2.5rem] border-white/5 shadow-2xl relative overflow-hidden">
                <h3 class="text-xl font-black text-white uppercase italic tracking-tight mb-8">Đổi <span class="text-red-500">Mật khẩu</span></h3>
                
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    <div class="space-y-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" placeholder="Nhập mật khẩu cũ..." class="w-full md:w-1/2 bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:outline-none focus:border-red-500 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Mật khẩu mới</label>
                                <input type="password" name="password" placeholder="Từ 8 ký tự trở lên..." class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:outline-none focus:border-red-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nhập lại mật khẩu mới</label>
                                <input type="password" name="password_confirmation" placeholder="Gõ lại cho chắc..." class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:outline-none focus:border-red-500 transition-all">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-red-500/30 hover:-translate-y-1">
                        Cập nhật bảo mật
                    </button>
                </form>
            </div>

        </div>
    </div>
</main>
@endsection

@section('scripts')
{{-- Bùa SweetAlert để bắt thông báo lỗi / thành công từ Controller trả về --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Ngon lành!',
            text: '{{ session("success") }}',
            background: '#1a1a24', color: '#fff',
            confirmButtonColor: '#2563EB'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Khoan đã!',
            html: `
                <ul class="text-left text-sm text-red-400">
                    @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            `,
            background: '#1a1a24', color: '#fff',
            confirmButtonColor: '#d33'
        });
    @endif
</script>
@endsection