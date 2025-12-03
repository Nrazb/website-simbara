@extends('layouts.app')

@section('title', 'Items Remove | SIMBARA')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Penghapusan Barang</h2>
        <p class="font-light text-gray-400 text-sm">Data penghapusan barang</p>
    </div>

    @include('components.header')
</div>
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center mb-4 gap-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative min-w-[220px]">
                <select name="user_id" onchange="this.form.submit()"
                    class="border rounded-lg px-3 py-2 pr-8 bg-white shadow-sm focus:ring focus:ring-blue-300">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(request()->anyFilled(['user_id']))
                <a href="{{ route('remove-item-requests.index') }}"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium
                       hover:bg-gray-200 border border-gray-300 transition">
                    <i class="fa-solid fa-rotate-left text-gray-500"></i>
                    Reset
                </a>
            @endif
        </form>

        <div class="ml-auto">
            <button id="openMutasiModal"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium
                   flex items-center gap-2 shadow-sm transition-all">
                <i class="fas fa-trash"></i>
                <span>Hapus Barang</span>
            </button>
        </div>
    </div>


    <!-- Tampilan tabel di dekstop -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full border-collapse text-sm text-left text-gray-600">
            <thead class="text-gray-700 bg-gray-100 font-semibold">
                <tr>
                    <th class="px-4 py-3">Nama Barang</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Status Penghapusan</th>
                    <th class="px-4 py-3">Konfirmasi Unit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($removeItemRequests as $data )
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800">{{$data->item->name}}</td>
                    <td class="px-4 py-3">{{$data->user->name}}</td>
                    <td class="px-4 py-3">{{$data->status}}</td>
                    <td class="px-4 py-3 text-left">
                        <span class="px-2 py-1 rounded text-white
                            {{ $data->unit_confirmed ? 'bg-green-600' : 'bg-red-600' }}">
                            {{ $data->unit_confirmed ? 'Sudah' : 'Belum' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tampilan kartu untuk bentuk mobile-->
    <div class="block md:hidden space-y-3">
        <div class="border border-gray-200 rounded-xl p-3 shadow-sm">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-gray-800 text-lg">jaosjfod</h3>
                <input type="checkbox" class="h-4 w-4">
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                <p><span class="font-medium">Unit:</span> kolom</p>
                <p><span class="font-medium">Status Penghapusan:</span> bahfadfh</p>
                <p><span class="font-medium">Konfirmasi Unit:</span> kolom</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-xs sm:text-sm text-gray-500 gap-3">
        <div class="flex items-center space-x-2">
            <span>Showing</span>
            <select name="per_page" onchange="submitPerPage(this.value)"
                class="border border-blue-900 rounded-md text-gray-700 px-2 py-1 focus:ring-1 focus:ring-blue-500">
                <option value="5"  {{ request('per_page') == 5  ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
            </select>
            <span>items</span>
        </div>

        <div class="flex space-x-1">
            @if ($removeItemRequests->onFirstPage())
                <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&lt;</span>
            @else
                <a href="{{ $removeItemRequests->previousPageUrl() }}"
                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                &lt;
                </a>
            @endif

            @foreach ($removeItemRequests->getUrlRange(1, $removeItemRequests->lastPage()) as $page => $url)
                @if ($page == $removeItemRequests->currentPage())
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

            @if ($removeItemRequests->hasMorePages())
                <a href="{{ $removeItemRequests->nextPageUrl() }}"
                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                &gt;
                </a>
            @else
                <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&gt;</span>
            @endif

        </div>
    </div>
</div>
<script>
    function submitPerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }
</script>
@endsection
