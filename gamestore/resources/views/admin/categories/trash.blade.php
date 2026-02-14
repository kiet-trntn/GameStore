@extends('layouts.admin')

@section('title', 'Thùng rác')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-red-600">Thùng rác Danh mục</h1>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    {{-- 1. Thêm cột STT --}}
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase w-16">STT</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase">Tên danh mục</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase">Ngày xóa</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-red-50 transition">
                    {{-- 2. Hiển thị số thứ tự chuẩn phân trang --}}
                    <td class="px-6 py-4 text-sm text-gray-700 font-bold">
                        {{ $categories->firstItem() + $loop->index }}
                    </td>

                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-center space-x-2">
                        {{-- Nút Khôi phục --}}
                        <a href="{{ route('admin.categories.restore', $category->id) }}" class="text-green-600 hover:text-green-900 font-bold">
                            <i class="fas fa-undo"></i> Khôi phục
                        </a>

                        {{-- Nút Xóa vĩnh viễn --}}
                        {{-- Lưu ý: Mình đã xóa onsubmit="..." cũ đi để tránh xung đột với onclick popup mới --}}
                        <form action="{{ route('admin.categories.force_delete', $category->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            
                            {{-- Bạn nhớ dùng hàm confirmForceDelete hoặc confirmDelete tùy ý nhé --}}
                            <button type="button" onclick="confirmDelete(event)" class="text-red-600 hover:text-red-900 font-bold transition duration-300">
                                <i class="fas fa-ban"></i> Xóa vĩnh viễn
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    {{-- 3. Tăng colspan lên 4 vì có thêm cột STT --}}
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Thùng rác trống rỗng.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Hiển thị thanh phân trang --}}
        <div class="mt-4 px-4 py-3 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection