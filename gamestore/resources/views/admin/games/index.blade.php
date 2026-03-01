@extends('layouts.admin')

@section('title', 'Danh sách Game')

@section('content')
<div class="container mx-auto pb-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý <span class="text-indigo-600">GAME</span></h1>
            <p class="text-sm text-gray-500">Xem, tìm kiếm và quản lý danh sách trò chơi của bạn</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.games.trash') }}" class="group bg-white border border-red-200 text-red-500 px-4 py-2 rounded-xl hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center gap-2 font-semibold shadow-sm">
                <i class="fas fa-trash-alt text-sm group-hover:rotate-12"></i>
                <span>Thùng rác</span>
            </a>
            <a href="{{ route('admin.games.create') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2 font-bold shadow-lg shadow-indigo-100">
                <i class="fas fa-plus text-sm"></i>
                <span>Thêm game</span>
            </a>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.games.index') }}" method="GET">
            <div class="flex flex-wrap lg:flex-nowrap gap-4">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Tìm tên game, nhà phát triển..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none text-sm transition-all">
                </div>

                <div class="w-full md:w-64">
                    <select name="category_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none text-sm transition-all">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-800 text-white px-6 py-2.5 rounded-xl hover:bg-gray-900 transition-all font-bold text-sm">
                        Lọc
                    </button>
                    
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('admin.games.index') }}" class="bg-gray-100 text-gray-500 px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-all text-sm flex items-center justify-center font-medium">
                            Xóa lọc
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase w-16 text-center tracking-wider">STT</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase w-24 text-center tracking-wider">Ảnh</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Thông tin Game</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center tracking-wider">Trạng thái</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center tracking-wider">Hot</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center tracking-wider">Lượt xem</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-right tracking-wider">Giá bán</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($games as $game)
                <tr class="hover:bg-gray-50/50 transition-all duration-200">
                    <td class="px-6 py-4 text-sm text-gray-400 text-center font-medium">
                        {{ ($games->currentPage() - 1) * $games->perPage() + $loop->iteration }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center">
                            <div class="w-16 h-10 rounded-lg overflow-hidden border border-gray-100 shadow-sm bg-gray-50">
                                @php
                                // Kiểm tra: Nếu link ảnh bắt đầu bằng chữ 'http' thì xài luôn.
                                // Nếu không có chữ 'http' (ảnh tự up) thì mới gắn hàm asset('storage/...') vô.
                                // Nếu game chưa có ảnh thì lấy đại 1 cái ảnh mặc định.
                                if ($game->image) {
                                    $imageUrl = str_starts_with($game->image, 'http') ? $game->image : asset('storage/' . $game->image);
                                } else {
                                    $imageUrl = 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800'; // Ảnh dự phòng
                                }
                                @endphp
                                @if($game->image)
                                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-[10px] text-gray-300">No Pic</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="text-sm font-bold text-gray-800">{{ $game->title }}</div>
                            
                            {{-- Kiểm tra nếu có ngày ra mắt và ngày đó LỚN HƠN hiện tại -> Hiện badge Sắp ra mắt --}}
                            @if($game->release_date && $game->release_date > now())
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 border border-blue-200 text-[9px] font-black uppercase tracking-widest rounded-md whitespace-nowrap">
                                    Sắp ra mắt
                                </span>
                            @endif
                        </div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-tighter mt-1">
                            {{ $game->developer ?? 'N/A' }} 
                            {{-- In luôn ngày ra mắt cho Admin dễ theo dõi --}}
                            @if($game->release_date)
                                | <i class="far fa-calendar-alt"></i> {{ $game->release_date->format('d/m/Y') }}
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase">
                            {{ $game->category->name ?? 'N/A' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center" x-data="{ active: {{ $game->is_active ? 'true' : 'false' }} }">
                            <button 
                                @click="fetch('{{ route('admin.games.toggle', $game->id) }}', {
                                    method: 'PATCH',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => { 
                                    if(data.status === 'success') {
                                        active = data.new_state;
                                        const Toast = Swal.mixin({
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 2000,
                                            timerProgressBar: true
                                        });
                                        Toast.fire({ icon: 'success', title: data.message });
                                    } else {
                                        Swal.fire('Lỗi!', data.message, 'error');
                                    }
                                })"
                                :class="active ? 'bg-green-500' : 'bg-gray-300'"
                                class="relative inline-flex h-5 w-10 items-center rounded-full transition-colors duration-200 focus:outline-none"
                            >
                                <span :class="active ? 'translate-x-5' : 'translate-x-1'"
                                      class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition duration-200 shadow-sm"></span>
                            </button>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center" x-data="{ featured: {{ $game->is_featured ? 'true' : 'false' }} }">
                            <button 
                                @click="fetch('{{ route('admin.games.toggle_featured', $game->id) }}', {
                                    method: 'PATCH',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => { 
                                    if(data.status === 'success') {
                                        featured = data.new_state;
                                        const Toast = Swal.mixin({
                                            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true
                                        });
                                        Toast.fire({ icon: 'success', title: data.message });
                                    } else {
                                        Swal.fire('Lỗi!', data.message, 'error');
                                    }
                                })"
                                :class="featured ? 'text-yellow-400 hover:text-yellow-500' : 'text-gray-300 hover:text-gray-400'"
                                class="transition-colors duration-300 focus:outline-none text-xl"
                                title="Đánh dấu Game Hot"
                            >
                                <i class="fas fa-star" :class="featured ? 'drop-shadow-[0_0_8px_rgba(250,204,21,0.6)]' : ''"></i>
                            </button>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 shadow-sm" title="Tổng số lượt xem">
                            <i class="fas fa-eye text-blue-500 text-xs"></i>
                            <span class="text-sm font-bold text-gray-700">{{ number_format($game->views) }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-right font-black text-gray-700">
                        {{ number_format($game->price, 0, ',', '.') }}<span class="text-[10px] ml-0.5">đ</span>
                    </td>

                    <td class="px-6 py-4 text-sm text-center">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('admin.games.edit', $game->id) }}" class="w-8 h-8 flex items-center justify-center text-blue-500 hover:bg-blue-50 rounded-xl transition-all" title="Sửa">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(event)" class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Xóa">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-box-open text-gray-100 text-6xl mb-4"></i>
                            <p class="text-gray-400 font-medium">Kho game đang trống...</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($games->hasPages())
        <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
            {{ $games->links() }}
        </div>
        @endif
    </div>
</div>
@endsection