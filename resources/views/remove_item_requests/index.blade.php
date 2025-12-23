@extends('layouts.app')

@section('title', 'Items Remove | SIMBARA')

@section('content')
    <div x-data="{ removeOpen: false }" class="flex items-center justify-between mb-4">
        <div>
            <h2 class="font-semibold text-blue-900 text-lg">Penghapusan Barang</h2>
            <p class="font-light text-gray-400 text-sm">Data penghapusan barang</p>
        </div>

        @include('components.header')
    </div>
    <div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
        <div class="flex flex-col mb-4 gap-1">
            <form method="GET" class="flex flex-col gap-1">
                <div class="relative">
                    <label class="block text-gray-500 text-sm font-medium mb-1">Saring</label>
                    <input type="text" name="search" placeholder="Cari Barang" value="{{ request('search') }}"
                        class="w-full sm:w-56 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-[2.35rem] -translate-y-1/2 text-gray-400"></i>
                </div>
                <div class="flex gap-2 items-center flex-wrap">
                    @if (auth()->user()->role === 'ADMIN')
                        <div class="relative min-w-[180px]">
                            <label class="block text-gray-500 text-sm font-medium mb-1">Unit</label>
                            <select name="user_id" onchange="this.form.submit()"
                                class="border rounded-lg px-3 py-2 pr-8 bg-white shadow-sm focus:ring focus:ring-blue-300">
                                <option value="">Semua Unit</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="relative min-w-[180px]">
                        <label class="block text-gray-500 text-sm font-medium mb-1">Status Penghapusan</label>
                        <select name="status" onchange="this.form.submit()"
                            class="border rounded-lg px-3 py-2 pr-8 bg-white shadow-sm focus:ring focus:ring-blue-300">
                            <option value="">Semua Status Penghapusan</option>
                            <option value="PROCESS" {{ request('status') == 'PROCESS' ? 'selected' : '' }}>PROCESS</option>
                            <option value="STORED" {{ request('status') == 'STORED' ? 'selected' : '' }}>STORED</option>
                            <option value="AUCTIONED" {{ request('status') == 'AUCTIONED' ? 'selected' : '' }}>AUCTIONED
                            </option>
                        </select>
                    </div>
                    <div class="relative min-w-[180px]">
                        <label class="block text-gray-500 text-sm font-medium mb-1">Konfirmasi Unit</label>
                        <select name="unit_confirmed" onchange="this.form.submit()"
                            class="border rounded-lg px-3 py-2 pr-8 bg-white shadow-sm focus:ring focus:ring-blue-300">
                            <option value="">Semua</option>
                            <option value="1" {{ request('unit_confirmed') === '1' ? 'selected' : '' }}>Sudah</option>
                            <option value="0" {{ request('unit_confirmed') === '0' ? 'selected' : '' }}>Belum</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex flex-col gap-1">
                        <label class="block text-gray-500 text-sm font-medium">Rentang Tanggal (Dibuat)</label>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <div class="relative min-w-[150px]">
                                <i
                                    class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    @change="this.form.submit()"
                                    class="border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400" />
                            </div>
                            <span class="text-gray-500">-</span>
                            <div class="relative min-w-[150px]">
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    @change="this.form.submit()"
                                    class="border rounded-lg px-3 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400" />
                            </div>
                        </div>
                    </div>
                </div>

                @if (request()->anyFilled(['search', 'user_id', 'status', 'unit_confirmed', 'start_date', 'end_date']))
                    <a href="{{ route('remove-item-requests.index') }}"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 border border-gray-300 transition">
                        <i class="fa-solid fa-rotate-left text-gray-500"></i>
                        Reset
                    </a>
                @endif
            </form>

            @if (auth()->user()->role !== 'ADMIN')
                <div class="ml-auto">
                    <button type="button" @click="removeOpen=true"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-all">
                        <i class="fas fa-trash"></i>
                        <span>Hapus Barang</span>
                    </button>
                </div>
            @endif
        </div>


        <!-- Tampilan tabel di dekstop -->
        <div class="md:block overflow-x-auto">
            <table class="min-w-full border-collapse text-sm text-left text-gray-600">
                <thead class="text-gray-700 bg-gray-100 font-semibold">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">Status Penghapusan</th>
                        <th class="px-4 py-3">Konfirmasi Unit</th>
                        <th class="px-4 py-3">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($removeItemRequests as $data)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $data->item->name }}</td>
                            <td class="px-4 py-3">{{ $data->user->name }}</td>
                            <td class="px-4 py-3">
                                @if (auth()->user()->role === 'ADMIN')
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button type="button" @click="open = !open"
                                            class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">{{ $data->status }}</button>
                                        <div x-show="open" x-transition
                                            class="absolute left-0 mt-1 bg-white border rounded shadow-md z-10">
                                            <form method="POST"
                                                action="{{ route('remove-item-requests.update-status', $data->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="PROCESS">
                                                <button type="submit"
                                                    class="w-full text-left px-3 py-2 hover:bg-gray-100">PROCESS</button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('remove-item-requests.update-status', $data->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="STORED">
                                                <button type="submit"
                                                    class="w-full text-left px-3 py-2 hover:bg-gray-100">STORED</button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('remove-item-requests.update-status', $data->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="AUCTIONED">
                                                <button type="submit"
                                                    class="w-full text-left px-3 py-2 hover:bg-gray-100">AUCTIONED</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span>{{ $data->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-left">
                                @if (auth()->user()->id === $data->user_id && !$data->unit_confirmed)
                                    <form method="POST"
                                        action="{{ route('remove-item-requests.confirm-unit', $data->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">Konfirmasi</button>
                                    </form>
                                @else
                                    <span
                                        class="px-2 py-1 rounded text-white {{ $data->unit_confirmed ? 'bg-green-600' : 'bg-red-600' }}">
                                        {{ $data->unit_confirmed ? 'Sudah' : 'Belum' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $data->created_at ? $data->created_at->format('Y-m-d') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-xs sm:text-sm text-gray-500 gap-3">
            <div class="flex items-center space-x-2" x-data>
                <span>Showing</span>
                <select name="per_page"
                    @change="(() => { const url = new URL(window.location.href); url.searchParams.set('per_page', $event.target.value); window.location.href = url.toString(); })()"
                    class="border border-blue-900 rounded-md text-gray-700 px-2 py-1 focus:ring-1 focus:ring-blue-500">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
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

    <template x-if="removeOpen">
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4"
            x-transition @click.self="removeOpen=false">
            <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-xl rounded-xl shadow-lg p-6 relative"
                x-transition.opacity x-transition.scale>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Pilih Barang untuk Dihapus</h2>
                <form method="POST" action="{{ route('remove-item-requests.store') }}">
                    @csrf
                    <div class="flex flex-col gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Barang <span
                                    class="text-red-500">*</span></label>
                            <select name="item_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900"
                                required>
                                <option disabled selected>-- Pilih Barang --</option>
                                @isset($items)
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->id }})
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-center gap-3 border-t pt-4 mt-4">
                        <button type="button"
                            class="px-6 py-2 rounded-lg border border-blue-900 text-gray-700 hover:bg-gray-200"
                            @click="removeOpen=false">Batal</button>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
@endsection
