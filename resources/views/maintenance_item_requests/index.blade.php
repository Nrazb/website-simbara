@extends('layouts.app')

@section('title', 'Items maintenance | SIMBARA')

@section('content')
    <div class="flex flex-col w-full h-full gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-blue-900 text-lg">Pemeliharan Barang</h2>
                <p class="font-light text-gray-400 text-sm">Daftar data pemeliharaan barang</p>
            </div>

            @include('components.header')
        </div>
        <div class="h-full p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
            <div class="mb-4">
                <form method="GET" class="w-full flex flex-col gap-1">
                    <div class="relative">
                        <label class="block text-gray-500 text-sm font-medium mb-1">Pencarian</label>
                        <input type="text" name="search" placeholder="Cari pemeliharaan atau barang"
                            value="{{ request('search') }}"
                            class="w-96 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-9 text-gray-400"></i>
                    </div>
                    <div class="flex gap-2 items-center flex-wrap">
                        <div>
                            <label class="block text-gray-500 text-sm font-medium mb-1">Status Barang</label>
                            <select name="item_status" onchange="this.form.submit()"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                                <option value="">Semua Status Barang</option>
                                <option value="GOOD" {{ request('item_status') == 'GOOD' ? 'selected' : '' }}>GOOD
                                </option>
                                <option value="DAMAGED" {{ request('item_status') == 'DAMAGED' ? 'selected' : '' }}>DAMAGED
                                </option>
                                <option value="REPAIRED" {{ request('item_status') == 'REPAIRED' ? 'selected' : '' }}>
                                    REPAIRED
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-sm font-medium mb-1">Status Pemeliharaan</label>
                            <select name="maintenance_status" onchange="this.form.submit()"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                                <option value="">Semua Status Pemeliharaan</option>
                                @foreach (['PENDING', 'APPROVED', 'BEING_SENT', 'PROCESSING', 'COMPLETED', 'REJECTED', 'REMOVED', 'BEING_SENT_BACK'] as $status)
                                    <option value="{{ $status }}"
                                        {{ request('maintenance_status') == $status ? 'selected' : '' }}>{{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-sm font-medium mb-1">Konfirmasi Unit</label>
                            <select name="unit_confirmed" onchange="this.form.submit()"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                                <option value="">Semua Konfirmasi</option>
                                <option value="1" {{ request('unit_confirmed') === '1' ? 'selected' : '' }}>Sudah
                                </option>
                                <option value="0" {{ request('unit_confirmed') === '0' ? 'selected' : '' }}>Belum
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-sm font-medium mb-1">Unit Pemeliharaan</label>
                            <select name="maintenance_user_id" onchange="this.form.submit()"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                                <option value="">Semua Unit Pemeliharaan</option>
                                @isset($maintenanceUnits)
                                    @foreach ($maintenanceUnits as $mu)
                                        <option value="{{ $mu->id }}"
                                            {{ request('maintenance_user_id') == $mu->id ? 'selected' : '' }}>
                                            {{ $mu->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-1">
                            <label class="block text-gray-500 text-sm font-medium mb-1">Rentang Tanggal (Dibuat)</label>
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

                    <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">
                </form>
                @if (request()->anyFilled([
                        'search',
                        'item_status',
                        'maintenance_status',
                        'maintenance_user_id',
                        'unit_confirmed',
                        'start_date',
                        'end_date',
                    ]))
                    <a href="{{ route('maintenance-item-requests.index') }}"
                        class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg text-sm font-medium border border-gray-300 hover:bg-gray-100 shadow-sm transition">
                        <i class="fa-solid fa-rotate-left text-gray-500"></i>
                        Reset
                    </a>
                @endif
            </div>

            <div class="h-full overflow-x-auto overflow-y-visible">
                <table class="min-w-full border-collapse text-sm text-left text-gray-600">
                    <thead class="text-gray-700 bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3">Status Barang</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3">Status Pemeliharaan</th>
                            <th class="px-4 py-3">Konfirmasi Unit</th>
                            <th class="px-4 py-3">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($maintenanceItemRequests as $data)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $data->item->name }}</td>
                                <td class="px-4 py-3">
                                    @if (auth()->user()?->role === 'MAINTENANCE_UNIT')
                                        <div x-data="{ open: false, disabled: {{ $data->unit_confirmed ? 'true' : 'false' }} }" class="block relative"
                                            @mouseenter="if (!disabled) open=true" @mouseleave="open=false">
                                            <button type="button"
                                                class="w-full px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 {{ $data->unit_confirmed ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $data->unit_confirmed ? 'disabled' : '' }}>{{ $data->item_status ?? '—' }}</button>
                                            <div x-show="open" x-transition
                                                class="absolute left-full top-0 bg-white border rounded shadow-md z-10 min-w-[140px]">
                                                @foreach (['GOOD', 'DAMAGED', 'REPAIRED'] as $status)
                                                    <form method="POST"
                                                        action="{{ route('maintenance-item-requests.update-item-status', $data->id) }}"
                                                        class="block">
                                                        @csrf
                                                        <input type="hidden" name="value" value="{{ $status }}">
                                                        <button type="submit"
                                                            class="w-full text-left px-3 py-2 hover:bg-gray-100 {{ $data->item_status === $status ? 'font-semibold' : '' }} {{ $data->unit_confirmed ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $data->unit_confirmed ? 'disabled' : '' }}>{{ $status }}</button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-3 py-1 rounded bg-gray-100">{{ $data->item_status ?? '—' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $data->information }}</td>
                                <td class="px-4 py-3">
                                    @if (auth()->user()?->role === 'MAINTENANCE_UNIT')
                                        <div x-data="{ open: false, disabled: {{ $data->unit_confirmed ? 'true' : 'false' }} }" class="block relative"
                                            @mouseenter="if (!disabled) open=true" @mouseleave="open=false">
                                            <button type="button"
                                                class="w-full px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 {{ $data->unit_confirmed ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $data->unit_confirmed ? 'disabled' : '' }}>{{ $data->maintenance_status }}</button>
                                            <div x-show="open" x-transition
                                                class="absolute left-full top-0 bg-white border rounded shadow-md z-10 min-w-[160px]">
                                                @foreach (['PENDING', 'PROCESS', 'COMPLETED', 'REJECTED', 'REMOVED'] as $status)
                                                    <form method="POST"
                                                        action="{{ route('maintenance-item-requests.update-request-status', $data->id) }}"
                                                        class="block">
                                                        @csrf
                                                        <input type="hidden" name="value"
                                                            value="{{ $status }}">
                                                        <button type="submit"
                                                            class="w-full text-left px-3 py-2 hover:bg-gray-100 {{ $data->maintenance_status === $status ? 'font-semibold' : '' }} {{ $data->unit_confirmed ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $data->unit_confirmed ? 'disabled' : '' }}>{{ $status }}</button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-3 py-1 rounded bg-gray-100">{{ $data->maintenance_status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($data->unit_confirmed)
                                        <span class="px-2 py-1 rounded text-white bg-green-600">Sudah</span>
                                    @else
                                        <form method="POST"
                                            action="{{ route('maintenance-item-requests.confirm-unit', $data->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1 rounded bg-blue-900 text-white {{ auth()->user()->id !== $data->user_id ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ auth()->user()->id !== $data->user_id ? 'disabled' : '' }}>Konfirmasi</button>
                                        </form>
                                    @endif
                                </td>

                                <td class="px-4 py-3">{{ $data->created_at ? $data->created_at->format('Y-m-d') : '—' }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-center text-xs sm:text-sm text-gray-500 gap-3">
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
                @if ($maintenanceItemRequests->onFirstPage())
                    <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&lt;</span>
                @else
                    <a href="{{ $maintenanceItemRequests->previousPageUrl() }}"
                        class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                        &lt;
                    </a>
                @endif

                @foreach ($maintenanceItemRequests->getUrlRange(1, $maintenanceItemRequests->lastPage()) as $page => $url)
                    @if ($page == $maintenanceItemRequests->currentPage())
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

                @if ($maintenanceItemRequests->hasMorePages())
                    <a href="{{ $maintenanceItemRequests->nextPageUrl() }}"
                        class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                        &gt;
                    </a>
                @else
                    <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&gt;</span>
                @endif

            </div>
        </div>
    </div>
@endsection
