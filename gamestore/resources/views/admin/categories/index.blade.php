@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="container mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-700">Danh mục Game</h1>
        
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="relative">
                <input type="text" 
                       name="keyword" 
                       value="{{ request('keyword') }}" 
                       placeholder="Tìm kiếm danh mục..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition w-64">
                
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
    
                </form>
    
            <button @click="openModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                <i class="fas fa-plus mr-2"></i> Thêm mới
            </button>
        </div>
    </div>

    {{-- Hiển thị thông báo thành công --}}
    {{-- @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif --}}


    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider w-16">STT</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Tên danh mục</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{-- logic Backend: Vòng lặp lấy dữ liệu --}}
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-700 font-bold">
                        {{ $categories->firstItem() + $loop->index }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 italic">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-sm text-center space-x-2">
                        {{-- Nút Sửa: Truyền ID vào route --}}
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-900 transition">
                            <i class="fas fa-edit"></i> Sửa
                        </a>

                        {{-- Nút Xóa: Phải dùng Form để bảo mật --}}
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            
                            <button type="button" onclick="confirmDelete(event)" class="text-red-600 hover:text-red-900 transition">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Chưa có danh mục nào được tạo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
            {{ $categories->links() }}
        </div>
    </div>



    <div x-show="openModal" 
         class="fixed inset-0 z-[110] overflow-y-auto" 
         x-cloak>
        <div class="fixed inset-0 bg-black opacity-50"></div>
        
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-[2rem] max-w-md w-full p-8 shadow-2xl" @click.away="openModal = false">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Thông tin danh mục</h3>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Logic Backend: Form Action --}}
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tên danh mục</label>
                        <input type="text" name="name" placeholder="Ví dụ: Thể thao" 
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Đường dẫn (Slug)</label>
                        <input type="text" name="slug" placeholder="the-thao" 
                               class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-gray-500 cursor-not-allowed" readonly>
                        <p class="text-[10px] text-gray-400 mt-1 italic">* Tự động tạo nếu để trống</p>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition">Lưu lại</button>
                        <button type="button" @click="openModal = false" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-200 transition">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection