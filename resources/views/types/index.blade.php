@extends('layouts.app')

@section('title', 'Jenis Barang | SIMBARA')

@section('content')
@include('types.create')
@include('types.edit')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Jenis Barang</h2>
        <p class="font-light text-gray-400 text-sm">Daftar jenis barang milik negara</p>
    </div>

    @include('components.header')
</div>
<div class="p-3 sm:p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-end gap-4 mb-5">
        <button
            class="flex items-center gap-2 px-4 py-2 text-blue-900 border border-blue-900 hover:bg-blue-900 hover:text-white
                   rounded-lg text-sm font-medium shadow-sm transition"
            data-modal-target="create-type">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah jenis</span>
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse text-sm text-left text-gray-600">
            <thead class="text-gray-700 bg-gray-100 font-semibold text-center">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama Jenis Barang</th>
                    <th class="px-4 py-3">Tanggal dibuat</th>
                    <th class="px-4 py-3">Tanggal dihapus</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($types as $data )
                <tr class="hover:bg-gray-50 transition" data-id="{{ $data->id }}">
                    <td class="px-4 py-3 font-medium text-center">{{ $data->id}}</td>
                    <td class="px-4 py-3 font-medium">{{ $data->name}}</td>
                    <td class="px-4 py-3">{{$data->created_at}}</td>
                    <td class="px-4 py-3 text-center">{{$data?->deleted_at ?? '-'}}</td>
                    <td class="px-4 py-3 text-center">
                        @if(!$data->deleted_at)
                            <div class="flex justify-center space-x-2">
                                <button class="border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-white p-2 rounded-lg"
                                    data-modal-target="edit-type">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <button class="border border-red-500 text-red-500 hover:bg-red-600 hover:text-white p-2 rounded-lg"
                                    data-modal-target="delete-modal-type">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @else
                            <span class="text-gray-400 italic">Data ini sudah dihapus</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
            @if ($types->onFirstPage())
                <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&lt;</span>
            @else
                <a href="{{ $types->previousPageUrl() }}"
                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                &lt;
                </a>
            @endif

            @foreach ($types->getUrlRange(1, $types->lastPage()) as $page => $url)
                @if ($page == $types->currentPage())
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

            @if ($types->hasMorePages())
                <a href="{{ $types->nextPageUrl() }}"
                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                &gt;
                </a>
            @else
                <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&gt;</span>
            @endif
        </div>
    </div>

    <div id="delete-modal-type"
    class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4 transition duration-200 ease-out">
        <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-md rounded-xl shadow-lg p-6 relative transform scale-95 opacity-0 transition-all duration-200 ease-out">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Hapus Usulan</h2>
            <p class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus jenis barang ini? Tindakan ini tidak dapat dibatalkan.</p>

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
        const deleteModal = document.getElementById('delete-modal-type');
        const deleteModalContent = deleteModal.querySelector('div.bg-white');
        const closeDeleteBtn = deleteModal.querySelector('#closeDeleteModal');
        const deleteForm = deleteModal.querySelector('#deleteForm');

        document.querySelectorAll('button[data-modal-target="delete-modal-type"]').forEach(button => {
            button.addEventListener('click', () => {
                const row = button.closest('tr') || button.closest('div[data-id]');
                const id = row.dataset.id;
                deleteForm.action = `/types/${id}`;
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
