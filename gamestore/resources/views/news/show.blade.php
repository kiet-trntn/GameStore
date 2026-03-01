@extends('layouts.user')

@section('title', $post->title)

@section('content')
<div class="h-20 md:h-28"></div>
<div class="container mx-auto px-4 md:px-10 pt-20 md:pt-40 pb-16">
    
    {{-- Header Bài Viết --}}
    <div class="max-w-4xl mx-auto mb-10 text-center">
        <div class="flex justify-center items-center gap-4 mb-6">
            <span class="bg-blue-600 px-4 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-blue-500/30">
                {{ $post->category }}
            </span>
            <span class="text-gray-400 text-sm font-bold italic"><i class="far fa-clock mr-1"></i> {{ $post->created_at->format('d/m/Y - H:i') }}</span>
        </div>
        
        <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-8">
            {{ $post->title }}
        </h1>
    </div>

    {{-- Ảnh Bìa To --}}
    <div class="max-w-5xl mx-auto h-[400px] md:h-[500px] rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 mb-12 relative">
        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-cover">
        @else
            <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200" class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-[#08080a] to-transparent"></div>
    </div>

    {{-- Nội dung chính (Dùng {!! !!} để render mã HTML) --}}
    <div class="max-w-3xl mx-auto text-gray-300 prose prose-invert prose-blue prose-lg md:prose-xl">
        <p class="text-xl md:text-2xl text-gray-400 font-medium italic mb-8 border-l-4 border-blue-500 pl-6">
            {{ $post->summary }}
        </p>

        <div class="content-html space-y-6 leading-relaxed">
            {!! $post->content !!}
        </div>
    </div>

    {{-- Phần Bài Viết Liên Quan --}}
    @if($relatedPosts->count() > 0)
    <div class="max-w-5xl mx-auto mt-24 pt-12 border-t border-white/10">
        <h3 class="text-2xl font-black text-white uppercase tracking-widest mb-8 border-l-4 border-blue-500 pl-4">Bài viết cùng chuyên mục</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $related)
                <a href="{{ route('news.show', $related->slug) }}" class="group bg-[#15151a] p-4 rounded-3xl border border-white/5 hover:border-blue-500/30 transition-all flex gap-4 items-center">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden flex-shrink-0">
                        @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                        @else
                            <img src="https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?auto=format&fit=crop&w=300" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white line-clamp-2 group-hover:text-blue-400 transition-colors">{{ $related->title }}</h4>
                        <p class="text-[10px] text-gray-500 mt-2 font-bold uppercase tracking-widest">{{ $related->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection