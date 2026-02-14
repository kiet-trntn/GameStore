@extends('layouts.admin')

@section('title', 'Chỉnh sửa Danh mục')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Chỉnh sửa Danh mục</h1>
        <a href="{{ route('admin.categories.index') }}" class="text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-lg mx-auto">
        {{-- Form cập nhật gửi đến route update --}}
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Bắt buộc phải có PUT cho hàm update --}}

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tên danh mục</label>
                <input type="text" name="name" 
                       value="{{ old('name', $category->name) }}" {{-- Lấy tên cũ điền vào --}}
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Đường dẫn (Slug)</label>
                <input type="text" value="{{ $category->slug }}" class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-gray-400 cursor-not-allowed" readonly>
                <p class="text-[10px] text-gray-400 mt-1 italic">* Slug sẽ tự động cập nhật theo tên mới</p>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition">
                Cập nhật thay đổi
            </button>
        </form>
    </div>
</div>
@endsection