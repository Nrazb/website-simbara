@extends('layouts.app')

@section('title', 'Items Mutation | SIMBARA')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Mutasi Barang</h2>
        <p class="font-light text-gray-400 text-sm">Data mutasi barang</p>
    </div>

    @include('components.header')
</div>
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-2">
            <button onclick="window.location.href='{{ route('mutation-item-requests.create') }}'" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1">
                <i class="fas fa-exchange-alt"></i>
                <span>Mutasi Barang</span>
            </button>
        </div>
    </div>

    <!-- Tampilan tabel di dekstop -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full border-collapse text-sm text-left text-gray-600">
            <thead class="text-gray-700 bg-gray-100 font-semibold">
                <tr>
                    <th class="px-4 py-3">Nama Barang</th>
                    <th class="px-4 py-3">Asal Unit</th>
                    <th class="px-4 py-3">Tujuan Unit</th>
                    <th class="px-4 py-3">Status Konfirmasi Unit Asal</th>
                    <th class="px-4 py-3">Status Konfirmasi Tujuan Unit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($mutationItemRequests as $data )
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800">{{$data->item->name}}</td>
                    <td class="px-4 py-3">{{$data->fromUser->name}}</td>
                    <td class="px-4 py-3">{{$data->toUser->name}}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded text-white
                            {{ $data->unit_confirmed ? 'bg-green-600' : 'bg-red-600' }}">
                            {{ $data->unit_confirmed ? 'Sudah' : 'Belum' }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded text-white
                            {{ $data->recipient_confirmed ? 'bg-green-600' : 'bg-red-600' }}">
                            {{ $data->recipient_confirmed ? 'Sudah' : 'Belum' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tampilan kartu untuk bentuk mobile-->
    <div class="block md:hidden space-y-3"
        <div class="border border-gray-200 rounded-xl p-3 shadow-sm">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-gray-800 text-lg">jaosjfod</h3>
                <input type="checkbox" class="h-4 w-4">
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                <p><span class="font-medium">Jenis:</span> kolom</p>
                <p><span class="font-medium">Tanggal Perolehan:</span> bahfadfh</p>
                <p><span class="font-medium">Tahun Perolehan:</span> kolom</p>
                <p><span class="font-medium">Nilai BMN:</span> Rp </p>
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
            @if ($mutationItemRequests->onFirstPage())
                <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&lt;</span>
            @else
                <a href="{{ $mutationItemRequests->previousPageUrl() }}"
                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                &lt;
                </a>
            @endif

            @foreach ($mutationItemRequests->getUrlRange(1, $mutationItemRequests->lastPage()) as $page => $url)
                @if ($page == $mutationItemRequests->currentPage())
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

            @if ($mutationItemRequests->hasMorePages())
                <a href="{{ $mutationItemRequests->nextPageUrl() }}"
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
