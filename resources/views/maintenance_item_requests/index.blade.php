@extends('layouts.app')

@section('title', 'Items maintenance | SIMBARA')

@section('content')
<div class="flex flex-col w-full h-full gap-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-blue-900 text-lg">Mutasi Barang</h2>
            <p class="font-light text-gray-400 text-sm">Data mutasi barang</p>
        </div>

        @include('components.header')
    </div>
    <div class="h-full p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
        <div class="mb-4">
            <form method="GET" class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="relative">
                    <input type="text" name="search" placeholder="Search Item" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
                </div>
                <div>
                    <select name="item_status" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                        <option value="">Semua Status Barang</option>
                        <option value="GOOD" {{ request('item_status') == 'GOOD' ? 'selected' : '' }}>GOOD</option>
                        <option value="DAMAGED" {{ request('item_status') == 'DAMAGED' ? 'selected' : '' }}>DAMAGED</option>
                        <option value="REPAIRED" {{ request('item_status') == 'REPAIRED' ? 'selected' : '' }}>REPAIRED
                        </option>
                    </select>
                </div>
                <div>
                    <select name="request_status" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                        <option value="">Semua Status Pemeliharaan</option>
                        <option value="PENDING" {{ request('request_status') == 'PENDING' ? 'selected' : '' }}>PENDING
                        </option>
                        <option value="PROCESS" {{ request('request_status') == 'PROCESS' ? 'selected' : '' }}>PROCESS
                        </option>
                        <option value="COMPLETED" {{ request('request_status') == 'COMPLETED' ? 'selected' : '' }}>COMPLETED
                        </option>
                        <option value="REJECTED" {{ request('request_status') == 'REJECTED' ? 'selected' : '' }}>REJECTED
                        </option>
                        <option value="REMOVED" {{ request('request_status') == 'REMOVED' ? 'selected' : '' }}>REMOVED
                        </option>
                    </select>
                </div>
                <div>
                    <select name="unit_confirmed" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                        <option value="">Semua Konfirmasi Unit</option>
                        <option value="1" {{ request('unit_confirmed') === '1' ? 'selected' : '' }}>Sudah</option>
                        <option value="0" {{ request('unit_confirmed') === '0' ? 'selected' : '' }}>Belum</option>
                    </select>
                </div>
                <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">
            </form>
        </div>

        <div class="h-full overflow-x-auto overflow-y-visible">
            <table class="min-w-full border-collapse text-sm text-left text-gray-600">
                <thead class="text-gray-700 bg-gray-100 font-semibold">
                    <tr>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3">Status Barang</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Status Pemeliharaan</th>
                        <th class="px-4 py-3">Konfirmasi Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($maintenanceItemRequests as $data)
                        <tr class="hover:bg-gray-50 transition">
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
                                            {{ $data->unit_confirmed ? 'disabled' : '' }}>{{ $data->request_status }}</button>
                                        <div x-show="open" x-transition
                                            class="absolute left-full top-0 bg-white border rounded shadow-md z-10 min-w-[160px]">
                                            @foreach (['PENDING', 'PROCESS', 'COMPLETED', 'REJECTED', 'REMOVED'] as $status)
                                                <form method="POST"
                                                    action="{{ route('maintenance-item-requests.update-request-status', $data->id) }}"
                                                    class="block">
                                                    @csrf
                                                    <input type="hidden" name="value" value="{{ $status }}">
                                                    <button type="submit"
                                                        class="w-full text-left px-3 py-2 hover:bg-gray-100 {{ $data->request_status === $status ? 'font-semibold' : '' }} {{ $data->unit_confirmed ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $data->unit_confirmed ? 'disabled' : '' }}>{{ $status }}</button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <span class="px-3 py-1 rounded bg-gray-100">{{ $data->request_status }}</span>
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
                                            class="px-3 py-1 rounded bg-blue-900 text-white {{ auth()->id() !== $data->user_id ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ auth()->id() !== $data->user_id ? 'disabled' : '' }}>Konfirmasi</button>
                                    </form>
                                @endif
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
            <select name="per_page" @change="(() => { const url = new URL(window.location.href); url.searchParams.set('per_page', $event.target.value); window.location.href = url.toString(); })()"
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
                    <a href="{{ $url }}" class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
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
