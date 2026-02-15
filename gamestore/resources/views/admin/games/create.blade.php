@extends('layouts.admin')

@section('title', 'Thêm Game Mới')

@section('content')
<div class="max-w-5xl mx-auto">
    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-lg font-bold mb-4 text-gray-700">Thông tin cơ bản</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tên Game</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 outline-none @error('title') border-red-500 @enderror">
                            @error('title') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Giá bán (VNĐ)</label>
                            <input type="number" name="price" value="{{ old('price') }}" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 outline-none @error('price') border-red-500 @enderror">
                            @error('price') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                            <select name="category_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="" selected disabled>-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mt-6">
                    <h2 class="text-lg font-bold mb-4 text-gray-700">Thông tin bổ sung</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nhà phát triển</label>
                            <input type="text" name="developer" class="w-full border border-gray-300 rounded-lg p-2 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Giá khuyến mãi (nếu có)</label>
                            <input type="number" name="sale_price" class="w-full border border-gray-300 rounded-lg p-2 outline-none border-green-200 bg-green-50">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cấu hình yêu cầu</label>
                            <textarea name="requirements" rows="3" class="w-full border border-gray-300 rounded-lg p-2 outline-none" placeholder="CPU: i5, RAM: 8GB, VGA: GTX 1050..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200" x-data="{ files: [] }">
                    <h2 class="text-lg font-bold mb-4 text-gray-700">Bộ sưu tập ảnh (Screenshots)</h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center relative hover:bg-gray-50 transition">
                        <input type="file" name="screenshots[]" multiple @change="files = Array.from($event.target.files)" class="absolute inset-0 opacity-0 cursor-pointer">
                        <p class="text-gray-500 text-sm">Bấm để chọn nhiều ảnh chi tiết</p>
                    </div>
                    <div class="grid grid-cols-4 gap-2 mt-4" x-show="files.length > 0">
                        <template x-for="file in files">
                            <img :src="URL.createObjectURL(file)" class="w-full h-20 object-cover rounded-md border">
                        </template>
                    </div>
                </div>
            </div>


            <div class="space-y-4">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200" x-data="{ imageUrl: null }">
                    <h2 class="text-lg font-bold mb-4 text-gray-700">Hình ảnh & Media</h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh bìa đại diện</label>
                        
                        <div class="mb-3 w-full h-40 border-2 border-dashed border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center relative">
                            <template x-if="imageUrl">
                                <img :src="imageUrl" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imageUrl">
                                <div class="text-center">
                                    <i class="fas fa-image text-gray-300 text-3xl mb-1"></i>
                                    <p class="text-[10px] text-gray-400">Chưa có ảnh</p>
                                </div>
                            </template>
                            <button type="button" x-show="imageUrl" @click="imageUrl = null; $refs.imageInput.value = ''" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600 shadow-sm">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
            
                        <input type="file" name="image" x-ref="imageInput"
                            @change="const file = $event.target.files[0]; if (file) imageUrl = URL.createObjectURL(file)"
                            class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 w-full">
                        
                        @error('image') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                    </div>
            
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link Trailer (Youtube)</label>
                        <input type="text" name="trailer_link" value="{{ old('trailer_link') }}" placeholder="https://..." class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('trailer_link') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                </div>
            
                <div class="flex flex-col gap-2">
                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Lưu Game Mới
                    </button>
                    <a href="{{ route('admin.games.index') }}" class="w-full bg-gray-100 text-gray-600 text-center font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                        Hủy bỏ
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection