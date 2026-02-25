@extends('layouts.admin')

@section('title', 'Thêm Game Mới')

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Thêm Game <span class="text-indigo-600">Mới</span></h1>
        <a href="{{ route('admin.games.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-50">
                        <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                        <h2 class="font-bold text-gray-700">Thông tin cơ bản</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tên Game</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Nhập tên trò chơi..." 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition @error('title') border-red-500 @enderror">
                            @error('title') <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Giá bán (VNĐ)</label>
                            <input type="number" name="price" value="{{ old('price') }}" placeholder="0"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition @error('price') border-red-500 @enderror">
                            @error('price') <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Danh mục</label>
                            <select name="category_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                <option value="" selected disabled>-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Mô tả game</label>
                        <textarea name="description" rows="5" placeholder="Mô tả nội dung game..."
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-50">
                        <i class="fas fa-tools text-orange-500 mr-2"></i>
                        <h2 class="font-bold text-gray-700">Thông số & Bán hàng</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nhà phát triển</label>
                            <input type="text" name="developer" value="{{ old('developer') }}" placeholder="VD: Rockstar Games"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ngày ra mắt (Dự kiến)</label>
                            <input type="date" name="release_date" value="{{ old('release_date', $game->release_date ?? '') }}" 
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1 text-green-600">Giá khuyến mãi (nếu có)</label>
                            <input type="number" name="sale_price" value="{{ old('sale_price') }}" 
                                class="w-full bg-green-50 border border-green-100 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-green-500 outline-none transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cấu hình yêu cầu</label>
                            <textarea name="requirements" rows="3" placeholder="CPU, RAM, Card đồ họa..."
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">{{ old('requirements') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{ files: [] }">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-gray-700"><i class="fas fa-images text-purple-500 mr-2"></i>Bộ sưu tập ảnh</h2>
                    </div>
                    
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center relative hover:border-indigo-300 hover:bg-indigo-50/30 transition group">
                        <input type="file" name="screenshots[]" multiple @change="files = Array.from($event.target.files)" class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="text-gray-400 group-hover:text-indigo-500 transition">
                            <i class="fas fa-cloud-upload-alt text-3xl mb-2"></i>
                            <p class="text-sm font-medium">Kéo thả hoặc Click để chọn ảnh chi tiết</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-3 mt-4" x-show="files.length > 0">
                        <template x-for="file in files">
                            <div class="relative">
                                <img :src="URL.createObjectURL(file)" class="w-full h-24 object-cover rounded-xl border border-gray-100 shadow-sm">
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{ imageUrl: null }">
                    <h2 class="font-bold text-gray-700 mb-4 text-sm uppercase tracking-wider">Ảnh bìa đại diện</h2>
                    
                    <div class="relative w-full aspect-video bg-gray-100 rounded-2xl overflow-hidden mb-4 border border-gray-100 shadow-inner group">
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="w-full h-full object-cover transition group-hover:scale-105 duration-500">
                        </template>
                        <template x-if="!imageUrl">
                            <div class="flex flex-col items-center justify-center h-full text-gray-300">
                                <i class="fas fa-image text-4xl mb-2"></i>
                                <p class="text-xs">Chưa chọn ảnh</p>
                            </div>
                        </template>
                    </div>

                    <div class="relative">
                        <input type="file" name="image" id="imageInput" class="hidden" 
                            @change="const file = $event.target.files[0]; if (file) imageUrl = URL.createObjectURL(file)">
                        <label for="imageInput" class="w-full flex items-center justify-center px-4 py-3 bg-indigo-50 text-indigo-700 rounded-xl font-semibold text-sm cursor-pointer hover:bg-indigo-100 transition border border-indigo-100">
                            <i class="fas fa-camera mr-2"></i> Chọn ảnh bìa
                        </label>
                    </div>
                    @error('image') <p class="text-red-500 text-xs mt-2 italic">{{ $message }}</p> @enderror
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="font-bold text-gray-700 mb-4 text-sm uppercase tracking-wider">Trailer URL</h2>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-red-500">
                            <i class="fab fa-youtube"></i>
                        </span>
                        <input type="text" name="trailer_link" value="{{ old('trailer_link') }}" placeholder="https://youtube.com/..."
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                    </div>
                    @error('trailer_link') <p class="text-red-500 text-xs mt-2 italic">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 transition duration-200 uppercase tracking-widest text-sm">
                        Lưu Game Mới
                    </button>
                    <a href="{{ route('admin.games.index') }}" class="w-full flex items-center justify-center bg-white text-gray-500 font-semibold py-3 rounded-2xl border border-gray-100 hover:bg-gray-50 hover:text-red-500 transition">
                        Hủy bỏ & Quay lại
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection 