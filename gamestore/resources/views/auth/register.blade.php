<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gia Nhập Biệt Đội | GAMEX Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #050507; 
            color: #eee;
        }
        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(25px) saturate(150%);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: radial-gradient(at 0% 100%, rgba(99, 102, 241, 0.15), transparent 50%),
                        radial-gradient(at 100% 0%, rgba(37, 99, 235, 0.1), transparent 50%);
        }
        .input-focus:focus {
            box-shadow: 0 0 25px rgba(37, 99, 235, 0.25);
        }
        /* Tùy chỉnh thanh cuộn cho form dài */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <div class="bg-mesh"></div>

    <div class="flex h-full w-full">
        <div class="w-full lg:w-2/5 flex items-center justify-center px-6 md:px-16 overflow-y-auto custom-scroll">
            <div class="w-full max-w-md py-12">
                <div class="mb-10 text-center lg:text-left">
                    <div class="lg:hidden flex justify-center mb-6">
                        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white italic text-2xl font-black shadow-lg">G</div>
                    </div>
                    <h1 class="text-4xl font-black text-white uppercase italic tracking-tighter mb-2">Tạo <span class="text-blue-500">Tài Khoản</span></h1>
                    <p class="text-gray-500 font-medium">Bắt đầu hành trình chinh phục đỉnh cao ngay hôm nay.</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    {{-- Ô Tên hiển thị --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Tên hiển thị (Gamertag)</label>
                        <div class="relative group">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </span>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="KietTran_99" 
                                   class="w-full bg-white/5 border {{ $errors->has('name') ? 'border-red-500' : 'border-white/10' }} rounded-2xl py-4 pl-14 pr-6 text-sm text-white focus:outline-none focus:border-blue-500 transition-all input-focus">
                        </div>
                        @error('name') <p class="text-xs text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ô Email --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Địa chỉ Email</label>
                        <div class="relative group">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="example@gamex.com" 
                                   class="w-full bg-white/5 border {{ $errors->has('email') ? 'border-red-500' : 'border-white/10' }} rounded-2xl py-4 pl-14 pr-6 text-sm text-white focus:outline-none focus:border-blue-500 transition-all input-focus">
                        </div>
                        @error('email') <p class="text-xs text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ô Mật khẩu --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Mật mã bảo mật</label>
                        <div class="relative group">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </span>
                            <input type="password" name="password" placeholder="••••••••" 
                                   class="w-full bg-white/5 border {{ $errors->has('password') ? 'border-red-500' : 'border-white/10' }} rounded-2xl py-4 pl-14 pr-6 text-sm text-white focus:outline-none focus:border-blue-500 transition-all input-focus">
                        </div>
                        @error('password') <p class="text-xs text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ô Nhập lại mật khẩu --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Xác nhận mật mã</label>
                        <div class="relative group">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </span>
                            {{-- Chú ý name="password_confirmation" là bắt buộc để Laravel tự so sánh với ô password ở trên --}}
                            <input type="password" name="password_confirmation" placeholder="••••••••" 
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-14 pr-6 text-sm text-white focus:outline-none focus:border-blue-500 transition-all input-focus">
                        </div>
                    </div>

                    {{-- Checkbox Đồng ý điều khoản --}}
                    <div class="flex items-start gap-3 py-2 px-1">
                        <input type="checkbox" name="terms" value="1" class="mt-1 w-4 h-4 rounded border-white/10 bg-white/5 text-blue-600 focus:ring-blue-500 transition cursor-pointer">
                        <label class="text-[11px] text-gray-500 leading-tight">
                            Tôi đồng ý với các <a href="#" class="text-blue-500 hover:underline">Điều khoản dịch vụ</a> và <a href="#" class="text-blue-500 hover:underline">Chính sách bảo mật</a> của GameX.
                        </label>
                    </div>
                    @error('terms') <p class="text-xs text-red-500 font-bold ml-1 -mt-2">{{ $message }}</p> @enderror

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black text-xs uppercase tracking-[0.2em] py-5 rounded-2xl shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                        Tạo tài khoản ngay
                    </button>
                </form>

                <p class="mt-10 text-center text-sm text-gray-500">
                    Đã có tài khoản? 
                    <a href="{{ route('login') }}" class="text-white font-bold hover:text-blue-500 transition-colors uppercase tracking-widest ml-1">Đăng nhập</a>
                </p>
            </div>
        </div>

        <div class="hidden lg:block lg:w-3/5 relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200" 
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-[10000ms] hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-l from-transparent via-[#050507]/20 to-[#050507]"></div>
            
            <div class="absolute top-12 right-12 flex items-center gap-3">
                <span class="text-2xl font-black text-white italic tracking-tighter uppercase">GameX</span>
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white italic text-2xl font-black shadow-2xl shadow-blue-600/40">G</div>
            </div>

            <div class="absolute bottom-16 right-12 text-right">
                <p class="text-4xl font-black text-white uppercase italic tracking-tighter leading-tight mb-2">
                    KẾT NỐI VỚI <br><span class="text-blue-500">TRIỆU GAME THỦ</span>
                </p>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Gia nhập cộng đồng gaming lớn nhất VN</p>
            </div>
        </div>
    </div>
</body>
</html>