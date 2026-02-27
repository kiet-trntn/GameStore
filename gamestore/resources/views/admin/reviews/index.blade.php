@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 md:px-8 pt-10 pb-20">
    
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tight mb-1">Kiểm duyệt <span class="text-yellow-500">Đánh giá</span></h1>
            <p class="text-gray-500 text-sm font-medium">Theo dõi cảm nhận của game thủ và dọn dẹp spam.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm flex items-center">
            <i class="fas fa-star text-yellow-400 mr-2"></i>
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tổng bình luận:</span>
            <span class="text-lg font-black text-blue-600 ml-2" id="total-reviews">{{ $reviews->total() }}</span>
        </div>
    </div>

    {{-- BẢNG DANH SÁCH BÌNH LUẬN --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Khách hàng</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest min-w-[200px]">Tựa Game</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Nội dung đánh giá</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Thời gian</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50/50 transition-colors" id="review-row-{{ $review->id }}">
                        
                        {{-- Cột Khách hàng --}}
                        <td class="py-4 px-6">
                            <div class="font-bold text-gray-800">{{ $review->user->name ?? 'Tài khoản ẩn' }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">ID: {{ $review->user_id }}</div>
                        </td>

                        {{-- Cột Game --}}
                        <td class="py-4 px-6">
                            @if($review->game)
                                <a href="{{ route('game.detail', $review->game->slug) }}" target="_blank" class="flex items-center gap-3 group">
                                    <img src="{{ $review->game->image ? asset('storage/' . $review->game->image) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=100' }}" class="w-10 h-10 rounded-lg object-cover shadow-sm group-hover:scale-110 transition-transform">
                                    <span class="font-bold text-blue-600 hover:text-blue-800 text-sm truncate max-w-[150px]" title="{{ $review->game->title }}">{{ $review->game->title }}</span>
                                </a>
                            @else
                                <span class="text-sm font-bold text-gray-400 line-through">Game đã xóa</span>
                            @endif
                        </td>

                        {{-- Cột Nội dung Đánh giá --}}
                        <td class="py-4 px-6">
                            <div class="flex gap-1 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-600 line-clamp-2" title="{{ $review->comment }}">{{ $review->comment }}</p>
                        </td>

                        {{-- Cột Thời gian --}}
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-600">{{ $review->created_at->format('d/m/Y') }}</span>
                            <br>
                            <span class="text-xs text-gray-400">{{ $review->created_at->format('H:i') }}</span>
                        </td>

                        {{-- Cột Nút Xóa --}}
                        <td class="py-4 px-6 text-center">
                            <button class="btn-delete-review text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" data-id="{{ $review->id }}" title="Xóa bình luận này">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="text-gray-400 mb-2"><i class="fas fa-comment-slash text-4xl"></i></div>
                            <p class="text-gray-500 font-medium">Chưa có đánh giá nào trên hệ thống.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-delete-review').forEach(button => {
        button.addEventListener('click', function() {
            let reviewId = this.getAttribute('data-id');
            let url = `/admin/reviews/${reviewId}`;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Quét rác nha ba?',
                text: "Xóa xong là cái bình luận này bay màu vĩnh viễn đó!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Quét ngay!',
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
                            // Hiệu ứng mờ dần và xóa dòng
                            let row = document.getElementById('review-row-' + reviewId);
                            row.style.transition = "all 0.5s";
                            row.style.opacity = "0";
                            row.style.transform = "translateX(20px)";
                            
                            setTimeout(() => { 
                                row.remove(); 
                                // Trừ số lượng tổng
                                let totalBadge = document.getElementById('total-reviews');
                                totalBadge.innerText = parseInt(totalBadge.innerText) - 1;
                            }, 500);

                            Swal.fire({
                                icon: 'success',
                                title: 'Sạch sẽ!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
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