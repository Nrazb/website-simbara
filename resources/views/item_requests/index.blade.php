@extends('layouts.app')

@section('title', 'Item Request | SIMBARA')

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
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <input type="text" placeholder="Search Item"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
        </div>

        <div class="flex items-center gap-2">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1" data-modal-target="create-usulan">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Usulan</span>
            </button>
            {{-- <button class="border border-gray-300 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1">
                <i class="fa-solid fa-upload"></i>
                <span>Export Data</span>
            </button> --}}
        </div>
    </div>

    <!-- Tampilan tabel di dekstop -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full border-collapse text-sm text-left text-gray-600">
            <thead class="text-gray-700 bg-gray-100 font-semibold">
                <tr>
                    <th class="px-4 py-3">Nama Barang</th>
                    <th class="px-4 py-3">Spesifikasi</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Quantity</th>
                    <th class="px-4 py-3">Alasan</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($itemRequests as $data )
                <tr class="hover:bg-gray-50 transition" data-id="{{ $data->id }}">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $data->name}}</td>
                    <td class="px-4 py-3">{{ $data->detail}}</td>
                    <td class="px-4 py-3" data-type-id="{{ $data->type->id }}">{{ $data->type->name}}</td>
                    <td class="px-4 py-3">{{ $data->qty}}</td>
                    <td class="px-4 py-3">{{ $data->reason}}</td>
                    <td class="px-4 py-3">{{ $data->user->name}}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <button class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-lg">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg" data-modal-target="edit-usulan">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg" data-modal-target="delete-modal">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tampilan kartu untuk bentuk mobile-->
    <div class="block md:hidden space-y-3">
        @foreach ($itemRequests as $data )
        <div class="border border-gray-200 rounded-xl p-3 shadow-sm" data-id="{{ $data->id }}">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-gray-800 text-lg">{{ $data->name}}</h3>
                <input type="checkbox" class="h-4 w-4">
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                <p><span class="font-medium">Spesifikasi:</span> {{ $data->detail}}</p>
                <p><span class="font-medium">Jenis:</span> {{ $data->type->name}}</p>
                <p><span class="font-medium">Quantity:</span> {{ $data->qty}}</p>
                <p><span class="font-medium">Alasan:</span> {{ $data->reason}}</p>
                <p><span class="font-medium">Unit:</span> {{ $data->user->name}}</p>
            </div>
            <div class="flex justify-end space-x-2 mt-3">
                <button class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-lg text-xs">
                    <i class="fa-regular fa-eye"></i>
                </button>
                <button class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg text-xs" data-modal-target="edit-usulan">
                    <i class="fa-solid fa-pencil"></i>
                </button>
                <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg text-xs" data-modal-target="delete-modal" >
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-xs sm:text-sm text-gray-500 gap-3">
        <div class="flex items-center space-x-2">
            <span>Showing</span>
            <select class="border border-blue-900 rounded-md text-gray-700 px-2 py-1 focus:ring-1 focus:ring-blue-500">
                <option>5</option>
                <option>10</option>
                <option>15</option>
            </select>
            <span>items</span>
        </div>

        <div class="flex space-x-1">
            <button class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">&lt;</button>
            <button class="px-2 sm:px-3 py-1 border border-blue-900 rounded-lg">1</button>
            <button class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">2</button>
            <button class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">3</button>
            <button class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">&gt;</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('delete-modal');
    const deleteModalContent = deleteModal.querySelector('div.bg-white');
    const closeDeleteBtn = deleteModal.querySelector('#closeDeleteModal');
    const deleteForm = deleteModal.querySelector('#deleteForm');

    document.querySelectorAll('button[data-modal-target="delete-modal"]').forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('tr') || button.closest('div[data-id]');
            const id = row.dataset.id;

            // Set action form delete dinamis
            deleteForm.action = `/item-requests/${id}`;

            // Tampilkan modal
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
</script>



@endsection
