@extends('layouts.user')

@section('title', 'Tin tức GameX - Cập nhật nhanh nhất')

@section('content')
<div class="pt-28 container mx-auto px-4 md:px-10 py-16">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight mb-4">Tạp chí <span class="text-blue-500">GameX</span></h1>
        <p class="text-gray-400 text-lg max-w-2xl mx-auto">Cập nhật những tin tức nóng hổi, review chân thực và sự kiện eSports mới nhất từ thế giới game.</p>
    </div>

    {{-- Lưới Bài Viết --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
            <a href="{{ route('news.show', $post->slug) }}" class="group bg-[#15151a] rounded-[2rem] border border-white/5 overflow-hidden hover:border-blue-500/50 transition-all shadow-xl hover:-translate-y-2 duration-300 flex flex-col h-full">
                
                {{-- Ảnh bìa --}}
                <div class="w-full h-56 overflow-hidden relative">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        {{-- Ảnh mặc định nếu chưa up --}}
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[#15151a] to-transparent"></div>
                </div>

                {{-- Nội dung --}}
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-blue-500 text-[10px] font-black uppercase tracking-widest bg-blue-500/10 px-3 py-1 rounded-md">{{ $post->category }}</span>
                        <span class="text-gray-500 text-xs font-bold italic">{{ $post->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <h2 class="text-xl font-bold text-white mb-3 line-clamp-2 leading-snug group-hover:text-blue-400 transition-colors">
                        {{ $post->title }}
                    </h2>
                    
                    <p class="text-gray-400 text-sm line-clamp-3 flex-grow mb-4">
                        {{ $post->summary }}
                    </p>
                    
                    <div class="text-blue-500 font-bold text-xs uppercase tracking-widest flex items-center gap-2 mt-auto">
                        Đọc bài viết <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-20">
                <i class="fas fa-newspaper text-6xl text-gray-700 mb-4"></i>
                <p class="text-gray-400 text-xl font-medium">Hiện tại chưa có bài viết nào.</p>
            </div>
        @endforelse
    </div>

    {{-- Phân trang --}}
    <div class="mt-12 flex justify-center">
        {{ $posts->links() }}
    </div>
</div>
@endsection