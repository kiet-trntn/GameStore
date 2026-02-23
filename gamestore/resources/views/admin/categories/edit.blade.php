@extends('layouts.admin')

@section('title', 'Chỉnh sửa Danh mục')

@section('content')
<div class="container mx-auto pb-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa <span class="text-indigo-600">Danh mục</span></h1>
            <p class="text-sm text-gray-500">Cập nhật thông tin và hình ảnh đại diện</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2 font-semibold shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i> Quay lại
        </a>
    </div>

    {{-- Form Section --}}
    <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 max-w-xl mx-auto relative overflow-hidden">
        {{-- Decoration blob --}}
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full blur-2xl z-0"></div>

        {{-- QUAN TRỌNG: Thêm enctype="multipart/form-data" --}}
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="relative z-10">
            @csrf
            @method('PUT')

            {{-- Khối Chọn Ảnh (Có Preview và Load ảnh cũ) --}}
            <div class="mb-6" x-data="{ imageUrl: '{{ $category->image ? asset('storage/' . $category->image) : '' }}' }">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Ảnh đại diện</label>
                <div class="flex items-center gap-5 p-4 border border-dashed border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-gray-50 transition-all">
                    <div class="w-20 h-20 shrink-0 rounded-xl border border-gray-200 bg-white flex items-center justify-center overflow-hidden relative shadow-sm group">
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </template>
                        <template x-if="!imageUrl">
                            <i class="fas fa-image text-gray-300 text-2xl"></i>
                        </template>
                    </div>
                    
                    <div class="flex-grow">
                        <input type="file" name="image" id="catImageEdit" class="hidden" 
                            @change="const file = $event.target.files[0]; if(file) imageUrl = URL.createObjectURL(file)">
                        <label for="catImageEdit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-xl font-semibold cursor-pointer hover:bg-indigo-100 transition-all text-sm w-full justify-center shadow-sm">
                            <i class="fas fa-camera"></i> Thay đổi ảnh
                        </label>
                        <p class="text-[10px] text-gray-400 mt-2 text-center italic">* Chọn ảnh mới sẽ tự động thay thế ảnh cũ</p>
                    </div>
                </div>
                @error('image') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tên danh mục --}}
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Tên danh mục</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" 
                       class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                @error('name')
                    <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Đường dẫn Slug --}}
            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Đường dẫn (Slug)</label>
                <div class="relative">
                    <input type="text" value="{{ $category->slug }}" 
                           class="w-full border border-gray-100 bg-gray-100/50 rounded-xl px-4 py-3 text-gray-400 cursor-not-allowed italic font-medium" readonly>
                    <i class="fas fa-lock absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-300 text-sm"></i>
                </div>
                <p class="text-[10px] text-gray-400 mt-1.5 ml-1 italic">* Slug sẽ tự động cập nhật nếu bạn đổi Tên danh mục</p>
            </div>

            {{-- Nút Cập nhật --}}
            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all duration-300 hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                Lưu Thay Đổi
            </button>
        </form>
    </div>
</div>
@endsection