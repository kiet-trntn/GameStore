<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameX Pro | Premium Store</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    {{-- 3. Thư viện FontAwesome (Để hiện cái icon hình con Robot và nút Gửi tin nhắn) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #050507; /* Đen sâu hơn một chút */
            color: #eee;
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(29, 78, 216, 0.03) 0%, transparent 50%);

        }
        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(30px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow:
                inset 0 1px rgba(255,255,255,0.05),
                0 20px 40px rgba(0,0,0,0.5);
        }
        /* Hiệu ứng hạt bụi nhỏ (tùy chọn) */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('https://www.transparenttextures.com/patterns/stardust.png');
            opacity: 0.03;
            pointer-events: none;
            z-index: -1;
        }
        /* Gradient Mesh Background */
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: radial-gradient(at 20% 20%, rgba(37,99,235,0.15), transparent 40%),
                        radial-gradient(at 80% 30%, rgba(59,130,246,0.12), transparent 40%),
                        radial-gradient(at 50% 80%, rgba(99,102,241,0.12), transparent 40%);
            animation: meshMove 18s ease-in-out infinite alternate;
        }

        @keyframes meshMove {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(-30px) scale(1.05); }
        }

    </style>
</head>
<body class="overflow-x-hidden">
    <div class="bg-mesh"></div>
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full"></div>
        
        <div class="absolute top-[40%] right-[-5%] w-[600px] h-[600px] bg-blue-900/10 blur-[150px] rounded-full"></div>
        
        <div class="absolute bottom-[-10%] left-[20%] w-[700px] h-[700px] bg-blue-500/5 blur-[130px] rounded-full"></div>
    </div>

    <!-- Header -->
    <div class="fixed w-full z-[100] px-4 md:px-10 pt-6">
        <nav class="max-w-7xl mx-auto glass rounded-2xl py-3 px-6 flex justify-between items-center shadow-2xl shadow-black/50 border border-white/10">
            
            {{-- 1. LOGO: Bấm vào là về Trang Chủ --}}
            <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tighter flex items-center gap-2 group">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white italic shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">G</div>
                <span class="text-white hidden sm:block group-hover:text-blue-400 transition-colors">GAMEX</span>
            </a>
    
            {{-- 2. MENU LINK: Đã xóa Cộng đồng, móc link cho Tin tức --}}
            <div class="hidden lg:flex items-center gap-10 text-[13px] font-bold uppercase tracking-[0.2em] text-gray-400">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-blue-500 border-b-2 border-blue-500 pb-1' : 'hover:text-white transition-colors duration-300' }}">Trang chủ</a>
                <a href="{{ route('game') }}" class="{{ request()->routeIs('game*') ? 'text-blue-500 border-b-2 border-blue-500 pb-1' : 'hover:text-white transition-colors duration-300' }}">Khám phá</a>
                <a href="{{ route('news.index') }}" class="{{ request()->routeIs('news*') ? 'text-blue-500 border-b-2 border-blue-500 pb-1' : 'hover:text-white transition-colors duration-300' }}">Tin tức</a>
            </div>
    
            <div class="flex items-center gap-3">
                {{-- Đã xóa Form Ô tìm kiếm ở đây --}}
                
                <div class="flex items-center gap-2 ml-2">
                    
                    {{-- 3. GIỎ HÀNG: Chỉ hiện số đếm khi đã đăng nhập --}}
                    <a href="{{ route('cart.index') }}" class="p-2.5 hover:bg-white/5 rounded-xl transition-all text-gray-400 hover:text-white relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        
                        @auth
                            @php
                                // Đếm số game đang có trong giỏ của user này
                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                            @endphp
                            <span id="cart-count-badge" class="absolute top-1.5 right-1.5 w-4 h-4 bg-blue-600 text-white text-[9px] font-black flex items-center justify-center rounded-full border-2 border-[#08080a] shadow-lg shadow-blue-500/50 {{ $cartCount == 0 ? 'hidden' : '' }}">
                                {{ $cartCount }}
                            </span>
                        @else
                            {{-- Chấm nhỏ xíu báo hiệu dành cho khách chưa đăng nhập --}}
                            <span class="absolute top-2 right-2 w-2 h-2 bg-gray-500 rounded-full border-2 border-[#08080a]"></span>
                        @endauth
                    </a>

                    {{-- 4. KHU VỰC TÀI KHOẢN (GUEST vs AUTH) --}}
                    @guest
                        {{-- Nếu CHƯA đăng nhập: Hiện nút Đăng nhập --}}
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-500/20">
                            Đăng nhập
                        </a>
                    @else
                        {{-- Nếu ĐÃ đăng nhập: Hiện Avatar + Tên + Dropdown menu --}}
                        <div class="relative group">
                            <button class="flex items-center gap-2 glass px-3 py-2 rounded-xl hover:bg-white/10 transition-all border border-white/5">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563EB&color=fff" class="w-6 h-6 rounded-full">
                                <span class="text-xs font-bold text-white max-w-[80px] truncate">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                            </button>
                            
                            {{-- Dropdown (Sẽ xổ xuống khi hover chuột) --}}
                            <div class="absolute right-0 mt-2 w-48 glass rounded-2xl overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 border border-white/10 shadow-2xl">
                                
                                {{-- CHỈ HIỆN NÚT NÀY NẾU TÀI KHOẢN LÀ ADMIN --}}
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-xs font-black text-yellow-400 hover:text-yellow-300 hover:bg-white/10 transition-colors bg-white/5">
                                        <i class="fas fa-user-shield w-4 text-center mr-1"></i> Trang Quản Trị
                                    </a>
                                    <div class="border-t border-white/10 my-1"></div>
                                @endif

                                <a href="{{ route('profile.index') }}" class="block px-4 py-3 text-xs font-bold text-gray-300 hover:text-white hover:bg-white/10 transition-colors">
                                    <i class="fas fa-user-circle w-4 text-center mr-1"></i> Hồ sơ của tôi
                                </a>
                                <a href="{{ route('user.library') }}" class="block px-4 py-3 text-xs font-bold text-gray-300 hover:text-white hover:bg-white/10 transition-colors">
                                    <i class="fas fa-shopping-bag w-4 text-center mr-1"></i> Thư viện Game
                                </a>
                                
                                <div class="border-t border-white/10 my-1"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-xs font-bold text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors">
                                        <i class="fas fa-sign-out-alt w-4 text-center mr-1"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest

                </div>
            </div>
        </nav>
    </div>

    <main>
        @yield('content')
    </main>
    
    <!-- footer --> 
    <footer class="container mx-auto px-4 md:px-10 pb-10 mt-20">
        <div class="glass rounded-[2.5rem] p-10 md:p-16 border border-white/10 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600/10 blur-[100px] rounded-full"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-600/10 blur-[100px] rounded-full"></div>
    
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 relative z-10">
                <div class="space-y-6">
                    <div class="text-3xl font-bold tracking-tighter flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white italic shadow-lg shadow-blue-500/30">G</div>
                        <span class="text-white">GAMEX</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Nền tảng phân phối game bản quyền hàng đầu, mang đến trải nghiệm giải trí đỉnh cao cho game thủ Việt.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:bg-blue-600 hover:scale-110 transition-all shadow-lg">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:bg-red-600 hover:scale-110 transition-all shadow-lg">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:bg-indigo-600 hover:scale-110 transition-all shadow-lg">
                            <i class="bi bi-discord"></i>
                        </a>
                    </div>
                </div>
    
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Khám phá</h4>
                    <ul class="space-y-4 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Tất cả trò chơi</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Game Indie mới</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Phần mềm & Công cụ</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Thẻ quà tặng</a></li>
                    </ul>
                </div>
    
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Hỗ trợ</h4>
                    <ul class="space-y-4 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Chính sách hoàn tiền</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Trạng thái máy chủ</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Liên hệ bản quyền</a></li>
                    </ul>
                </div>
    
                <div class="space-y-6">
                    <h4 class="text-white font-bold mb-2 uppercase tracking-widest text-xs">Đăng ký nhận tin</h4>
                    <p class="text-gray-400 text-xs italic">Đừng bỏ lỡ các đợt Flash Sale cuối tuần!</p>
                    <div class="relative group">
                        <input type="email" placeholder="Email của bạn..." 
                               class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-sm focus:outline-none focus:border-blue-500/50 transition-all outline-none">
                        <button class="absolute right-2 top-2 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-xl transition-all shadow-lg shadow-blue-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
    
            <div class="mt-16 pt-8 border-t border-white/5 flex flex-col md:row justify-between items-center gap-4 text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                <p>&copy; 2026 GAMEX STORE. MADE BY GAMERS FOR GAMERS.</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>


    {{-- BONG BÓNG CHATBOT AI --}}
    <div x-data="{ openChat: false, message: '', chatHistory: [{sender: 'bot', text: 'Chào ba! Tui là AI tư vấn của GameX. Ba xài máy cấu hình sao, đang thèm chơi thể loại gì cứ nói tui tư vấn cho nha! 🎮'}] }" 
         class="fixed bottom-6 right-6 z-[100] font-sans">
        
        {{-- Nút gọi hồn Chatbot --}}
        <button @click="openChat = !openChat" class="w-14 h-14 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-110 transition-transform relative">
            <i class="fas" :class="openChat ? 'fa-times text-xl' : 'fa-robot text-2xl'"></i>
            <span x-show="!openChat" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping"></span>
            <span x-show="!openChat" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-[#08080a]"></span>
        </button>

        {{-- Cửa sổ Chat --}}
        <div x-show="openChat" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-10"
             class="absolute bottom-20 right-0 w-80 md:w-96 bg-white rounded-3xl overflow-hidden shadow-2xl border border-gray-100 flex flex-col" style="height: 500px; display: none;">
            
            {{-- Header Chat --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 text-white flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-xl backdrop-blur-sm">🤖</div>
                <div>
                    <h3 class="font-black text-sm uppercase tracking-widest">GameX AI Advisor</h3>
                    <p class="text-[10px] text-blue-200 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-400"></span> Trực tuyến</p>
                </div>
            </div>

            {{-- Khung hiển thị tin nhắn --}}
            <div id="chat-box" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4">
                <template x-for="(msg, index) in chatHistory" :key="index">
                    <div class="flex" :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[80%] rounded-2xl px-4 py-2 text-sm shadow-sm"
                             :class="msg.sender === 'user' ? 'bg-blue-600 text-white rounded-tr-sm' : 'bg-white border border-gray-100 text-gray-700 rounded-tl-sm'"
                             x-html="msg.text">
                        </div>
                    </div>
                </template>
            </div>

            {{-- Chỗ nhập câu hỏi --}}
            <div class="p-4 bg-white border-t border-gray-100">
                <form @submit.prevent="
                    if(message.trim() === '') return;
                    let userText = message;
                    chatHistory.push({sender: 'user', text: userText});
                    message = '';
                    
                    // Thêm dòng Đang gõ chữ...
                    let botIndex = chatHistory.push({sender: 'bot', text: '<i class=\'fas fa-ellipsis-h animate-pulse\'></i>'}) - 1;
                    
                    setTimeout(() => { document.getElementById('chat-box').scrollTop = 9999; }, 50);

                    fetch('{{ route('chatbot.ask') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                        },
                        body: JSON.stringify({ message: userText })
                    })
                    .then(res => res.json())
                    .then(data => {
                        chatHistory[botIndex].text = data.reply;
                        setTimeout(() => { document.getElementById('chat-box').scrollTop = 9999; }, 50);
                    });
                " class="flex items-center gap-2">
                    <input type="text" x-model="message" placeholder="Hỏi cấu hình, tư vấn game..." class="flex-1 bg-gray-100 border-none rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all text-gray-800">
                    <button type="submit" class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors shadow-md">
                        <i class="fas fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>


        
        // Khởi tạo Swiper an toàn
        if (document.querySelector('.mainHeroSwiper')) {
            const swiper = new Swiper('.mainHeroSwiper', {
            loop: true,
            speed: 1000, // Tăng tốc độ chuyển cảnh cho mượt
            autoplay: { 
                delay: 6000,
                disableOnInteraction: false 
            },
            pagination: { 
                el: '.swiper-pagination', 
                clickable: true,
                dynamicBullets: true // Bullet sẽ to nhỏ theo slide hiện tại
            },
            navigation: { 
                nextEl: '.swiper-button-next', 
                prevEl: '.swiper-button-prev' 
            },
            effect: 'fade',
            fadeEffect: { crossFade: true }
        });
        }

        if (document.querySelector('.hotGamesSwiper')) {
            // Khởi tạo Swiper cho Game Hot
        const hotSwiper = new Swiper('.hotGamesSwiper', {
            slidesPerView: 1.5, // Hiển thị 1 phần của slide tiếp theo để người dùng biết là lướt được
            spaceBetween: 20,
            centeredSlides: false,
            grabCursor: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: '.hot-next',
                prevEl: '.hot-prev',
            },
            breakpoints: {
                // Mobile lớn
                480: {
                    slidesPerView: 2.2,
                },
                // Tablet
                768: {
                    slidesPerView: 3.5,
                },
                // Desktop
                1200: {
                    slidesPerView: 5,
                    spaceBetween: 25, // Tăng khoảng cách cho thoáng
                }
            }
        });
        }
    </script>
    {{-- Đặt SweetAlert ở Layout luôn vì trang nào cũng xài popup --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- SCRIPT DÙNG CHUNG CHO TOÀN BỘ WEBSITE --}}
    <script>
        // SỬ DỤNG EVENT DELEGATION: Lắng nghe click trên toàn bộ body
        document.body.addEventListener('click', function(e) {
            
            // Tìm xem chỗ vừa click có phải là nút btn-add-cart (hoặc icon bên trong nó) không
            let button = e.target.closest('.btn-add-cart');
            
            // Nếu không phải nút thêm giỏ hàng thì bỏ qua, không làm gì hết
            if (!button) return; 

            // NẾU ĐÚNG LÀ NÚT THÊM GIỎ HÀNG THÌ CHẠY XUỐNG ĐÂY
            e.preventDefault(); 
            
            let gameId = button.getAttribute('data-id');
            let url = `/gio-hang/them/${gameId}`;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Đổi giao diện nút thành loading
            let originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin text-xl"></i>';
            button.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                if (!response.ok && response.status !== 401) {
                    throw new Error("Lỗi Server: Check Console nha!");
                }
                if (response.status === 401) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ối chà!',
                        text: 'Ba phải đăng nhập mới mua game được nha!',
                        confirmButtonText: 'Đăng nhập ngay',
                        confirmButtonColor: '#2563EB'
                    }).then((result) => {
                        if (result.isConfirmed) { window.location.href = '/login'; }
                    });
                    throw new Error('Chưa đăng nhập');
                }
                return response.json();
            })
            .then(data => {
                // Trả lại hình dáng ban đầu
                button.innerHTML = originalContent;
                button.disabled = false;

                if (data.status === 'success') {
                    // Cập nhật số đỏ trên Header
                    let badge = document.getElementById('cart-count-badge');
                    if (badge) {
                        badge.innerText = data.cart_count;
                        badge.classList.remove('hidden'); 
                    }
                    Swal.fire({ icon: 'success', title: 'Đã bỏ vô giỏ!', text: data.message, showConfirmButton: false, timer: 1500 });
                } else if (data.status === 'warning') {
                    Swal.fire({ icon: 'warning', title: 'Khoan đã!', text: data.message, confirmButtonColor: '#F59E0B' });
                }
            })
            .catch(error => {
                button.innerHTML = originalContent;
                button.disabled = false;
                if(error.message !== 'Chưa đăng nhập') {
                    console.error(error);
                    Swal.fire('Lỗi Code Rồi!', 'Bấm F12 qua tab Console xem nó báo gì nha!', 'error');
                }
            });
        });
    </script>

    {{-- Dành cho các trang muốn nhét thêm code JS riêng --}}
    @yield('scripts')

    
</body>
</html>