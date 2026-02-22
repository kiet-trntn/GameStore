@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="container mx-auto pb-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý <span class="text-indigo-600">DANH MỤC</span></h1>
            <p class="text-sm text-gray-500">Phân loại các trò chơi theo thể loại và trạng thái</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.trash') }}" class="group bg-white border border-red-200 text-red-500 px-4 py-2 rounded-xl hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center gap-2 font-semibold shadow-sm">
                <i class="fas fa-trash-alt text-sm group-hover:rotate-12"></i>
                <span>Thùng rác</span>
            </a>
            <button @click="openModal = true" class="bg-indigo-600 text-white px-5 py-2 rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2 font-bold shadow-lg shadow-indigo-100">
                <i class="fas fa-plus text-sm"></i>
                <span>Thêm mới</span>
            </button>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.categories.index') }}" method="GET">
            <div class="flex flex-wrap lg:flex-nowrap gap-4">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                           placeholder="Tìm tên danh mục..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none text-sm transition-all">
                </div>

                <div class="w-full md:w-64">
                    <select name="status" onchange="this.form.submit()" 
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none text-sm transition-all cursor-pointer">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang hiện</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đang ẩn</option>
                    </select>
                </div>

                {{-- <div class="flex gap-2">
                    <button type="submit" class="bg-gray-800 text-white px-6 py-2.5 rounded-xl hover:bg-gray-900 transition-all font-bold text-sm">
                        Lọc
                    </button>
                    
                    @if(request('keyword') || request('status'))
                        <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 text-gray-500 px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-all text-sm flex items-center justify-center font-medium">
                            Xóa lọc
                        </a>
                    @endif
                </div> --}}
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase w-16 text-center tracking-wider">STT</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Thông tin Danh mục</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Đường dẫn (Slug)</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center tracking-wider">Trạng thái</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50/50 transition-all duration-200">
                    <td class="px-6 py-4 text-sm text-gray-400 text-center font-medium">
                        {{ $categories->firstItem() + $loop->index }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-800">{{ $category->name }}</div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-tighter">ID: #{{ $category->id }}</div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                            {{ $category->slug }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center" x-data="{ active: {{ $category->is_active ? 'true' : 'false' }} }">
                            <button 
                                @click="fetch('{{ route('admin.categories.toggle', $category->id) }}', {
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

                    <td class="px-6 py-4 text-sm text-center">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="w-8 h-8 flex items-center justify-center text-blue-500 hover:bg-blue-50 rounded-xl transition-all" title="Sửa">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block">
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
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-folder-open text-gray-100 text-6xl mb-4"></i>
                            <p class="text-gray-400 font-medium">Chưa có danh mục nào được tạo...</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Add Category --}}
<div x-show="openModal" class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="openModal = false"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-[2rem] max-w-md w-full p-8 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Thêm <span class="text-indigo-600">Danh mục</span></h3>
                <button @click="openModal = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Tên danh mục</label>
                    <input type="text" name="name" placeholder="Ví dụ: Hành động, Phiêu lưu..." 
                           class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Đường dẫn (Tự động)</label>
                    <input type="text" name="slug" placeholder="slug-tu-dong" 
                           class="w-full border border-gray-100 bg-gray-100/50 rounded-xl px-4 py-3 text-gray-400 cursor-not-allowed italic" readonly>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3.5 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                        Xác nhận thêm
                    </button>
                    <button type="button" @click="openModal = false" class="flex-1 bg-gray-100 text-gray-600 font-bold py-3.5 rounded-xl hover:bg-gray-200 transition-all">
                        Hủy bỏ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection