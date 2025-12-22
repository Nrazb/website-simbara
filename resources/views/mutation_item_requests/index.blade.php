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
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4"></div>
        <div class="mb-4 rounded-xl shadow-sm">
            <form method="GET" class="flex flex-col gap-1" x-data x-ref="filterForm">
                <div class="flex flex-col items-start gap-4 w-full">
                    <div class="flex flex-col">
                        <label class="text-gray-500 text-sm font-medium">Saring</label>
                        <div class="flex gap-2 items-center">
                            <div class="relative min-w-[220px]">
                                <i
                                    class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="search" placeholder="Cari Barang"
                                    value="{{ request('search') }}"
                                    class="w-full border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if (auth()->user()->role === 'ADMIN')
                            <div class="flex flex-col">
                                <label class="block text-gray-500 text-sm font-medium mb-1">Asal Unit</label>
                                <div class="relative min-w-[200px]">
                                    <i
                                        class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <select name="from_user_id" @change="$refs.filterForm.submit()"
                                        class="w-full border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition">
                                        <option value="">Semua Asal</option>
                                        @isset($users)
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ request('from_user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-gray-500 text-sm font-medium mb-1">Tujuan Unit</label>
                                <div class="relative min-w-[200px]">
                                    <i
                                        class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <select name="to_user_id" @change="$refs.filterForm.submit()"
                                        class="w-full border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition">
                                        <option value="">Semua Tujuan</option>
                                        @isset($users)
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ request('to_user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="relative min-w-[180px]">
                            <label class="block text-gray-500 text-sm font-medium mb-1">Konfirmasi Unit Asal</label>
                            <select name="unit_confirmed" @change="$refs.filterForm.submit()"
                                class="w-full border rounded-lg px-3 py-2 pr-8 bg-white shadow-sm focus:ring focus:ring-blue-300">
                                <option value="">Semua</option>
                                <option value="1" {{ request('unit_confirmed') === '1' ? 'selected' : '' }}>Sudah
                                </option>
                                <option value="0" {{ request('unit_confirmed') === '0' ? 'selected' : '' }}>Belum
                                </option>
                            </select>
                        </div>
                        <div class="relative min-w-[180px]">
                            <label class="block text-gray-500 text-sm font-medium mb-1">Konfirmasi Unit Tujuan</label>
                            <select name="recipient_confirmed" @change="$refs.filterForm.submit()"
                                class="w-full border rounded-lg px-3 py-2 pr-8 bg-white shadow-sm focus:ring focus:ring-blue-300">
                                <option value="">Semua</option>
                                <option value="1" {{ request('recipient_confirmed') === '1' ? 'selected' : '' }}>
                                    Sudah</option>
                                <option value="0" {{ request('recipient_confirmed') === '0' ? 'selected' : '' }}>
                                    Belum</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 w-full">
                        <div class="flex flex-col gap-2">
                            <div class="flex flex-col gap-1">
                                <label class="text-gray-500 text-sm font-medium">Rentang Tanggal (Dibuat)</label>
                            </div>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <div class="relative min-w-[150px]">
                                        <i
                                            class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                                            @change="$refs.filterForm.submit()"
                                            class="border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400" />
                                    </div>
                                    <span class="text-gray-500">-</span>
                                    <div class="relative min-w-[150px]">
                                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                                            @change="$refs.filterForm.submit()"
                                            class="border rounded-lg px-3 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">

                @if (request()->anyFilled([
                        'search',
                        'from_user_id',
                        'to_user_id',
                        'unit_confirmed',
                        'recipient_confirmed',
                        'start_date',
                        'end_date',
                    ]))
                    <a href="{{ route('mutation-item-requests.index') }}"
                        class="flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg text-sm font-medium border border-gray-300 hover:bg-gray-100 shadow-sm transition">
                        <i class="fa-solid fa-rotate-left text-gray-500"></i>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div x-data class="overflow-x-auto">
            <table class="min-w-full border-collapse text-sm text-left text-gray-600">
                <thead class="text-gray-700 bg-gray-100 font-semibold">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3">Asal Unit</th>
                        <th class="px-4 py-3">Tujuan Unit</th>
                        <th class="px-4 py-3">Status Konfirmasi Unit Asal</th>
                        <th class="px-4 py-3">Status Konfirmasi Tujuan Unit</th>
                        <th class="px-4 py-3">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($mutationItemRequests as $data)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $data->item->name }}</td>
                            <td class="px-4 py-3">{{ $data->fromUser->name }}</td>
                            <td class="px-4 py-3">{{ $data->toUser->name }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($data->unit_confirmed)
                                    <span class="px-2 py-1 rounded text-white bg-green-600">Sudah</span>
                                @else
                                    <form method="POST" action="{{ route('mutation-item-requests.confirm', $data->id) }}"
                                        class="inline-block">
                                        @csrf
                                        <input type="hidden" name="target" value="unit">
                                        <button type="submit"
                                            class="px-3 py-1 rounded bg-blue-900 text-white {{ auth()->user()->id !== $data->from_user_id ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ auth()->user()->id !== $data->from_user_id ? 'disabled' : '' }}>Konfirmasi</button>
                                    </form>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($data->recipient_confirmed)
                                    <span class="px-2 py-1 rounded text-white bg-green-600">Sudah</span>
                                @else
                                    <form method="POST"
                                        action="{{ route('mutation-item-requests.confirm', $data->id) }}"
                                        class="inline-block">
                                        @csrf
                                        <input type="hidden" name="target" value="recipient">
                                        <button type="submit"
                                            class="px-3 py-1 rounded bg-blue-900 text-white {{ auth()->user()->id !== $data->to_user_id ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ auth()->user()->id !== $data->to_user_id ? 'disabled' : '' }}>Konfirmasi</button>
                                    </form>
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
