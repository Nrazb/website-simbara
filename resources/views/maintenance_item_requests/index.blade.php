@extends('layouts.app')

@section('title', 'Items maintenance | SIMBARA')

@section('content')
    @php
        $currentUser = auth()->user();
        $maintenanceStatusLabels = [
            'PENDING' => 'Menunggu',
            'APPROVED' => 'Disetujui',
            'BEING_SENT' => 'Sedang Dikirim',
            'BEING_RECEIVED' => 'Diterima Unit',
            'PROCESSING' => 'Diproses',
            'FIINISHED' => 'Selesai',
            'REJECTED' => 'Ditolak',
            'REMOVED' => 'Dihapus',
            'BEING_SENT_BACK' => 'Dikirim Kembali',
            'BEING_RECEIVED_BACK' => 'Diterima Kembali',
            'COMPLETED' => 'Selesai',
        ];
        $itemStatusLabels = [
            'PENDING' => 'Menunggu',
            'GOOD' => 'Baik',
            'DAMAGED' => 'Rusak',
            'REPAIRED' => 'Diperbaiki',
        ];
    @endphp
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
                                <option value="PENDING" {{ request('item_status') == 'PENDING' ? 'selected' : '' }}>
                                    {{ $itemStatusLabels['PENDING'] ?? 'PENDING' }}</option>
                                <option value="GOOD" {{ request('item_status') == 'GOOD' ? 'selected' : '' }}>
                                    {{ $itemStatusLabels['GOOD'] ?? 'GOOD' }}
                                </option>
                                <option value="DAMAGED" {{ request('item_status') == 'DAMAGED' ? 'selected' : '' }}>
                                    {{ $itemStatusLabels['DAMAGED'] ?? 'DAMAGED' }}
                                </option>
                                <option value="REPAIRED" {{ request('item_status') == 'REPAIRED' ? 'selected' : '' }}>
                                    {{ $itemStatusLabels['REPAIRED'] ?? 'REPAIRED' }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-sm font-medium mb-1">Status Pemeliharaan</label>
                            <select name="maintenance_status" onchange="this.form.submit()"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                                <option value="">Semua Status Pemeliharaan</option>
                                @foreach (['PENDING', 'APPROVED', 'BEING_SENT', 'BEING_RECEIVED', 'PROCESSING', 'FIINISHED', 'REJECTED', 'REMOVED', 'BEING_SENT_BACK', 'BEING_RECEIVED_BACK', 'COMPLETED'] as $status)
                                    <option value="{{ $status }}"
                                        {{ request('maintenance_status') == $status ? 'selected' : '' }}>
                                        {{ $maintenanceStatusLabels[$status] ?? $status }}
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
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($maintenanceItemRequests as $data)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $data->item->name }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $isMaintenanceUnit = $currentUser && $currentUser->role === 'MAINTENANCE_UNIT';
                                        $itemStatusEditable =
                                            $isMaintenanceUnit &&
                                            !$data->unit_confirmed &&
                                            $data->maintenance_status === 'PROCESSING';
                                        $itemStatusLabel =
                                            $itemStatusLabels[$data->item_status] ?? ($data->item_status ?? '—');
                                    @endphp

                                    @if ($itemStatusEditable)
                                        <div x-data="{ open: false }" @click.outside="open = false" class="block relative">
                                            <button type="button" @click="open = !open"
                                                class="w-full px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">{{ $itemStatusLabel }}</button>
                                            <div x-show="open" x-cloak x-transition
                                                class="absolute left-0 top-full mt-1 bg-white border rounded shadow-md z-20 min-w-[140px]">
                                                @foreach (['GOOD', 'DAMAGED', 'REPAIRED'] as $status)
                                                    <form method="POST"
                                                        action="{{ route('maintenance-item-requests.update-item-status', $data->id) }}"
                                                        class="block">
                                                        @csrf
                                                        <input type="hidden" name="value" value="{{ $status }}">
                                                        <button type="submit"
                                                            class="w-full text-left px-3 py-2 hover:bg-gray-100 {{ $data->item_status === $status ? 'font-semibold' : '' }}">{{ $itemStatusLabels[$status] ?? $status }}</button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-3 py-1 rounded bg-gray-100">{{ $itemStatusLabel }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $data->information }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $maintenanceLabel =
                                            $maintenanceStatusLabels[$data->maintenance_status] ??
                                            $data->maintenance_status;
                                        $isOwner =
                                            $currentUser &&
                                            $currentUser->id === $data->user_id &&
                                            $currentUser->role !== 'MAINTENANCE_UNIT';
                                        $maintenanceNextStatuses = [];

                                        if (!$data->unit_confirmed) {
                                            if ($data->maintenance_status === 'PENDING') {
                                                if ($isMaintenanceUnit) {
                                                    $maintenanceNextStatuses = ['APPROVED', 'REJECTED'];
                                                } elseif ($isOwner) {
                                                    $maintenanceNextStatuses = ['BEING_SENT'];
                                                }
                                            } elseif (
                                                $data->maintenance_status === 'APPROVED' &&
                                                ($isOwner || $isMaintenanceUnit)
                                            ) {
                                                $maintenanceNextStatuses = ['BEING_SENT'];
                                            } elseif (
                                                $data->maintenance_status === 'BEING_SENT' &&
                                                $isMaintenanceUnit
                                            ) {
                                                $maintenanceNextStatuses = ['BEING_RECEIVED'];
                                            } elseif (
                                                $data->maintenance_status === 'BEING_RECEIVED' &&
                                                $isMaintenanceUnit
                                            ) {
                                                $maintenanceNextStatuses = ['PROCESSING'];
                                            } elseif (
                                                $data->maintenance_status === 'PROCESSING' &&
                                                $isMaintenanceUnit
                                            ) {
                                                $maintenanceNextStatuses = ['FIINISHED', 'REJECTED', 'REMOVED'];
                                            } elseif (
                                                in_array($data->maintenance_status, ['FIINISHED', 'REJECTED'], true) &&
                                                $isMaintenanceUnit
                                            ) {
                                                $maintenanceNextStatuses = ['BEING_SENT_BACK'];
                                            } elseif ($data->maintenance_status === 'BEING_SENT_BACK' && $isOwner) {
                                                $maintenanceNextStatuses = ['BEING_RECEIVED_BACK'];
                                            } elseif ($data->maintenance_status === 'BEING_RECEIVED_BACK' && $isOwner) {
                                                $maintenanceNextStatuses = ['COMPLETED'];
                                            }
                                        }
                                    @endphp

                                    @if (!empty($maintenanceNextStatuses))
                                        <div x-data="{ open: false }" @click.outside="open = false"
                                            class="block relative">
                                            <button type="button" @click="open = !open"
                                                class="w-full px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">{{ $maintenanceLabel }}</button>
                                            <div x-show="open" x-cloak x-transition
                                                class="absolute left-0 top-full mt-1 bg-white border rounded shadow-md z-20 min-w-[180px]">
                                                @foreach ($maintenanceNextStatuses as $status)
                                                    <form method="POST"
                                                        action="{{ route('maintenance-item-requests.update-request-status', $data->id) }}"
                                                        class="block">
                                                        @csrf
                                                        <input type="hidden" name="value"
                                                            value="{{ $status }}">
                                                        <button type="submit"
                                                            class="w-full text-left px-3 py-2 hover:bg-gray-100">
                                                            {{ $maintenanceStatusLabels[$status] ?? $status }}
                                                        </button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-3 py-1 rounded bg-gray-100">{{ $maintenanceLabel }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($data->unit_confirmed)
                                        <span class="px-2 py-1 rounded text-white bg-green-600">Sudah</span>
                                    @else
                                        @php
                                            $confirmAllowed =
                                                $isOwner &&
                                                in_array(
                                                    $data->maintenance_status,
                                                    ['REJECTED', 'REMOVED', 'COMPLETED', 'BEING_RECEIVED_BACK'],
                                                    true,
                                                );
                                        @endphp

                                        @if ($confirmAllowed)
                                            <form method="POST"
                                                action="{{ route('maintenance-item-requests.confirm-unit', $data->id) }}"
                                                class="inline-block">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 rounded bg-blue-900 text-white">Konfirmasi</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    @endif
                                </td>

                                <td class="px-4 py-3">{{ $data->created_at ? $data->created_at->format('Y-m-d') : '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    @php
                                        $infoEditable =
                                            $isMaintenanceUnit &&
                                            !$data->unit_confirmed &&
                                            in_array(
                                                $data->maintenance_status,
                                                ['COMPLETED', 'REJECTED', 'REMOVED', 'FIINISHED'],
                                                true,
                                            );
                                    @endphp

                                    @if ($infoEditable)
                                        <div x-data="{ open: false }" class="inline-block">
                                            <button type="button" @click="open = true"
                                                class="px-3 py-1 rounded bg-blue-900 text-white">Edit</button>

                                            <div x-show="open" x-cloak
                                                class="fixed inset-0 z-50 flex items-center justify-center">
                                                <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
                                                <div class="relative bg-white rounded-xl shadow-lg w-full max-w-md p-5">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <h3 class="text-gray-800 font-semibold">Ubah Keterangan</h3>
                                                        <button type="button" class="text-gray-500"
                                                            @click="open = false">×</button>
                                                    </div>
                                                    <form method="POST"
                                                        action="{{ route('maintenance-item-requests.update-information', $data->id) }}"
                                                        class="flex flex-col gap-3">
                                                        @csrf
                                                        <input type="text" name="information"
                                                            value="{{ old('information', $data->information) }}"
                                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500"
                                                            placeholder="Keterangan pemeliharaan" />
                                                        <div class="flex justify-end gap-2">
                                                            <button type="button" @click="open = false"
                                                                class="px-3 py-2 rounded bg-gray-100 text-gray-700">Batal</button>
                                                            <button type="submit"
                                                                class="px-3 py-2 rounded bg-blue-900 text-white">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">—</span>
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

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
            class="fixed bottom-4 right-4 z-50 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
            class="fixed bottom-4 right-4 z-50 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
            <span>{{ session('error') }}</span>
        </div>
    @endif
@endsection
