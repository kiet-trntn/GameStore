@extends('layouts.admin')

@section('title', 'Thêm Bài Viết Mới')

@section('content')
<div class="container mx-auto px-4 md:px-8 pt-10 pb-20">
    <div class="mb-8 flex items-center gap-3">
        <a href="{{ route('admin.posts.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-indigo-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight mb-1">Thêm <span class="text-indigo-600">Bài Viết Mới</span></h1>
        </div>
    </div>

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- CỘT TRÁI: Nội dung chữ --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tiêu đề bài viết <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="Nhập tiêu đề giật tít vào đây..." class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-gray-800">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Đoạn tóm tắt (Mô tả ngắn)</label>
                        <textarea name="summary" rows="3" placeholder="Viết 1-2 câu tóm tắt hiển thị ngoài trang chủ..." class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nội dung chi tiết</label>
                        {{-- Mốt ba muốn chèn CKEditor vô đây thì chèn nha, tạm thời xài textarea đỡ --}}
                        <textarea name="content" rows="15" placeholder="Nội dung bài viết..." class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm leading-relaxed"></textarea>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: Hình ảnh & Cài đặt --}}
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-sm font-black text-gray-800 uppercase mb-4 border-b pb-3">Cài đặt bài viết</h3>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Thể loại tin tức</label>
                        <select name="category" class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-gray-700 cursor-pointer">
                            <option value="Sự kiện">Sự kiện (Events)</option>
                            <option value="Cập nhật">Cập nhật (Updates)</option>
                            <option value="Phần cứng">Phần cứng (Hardware)</option>
                            <option value="Cộng đồng">Cộng đồng (Community)</option>
                        </select>
                    </div>

                    <div x-data="{ imageUrl: null }">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Ảnh bìa (Thumbnail)</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 p-4 text-center hover:bg-gray-100 transition-colors relative">
                            
                            {{-- Preview Ảnh --}}
                            <template x-if="imageUrl">
                                <img :src="imageUrl" class="w-full h-40 object-cover rounded-lg shadow-sm mb-3">
                            </template>
                            <template x-if="!imageUrl">
                                <div class="w-full h-40 flex flex-col items-center justify-center text-gray-400 mb-3 bg-white rounded-lg border border-gray-100">
                                    <i class="fas fa-cloud-upload-alt text-3xl mb-2"></i>
                                    <span class="text-xs font-medium">Chưa có ảnh</span>
                                </div>
                            </template>

                            <input type="file" name="image" id="postImage" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*"
                                @change="const file = $event.target.files[0]; if(file) imageUrl = URL.createObjectURL(file)">
                            
                            <label for="postImage" class="inline-block bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-xs font-bold pointer-events-none shadow-sm">
                                Chọn ảnh tải lên
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Nút Đăng Bài --}}
                <button type="submit" class="w-full bg-indigo-600 text-white font-black uppercase tracking-widest py-4 rounded-xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/30 transition-all flex justify-center items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Xuất bản bài viết
                </button>
            </div>
        </div>
    </form>
</div>
@endsection