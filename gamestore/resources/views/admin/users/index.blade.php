@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 md:px-8 pt-10 pb-20">
    
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tight mb-1">Quản lý <span class="text-blue-600">Thành viên</span></h1>
            <p class="text-gray-500 text-sm font-medium">Danh sách các game thủ đang hoạt động trên hệ thống.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm flex items-center">
            <i class="fas fa-users text-gray-400 mr-2"></i>
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tổng số:</span>
            <span class="text-lg font-black text-blue-600 ml-2" id="total-users">{{ $users->total() }}</span>
        </div>
    </div>

    {{-- THANH TÌM KIẾM --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="flex gap-4 items-center">
                <div class="relative flex-grow max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                           placeholder="Nhập tên hoặc email cần tìm..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none text-sm transition-all">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/20">
                    Tìm ngay
                </button>
                
                {{-- Nếu đang tìm kiếm thì hiện nút Xóa Lọc --}}
                @if(request('keyword'))
                    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-red-500 font-bold text-sm transition-colors">
                        <i class="fas fa-times-circle mr-1"></i> Bỏ lọc
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    {{-- BẢNG THÀNH VIÊN --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">ID</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Tài khoản</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest">Ngày tham gia</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Quyền hạn</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors" id="user-row-{{ $user->id }}">
                        <td class="py-4 px-6">
                            <span class="font-bold text-gray-500">#{{ $user->id }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563EB&color=fff" class="w-10 h-10 rounded-full shadow-sm">
                                <div>
                                    <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                    @if(auth()->id() == $user->id)
                                        <span class="bg-blue-100 text-blue-700 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-widest">Bạn (Admin)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-600 font-medium">
                            {{ $user->email }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-600">{{ $user->created_at->format('d/m/Y') }}</span>
                        </td>
                        {{-- CỘT QUYỀN HẠN --}}
                        <td class="py-4 px-6 text-center">
                            @if(auth()->id() == $user->id)
                                {{-- Nếu là chính mình thì hiện cục Badge chứ không cho sửa --}}
                                <span class="bg-indigo-100 text-indigo-700 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest border border-indigo-200">
                                    Admin (Bạn)
                                </span>
                            @else
                                {{-- Nếu là người khác thì hiện Form Select để đổi quyền --}}
                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <select name="role" onchange="this.form.submit()" class="text-xs font-bold border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 hover:bg-white cursor-pointer transition-colors py-2 px-3 {{ $user->role == 'admin' ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-50 text-gray-600' }}">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                                    </select>
                                </form>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            {{-- Không hiện nút Xóa nếu là chính mình --}}
                            @if(auth()->id() != $user->id)
                                <button class="btn-delete-user text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" data-id="{{ $user->id }}" title="Xóa tài khoản">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @else
                                <span class="text-gray-300"><i class="fas fa-shield-alt"></i></span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="text-gray-400 mb-2"><i class="fas fa-ghost text-4xl"></i></div>
                            <p class="text-gray-500 font-medium">Chưa có ai đăng ký hết trơn.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // AJAX XÓA USER CỰC MƯỢT
    document.querySelectorAll('.btn-delete-user').forEach(button => {
        button.addEventListener('click', function() {
            let userId = this.getAttribute('data-id');
            let url = `/admin/users/${userId}`;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Trảm thiệt hả ba?',
                text: "Xóa tài khoản này là mất tiêu luôn dữ liệu của người ta đó nha!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Khảm! (Xóa)',
                cancelButtonText: 'Tha mạng'
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
                            // Hiệu ứng mờ dần rồi xóa cái dòng đó
                            let row = document.getElementById('user-row-' + userId);
                            row.style.transition = "all 0.5s";
                            row.style.opacity = "0";
                            row.style.transform = "translateX(20px)";
                            
                            setTimeout(() => { 
                                row.remove(); 
                                // Cập nhật lại tổng số user trên màn hình
                                let totalBadge = document.getElementById('total-users');
                                totalBadge.innerText = parseInt(totalBadge.innerText) - 1;
                            }, 500);

                            Swal.fire({
                                icon: 'success',
                                title: 'Đã tiễn khách!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Lỗi', data.message, 'error');
                        }
                    })
                    .catch(error => console.error(error));
                }
            });
        });
    });
</script>
@endsection