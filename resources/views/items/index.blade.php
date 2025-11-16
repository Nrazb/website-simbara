@extends('layouts.app')

@section('title', 'Items | SIMBARA')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Data Barang</h2>
        <p class="font-light text-gray-400 text-sm">Daftar lengkap seluruh barang yang tercatat</p>
    </div>

    @include('components.header')
</div>
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <form action="{{ route('items.index') }}" method="GET">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <input type="text" name="search" placeholder="Search Item" value="{{ request('search') }}" id="filterForm"
                    class="form-control w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
            </div>
        </form>

        <div class="flex items-center gap-2">
            <button onclick="window.location.href='{{ route('items.create') }}'" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Barang</span>
            </button>
            <a href="{{ route('items.import') }}" class="border border-gray-300 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1">
                <i class="fa-solid fa-download"></i>
                <span>Import Data</span>
            </a>
            <a href="{{ route('items.export') }}" class="border border-gray-300 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1">
                <i class="fa-solid fa-upload"></i>
                <span>Export Data</span>
            </a>
        </div>
    </div>

    <!-- Tampilan tabel di dekstop -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full border-collapse text-sm text-left text-gray-600">
            <thead class="text-gray-700 bg-gray-100 font-semibold">
                <tr>
                    <th class="px-4 py-3">Nama Barang</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Tanggal Perolehan</th>
                    <th class="px-4 py-3">Tahun Perolehan</th>
                    <th class="px-4 py-3">Nilai BMN</th>
                    <th class="px-4 py-3 text-center">Perlakuan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($items as $item )
                <tr class="hover:bg-gray-50 transition" data-id="{{ $item->id}}">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $item->name }}</td>
                    <td class="px-4 py-3" data-type-id="{{ $item->type->id}}">{{ $item->type->name}}</td>
                    <td class="px-4 py-3">{{ $item->acquisition_date }}</td>
                    <td class="px-4 py-3">{{ $item->acquisition_year }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($item->cost, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <button class="bg-blue-900 hover:bg-blue-800 text-white p-2 rounded-lg">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            <button class="bg-amber-400 hover:bg-amber-500 text-white p-2 rounded-lg">
                                <i class="fas fa-tools"></i>
                            </button>
                            <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg">
                                <i class="fas fa-trash"></i>
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
        @foreach ($items as $item )
        <div class="border border-gray-200 rounded-xl p-3 shadow-sm" data-id="{{ $item->id}}">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-gray-800 text-lg">{{ $item->name }}</h3>
                <input type="checkbox" class="h-4 w-4">
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                <p><span class="font-medium" data-type-id="{{ $item->type->id}}">Jenis:</span> {{ $item->type->name}}</p>
                <p><span class="font-medium">Tanggal Perolehan:</span> {{ $item->acquisition_date }}</p>
                <p><span class="font-medium">Tahun Perolehan:</span> {{ $item->acquisition_year }}</p>
                <p><span class="font-medium">Nilai BMN:</span> Rp {{ number_format($item->cost, 0, ',', '.') }}</p>
            </div>
            <div class="flex justify-end space-x-2 mt-3">
                <button class="bg-blue-900 hover:bg-blue-800 text-white p-2 rounded-lg">
                    <i class="fas fa-exchange-alt"></i>
                </button>
                <button class="bg-amber-400 hover:bg-amber-500 text-white p-2 rounded-lg">
                    <i class="fas fa-tools"></i>
                </button>
                <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @endforeach
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
            @if ($items->onFirstPage())
                <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&lt;</span>
            @else
                <a href="{{ $items->previousPageUrl() }}"
                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                &lt;
                </a>
            @endif

            @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                @if ($page == $items->currentPage())
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

            @if ($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}"
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
