@extends('layouts.app')

@section('title', 'Usulan Barang Baru | SIMBARA')

@section('content')
@include('item_requests.create')
@include('item_requests.edit')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Usulan Barang Baru</h2>
        <p class="font-light text-gray-400 text-sm">Catat dan kirim usulan barang yang dibutuhkan unit anda</p>
    </div>

    @include('components.header')
</div>
<div class="p-3 sm:p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
        <div class="relative w-full sm:max-w-xs">
            <input type="text" placeholder="Cari Item"
                value="{{ request('search') }}"
                oninput="this.form.submit()"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>

        <button
            class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-700 text-white
                   rounded-lg text-sm font-medium shadow-sm transition"
            data-modal-target="create-usulan">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Usulan</span>
        </button>
    </div>

    <div class="mb-4 rounded-xl shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            @if(auth()->user()->role === 'ADMIN')
            <div class="relative min-w-[220px]">
                <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2
                          text-gray-400 text-sm"></i>
                <select name="user_id" onchange="this.form.submit()"
                    class="w-full border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm text-sm
                           focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="relative min-w-[150px]">
                <i class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <select name="year" onchange="this.form.submit()"
                    class="border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm
                        focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition w-full text-sm">
                    <option value="">Semua Tahun</option>

                    @foreach($years as $y)
                        <option value="{{ $y->year }}" {{ request('year') == $y->year ? 'selected' : '' }}>
                            {{ $y->year }}
                        </option>
                    @endforeach
                </select>
            </div>


            @if(request()->anyFilled(['user_id', 'year']))
                <a href="{{ route('item-requests.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg
                           text-sm font-medium border border-gray-300 hover:bg-gray-100 shadow-sm transition">
                    <i class="fa-solid fa-rotate-left text-gray-500"></i>
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tampilan tabel di dekstop -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full border-collapse text-sm text-left text-gray-600">
            <thead class="text-gray-700 bg-gray-100 font-semibold">
                <tr>
                    <th class="px-4 py-3">Nama Barang</th>
                    <th class="px-4 py-3">Spesifikasi</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Kuantitas</th>
                    <th class="px-4 py-3">Alasan</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Tanggal Usulan</th>
                    <th class="px-4 py-3">Tanggal Usulan Dihapus</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($itemRequests as $data)
                <tr class="hover:bg-gray-50 transition {{ $data->trashed() ?  'text-red-600' : '' }}" data-id="{{ $data->id }}">
                    <td class="px-4 py-3 font-medium">{{ $data->name}}</td>
                    <td class="px-4 py-3">{{ $data->detail}}</td>
                    <td class="px-4 py-3" data-type-id="{{ $data->type->id }}">{{ $data->type->name}}</td>
                    <td class="px-4 py-3">{{ $data->qty}}</td>
                    <td class="px-4 py-3">{{ $data->reason}}</td>
                    <td class="px-4 py-3">{{ $data->user->name}}</td>
                    <td class="px-4 py-3">{{$data->created_at}}</td>
                    <td class="px-4 py-3">{{$data?->deleted_at ?? '-'}}</td>
                    <td class="px-4 py-3 text-center">
                        @if (!$data->deleted_at)
                            <div class="flex justify-center space-x-2">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg" data-modal-target="edit-usulan">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg" data-modal-target="delete-modal">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @else
                            <span class="text-gray-400 italic">Data ini sudah dihapus</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="p-3 text-gray-500 font-light italic text-center">
                        Tidak ada data saat ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tampilan kartu untuk bentuk mobile-->
    <div class="block md:hidden space-y-3">
        @forelse ($itemRequests as $data)
        <div class="border border-gray-200 rounded-xl p-3 shadow-sm {{ $data->trashed() ?  'text-red-600' : '' }}" data-id="{{ $data->id }}" data-id="{{ $data->id }}">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold">{{ $data->name}}</h3>
                <h3 class="font-semibold">{{ $data->user->name}}</h3>
            </div>
            <div class="mt-2 text-sm space-y-1">
                <p><span class="font-medium">Spesifikasi:</span> {{ $data->detail}}</p>
                <p><span class="font-medium">Jenis:</span> {{ $data->type->name}}</p>
                <p><span class="font-medium">Kuantitas:</span> {{ $data->qty}}</p>
                <p><span class="font-medium">Alasan:</span> {{ $data->reason}}</p>
                <p><span class="font-medium">Tanggal Usulan:</span> {{ $data->created_at}}</p>
                <p><span class="font-medium">Tanggal Usulan Dihapus:</span> {{ $data->deleted_at}}</p>
            </div>
            <div class="flex justify-end space-x-2 mt-3">
                @if (!$data->deleted_at)
                    <div class="flex justify-center space-x-2">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg" data-modal-target="edit-usulan">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg" data-modal-target="delete-modal">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                @else
                    <span class="text-gray-400 italic">Data ini sudah dihapus</span>
                @endif
            </div>
        </div>
        @empty
        <div class="mt-2 text-sm space-y-1 italic text-center">
            <p><span>Tidak ada data saat ini</span></p>
        </div>
        @endforelse
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-xs sm:text-sm text-gray-500 gap-3">
        <div class="flex items-center space-x-2">
            <span>Menampilkan</span>
            <select name="per_page" onchange="submitPerPage(this.value)"
                class="border border-blue-900 rounded-md text-gray-700 px-2 py-1 focus:ring-1 focus:ring-blue-500">
                <option value="5"  {{ request('per_page') == 5  ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
            </select>
            <span>item</span>
        </div>

        <div class="flex space-x-1">
            @if ($itemRequests->onFirstPage())
                <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&lt;</span>
            @else
                <a href="{{ $itemRequests->previousPageUrl() }}"
                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                &lt;
                </a>
            @endif

            @foreach ($itemRequests->getUrlRange(1, $itemRequests->lastPage()) as $page => $url)
                @if ($page == $itemRequests->currentPage())
                    <span class="px-2 sm:px-3 py-1 border border-blue-900 rounded-lg font-semibold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                    class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if ($itemRequests->hasMorePages())
                <a href="{{ $itemRequests->nextPageUrl() }}"
                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                &gt;
                </a>
            @else
                <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&gt;</span>
            @endif
        </div>
    </div>

    <!-- Modal Hapus -->
    <div id="delete-modal"
    class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4 transition duration-200 ease-out">
        <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-md rounded-xl shadow-lg p-6 relative transform scale-95 opacity-0 transition-all duration-200 ease-out">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Hapus Usulan</h2>
            <p class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus usulan untuk barang ini? Tindakan ini tidak dapat dibatalkan.</p>

            <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-center gap-3">
                <button type="button" id="closeDeleteModal" class="px-6 py-2 rounded-lg border border-gray-400 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">Hapus</button>
            </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
    <div id="toast-success"
        class="fixed bottom-4 right-4 z-50 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 opacity-0 translate-y-4 transition-all duration-300">
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div id="toast-error"
        class="fixed bottom-4 right-4 z-50 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 opacity-0 translate-y-4 transition-all duration-300">
        <span>{{ session('error') }}</span>
    </div>
@endif

<script>
    function submitPerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('delete-modal');
        const deleteModalContent = deleteModal.querySelector('div.bg-white');
        const closeDeleteBtn = deleteModal.querySelector('#closeDeleteModal');
        const deleteForm = deleteModal.querySelector('#deleteForm');

        document.querySelectorAll('button[data-modal-target="delete-modal"]').forEach(button => {
            button.addEventListener('click', () => {
                const row = button.closest('tr') || button.closest('div[data-id]');
                const id = row.dataset.id;
                deleteForm.action = `/item-requests/${id}`;
                deleteModal.classList.remove('hidden');
                setTimeout(() => {
                    deleteModalContent.classList.remove('scale-95', 'opacity-0');
                    deleteModalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
        });

        const closeDeleteModal = () => {
            deleteModalContent.classList.remove('scale-100', 'opacity-100');
            deleteModalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => deleteModal.classList.add('hidden'), 150);
        };

        closeDeleteBtn.addEventListener('click', closeDeleteModal);
        deleteModal.addEventListener('click', e => { if(e.target === deleteModal) closeDeleteModal(); });
    });

    function showToast(id) {
        const toast = document.getElementById(id);
        if (!toast) return;

        setTimeout(() => {
            toast.classList.remove("opacity-0", "translate-y-4");
            toast.classList.add("opacity-100", "translate-y-0");
        }, 100);

        setTimeout(() => {
            toast.classList.remove("opacity-100", "translate-y-0");
            toast.classList.add("opacity-0", "translate-y-4");

            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    showToast("toast-success");
    showToast("toast-error");
</script>
@endsection
