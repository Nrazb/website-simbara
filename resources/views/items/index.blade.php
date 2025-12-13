@extends('layouts.app')

@section('title', 'Barang Milik Negara | SIMBARA')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="font-semibold text-blue-900 text-lg">Data Barang</h2>
        <p class="font-light text-gray-400 text-sm">Daftar lengkap seluruh barang yang tercatat</p>
    </div>

    @include('components.header')
</div>
<div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
        <form action="{{ route('items.index') }}" method="GET">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <input type="text" name="search" placeholder="Search Item" value="{{ request('search') }}" id="filterForm"
                    class="form-control w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
            </div>
        </form>

        <div class="flex items-center gap-2">
            <button onclick="window.location.href='{{ route('items.create') }}'" class="bg-blue-900 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Barang</span>
            </button>
            <form id="importForm" action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data" class="border border-gray-300 text-gray-700 rounded-lg text-sm font-medium flex items-center space-x-1">
                @csrf
                <input id="importInput" type="file" name="file" accept=".xlsx,.xls,.csv" class="hidden">
                <button type="button" id="importButton" class="px-4 py-2 hover:bg-gray-100 flex items-center space-x-1">
                    <i class="fa-solid fa-download"></i>
                    <span>Import Data</span>
                </button>
            </form>
        </div>
    </div>

    <div class="mb-4 rounded-xl shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-4">
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
                <a href="{{ route('items.index') }}"
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
                    <th class="px-4 py-3">Id Barang</th>
                    <th class="px-4 py-3">Kode Barang</th>
                    <th class="px-4 py-3">NUP Barang</th>
                    <th class="px-4 py-3">Nama Barang</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Tanggal Perolehan</th>
                    <th class="px-4 py-3">Tahun Perolehan</th>
                    <th class="px-4 py-3">Nilai BMN</th>
                    <th class="px-4 py-3">Unit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($items as $item )
                <tr class="hover:bg-gray-50 transition" data-id="{{ $item->id}}">
                    <td class="px-4 py-3 font-medium">{{ $item->id }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->code }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->order_number }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->name }}</td>
                    <td class="px-4 py-3" data-type-id="{{ $item->type?->id }}">{{ $item->type?->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $item->acquisition_date }}</td>
                    <td class="px-4 py-3">{{ $item->acquisition_year }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($item->cost, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->user->name }}</td>
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
                <h3 class="font-semibol text-lg">{{ $item->name }}</h3>
                <h3 class="font-semibol text-lg">{{ $item->id }}</h3>
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                <p><span class="font-medium">Kode Barang:</span> {{ $item->code }}</p>
                <p><span class="font-medium">NUP Barang:</span> {{ $item->order_number }}</p>
                <p><span class="font-medium" data-type-id="{{ $item->type?->id }}">Jenis:</span> {{ $item->type?->name ?? '—' }}</p>
                <p><span class="font-medium">Tanggal Perolehan:</span> {{ $item->acquisition_date }}</p>
                <p><span class="font-medium">Tahun Perolehan:</span> {{ $item->acquisition_year }}</p>
                <p><span class="font-medium">Nilai BMN:</span> Rp {{ number_format($item->cost, 0, ',', '.') }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-sm sm:text-sm text-gray-500 gap-3">
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
<script>
    const importButton = document.getElementById('importButton');
    const importInput = document.getElementById('importInput');
    const importForm = document.getElementById('importForm');
    if (importButton && importInput && importForm) {
        importButton.addEventListener('click', () => importInput.click());
        importInput.addEventListener('change', () => {
            if (importInput.files.length > 0) importForm.submit();
        });
    }
</script>
@endsection
