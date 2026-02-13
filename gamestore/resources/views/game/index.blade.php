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
            <div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Thể loại</h3>
                <div class="flex flex-wrap gap-2">
                    <button class="px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold">Tất cả</button>
                    <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-gray-400 text-xs font-bold hover:bg-white/10 transition">Hành động</button>
                    <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-gray-400 text-xs font-bold hover:bg-white/10 transition">Phiêu lưu</button>
                    <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-gray-400 text-xs font-bold hover:bg-white/10 transition">Nhập vai</button>
                    <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-gray-400 text-xs font-bold hover:bg-white/10 transition">Chiến thuật</button>
                    <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-gray-400 text-xs font-bold hover:bg-white/10 transition">Kinh dị</button>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Khoảng giá</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 group cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded-lg border-white/10 bg-white/5 text-blue-600 focus:ring-blue-600 transition">
                        <span class="text-sm text-gray-400 group-hover:text-white transition">Dưới 250,000₫</span>
                    </label>
                    <label class="flex items-center gap-3 group cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded-lg border-white/10 bg-white/5 text-blue-600 focus:ring-blue-600 transition">
                        <span class="text-sm text-gray-400 group-hover:text-white transition">250k - 500k</span>
                    </label>
                    <label class="flex items-center gap-3 group cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 rounded-lg border-white/10 bg-white/5 text-blue-600 focus:ring-blue-600 transition">
                        <span class="text-sm text-gray-400 group-hover:text-white transition">Trên 500,000₫</span>
                    </label>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Nền tảng</h3>
                <div class="flex gap-4">
                    <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center hover:bg-white/10 transition">🪟</button>
                    <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center hover:bg-white/10 transition">🍎</button>
                    <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center hover:bg-white/10 transition">🐧</button>
                </div>
            </div>
        </aside>

        <!-- Danh Sách Thẻ Game (Game Cards) -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                
                <div class="group relative bg-white/[0.02] rounded-[2rem] overflow-hidden border border-white/5 hover:border-blue-500/40 transition-all duration-500 hover:-translate-y-2">
                    <div class="aspect-[3/4] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-transparent to-transparent opacity-90"></div>
                        
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="bg-blue-600 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase shadow-lg">Bán chạy</span>
                            <span class="bg-black/50 backdrop-blur-md text-[10px] font-black px-2.5 py-1 rounded-lg uppercase border border-white/10">-20%</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg text-white group-hover:text-blue-400 transition-colors truncate">Black Myth: Wukong</h3>
                        </div>
                        <p class="text-gray-500 text-xs mb-4">Action RPG • Soul-like</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-xs text-gray-500 line-through block">1,200k</span>
                                <span class="font-black text-blue-400 text-xl tracking-tighter">950.000₫</span>
                            </div>
                            <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="group relative bg-white/[0.02] rounded-[2rem] overflow-hidden border border-white/5 hover:border-blue-500/40 transition-all duration-500 hover:-translate-y-2">
                    <div class="aspect-[3/4] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-transparent to-transparent opacity-90"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-white group-hover:text-blue-400 transition-colors">Cyber Strike: Tokyo</h3>
                        <p class="text-gray-500 text-xs mb-4">Cyberpunk • Open World</p>
                        <div class="flex justify-between items-center">
                            <span class="font-black text-blue-400 text-xl tracking-tighter">550.000₫</span>
                            <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="group relative bg-white/[0.02] rounded-[2rem] overflow-hidden border border-white/5 hover:border-blue-500/40 transition-all duration-500 hover:-translate-y-2">
                    <div class="aspect-[3/4] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?auto=format&fit=crop&w=600" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] via-transparent to-transparent opacity-90"></div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-red-500 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase shadow-lg">Cực hiếm</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-white group-hover:text-blue-400 transition-colors">Elden Realm</h3>
                        <p class="text-gray-500 text-xs mb-4">Action • Dark Fantasy</p>
                        <div class="flex justify-between items-center">
                            <span class="font-black text-blue-400 text-xl tracking-tighter">1,150.000₫</span>
                            <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                </div>

            <div class="mt-16 flex justify-center gap-2">
                <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-blue-500 border-blue-500/50">1</button>
                <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-gray-500 hover:text-white transition">2</button>
                <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-gray-500 hover:text-white transition">3</button>
                <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-gray-500 hover:text-white transition">...</button>
                <button class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-gray-500 hover:text-white transition">→</button>
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
            
            <a href="#" class="group flex items-center gap-6 glass p-2 rounded-[2rem] border-white/5 hover:border-blue-500/30 transition-all duration-500">
                <div class="relative w-32 h-40 flex-shrink-0 overflow-hidden rounded-[1.5rem]">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                    <div class="absolute -top-2 -left-2 w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-xl rotate-[-12deg] group-hover:rotate-0 transition-transform">
                        <span class="text-2xl font-black text-white italic">1</span>
                    </div>
                </div>
                <div class="flex-grow pr-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Action RPG</span>
                        <div class="flex text-yellow-500 text-[8px]">
                            ★★★★★
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-white group-hover:text-blue-400 transition-colors mb-2 leading-tight">Elden Ring: Shadow of the Erdtree</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-black text-white">890.000₫</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">150k+ người mua</span>
                    </div>
                </div>
            </a>
    
            <a href="#" class="group flex items-center gap-6 glass p-2 rounded-[2rem] border-white/5 hover:border-blue-500/30 transition-all duration-500">
                <div class="relative w-32 h-40 flex-shrink-0 overflow-hidden rounded-[1.5rem]">
                    <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute -top-2 -left-2 w-12 h-12 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center justify-center shadow-xl rotate-[-12deg] group-hover:rotate-0 transition-transform">
                        <span class="text-2xl font-black text-white italic">2</span>
                    </div>
                </div>
                <div class="flex-grow pr-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Simulation</span>
                    </div>
                    <h3 class="text-xl font-black text-white group-hover:text-blue-400 transition-colors mb-2 leading-tight">Cyberpunk: Phantom Liberty</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-black text-white">550.000₫</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase">98k+ người mua</span>
                    </div>
                </div>
            </a>
    
            <a href="#" class="group flex items-center gap-6 glass p-2 rounded-[2rem] border-white/5 hover:border-blue-500/30 transition-all duration-500">
                <div class="relative w-32 h-40 flex-shrink-0 overflow-hidden rounded-[1.5rem]">
                    <img src="https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?auto=format&fit=crop&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute -top-2 -left-2 w-12 h-12 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center justify-center shadow-xl rotate-[-12deg] group-hover:rotate-0 transition-transform">
                        <span class="text-2xl font-black text-white italic">3</span>
                    </div>
                </div>
                <div class="flex-grow pr-6">
                    <h3 class="text-xl font-black text-white group-hover:text-blue-400 transition-colors mb-2 leading-tight">Ghost of Tsushima</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-black text-white">720.000₫</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase">82k+ người mua</span>
                    </div>
                </div>
            </a>
    
            <a href="#" class="group flex items-center gap-6 glass p-2 rounded-[2rem] border-white/5 hover:border-blue-500/30 transition-all duration-500">
                <div class="relative w-32 h-40 flex-shrink-0 overflow-hidden rounded-[1.5rem]">
                    <img src="https://images.unsplash.com/photo-1605898835373-02f74446e8ea?auto=format&fit=crop&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute -top-2 -left-2 w-12 h-12 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center justify-center shadow-xl rotate-[-12deg] group-hover:rotate-0 transition-transform">
                        <span class="text-2xl font-black text-white italic">4</span>
                    </div>
                </div>
                <div class="flex-grow pr-6">
                    <h3 class="text-xl font-black text-white group-hover:text-blue-400 transition-colors mb-2 leading-tight">Resident Evil 4 Remake</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-black text-white">450.000₫</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase">75k+ người mua</span>
                    </div>
                </div>
            </a>
    
        </div>
    </div>
</main>

@endsection