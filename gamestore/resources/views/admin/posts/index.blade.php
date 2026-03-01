@extends('layouts.admin')

@section('title', 'Quản lý Tin tức')

@section('content')
<div class="container mx-auto px-4 md:px-8 pt-10 pb-20">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tight mb-1">Quản lý <span class="text-indigo-600">Tin Tức</span></h1>
            <p class="text-gray-500 text-sm font-medium">Viết bài, cập nhật sự kiện và mẹo hay cho game thủ.</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2 font-bold shadow-lg shadow-indigo-100">
            <i class="fas fa-plus text-sm"></i> Thêm Bài Mới
        </a>
    </div>

    {{-- Thanh Tìm kiếm --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.posts.index') }}" method="GET">
            <div class="flex gap-4 items-center">
                <div class="relative flex-grow max-w-lg">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-search"></i></span>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm tiêu đề, thể loại bài viết..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none text-sm transition-all">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/20">
                    Tra cứu
                </button>
                @if(request('keyword'))
                    <a href="{{ route('admin.posts.index') }}" class="text-gray-500 hover:text-red-500 font-bold text-sm transition-colors"><i class="fas fa-times-circle mr-1"></i> Bỏ lọc</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Bảng Dữ Liệu --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest w-24 text-center">Ảnh Bìa</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Thông tin bài viết</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Thể loại</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Ngày đăng</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 transition-colors duration-200" id="post-row-{{ $post->id }}">
                        <td class="py-4 px-6">
                            <div class="w-20 h-14 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                @if($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center"><i class="fas fa-image text-gray-300"></i></div>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1 mb-1">{{ $post->title }}</h3>
                            <p class="text-[11px] text-gray-500 line-clamp-1">{{ $post->summary }}</p>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest">
                                {{ $post->category }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="text-sm font-bold text-gray-600">{{ $post->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-gray-400 font-medium">{{ $post->created_at->format('H:i') }}</div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="w-8 h-8 flex items-center justify-center text-blue-500 hover:bg-blue-50 rounded-xl transition-all" title="Sửa bài"><i class="fas fa-edit text-xs"></i></a>
                                <button class="btn-delete-post w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-xl transition-all" data-id="{{ $post->id }}" title="Xóa bài"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <i class="fas fa-newspaper text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500 font-medium">Chưa có bài viết nào được đăng.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">{{ $posts->links() }}</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // AJAX XÓA BÀI VIẾT (Tái sử dụng code cực mượt)
    document.querySelectorAll('.btn-delete-post').forEach(button => {
        button.addEventListener('click', function() {
            let postId = this.getAttribute('data-id');
            let url = `/admin/posts/${postId}`;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Trảm bài này?',
                text: "Bài viết sẽ bị xóa vĩnh viễn khỏi hệ thống!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Xóa luôn!',
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
                            let row = document.getElementById('post-row-' + postId);
                            row.style.transition = "all 0.5s"; row.style.opacity = "0"; row.style.transform = "translateX(20px)";
                            setTimeout(() => { row.remove(); }, 500);
                            Toast.fire({ icon: 'success', title: data.message });
                        }
                    })
                }
            });
        });
    });
</script>
@endsection