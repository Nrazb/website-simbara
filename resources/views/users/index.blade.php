@extends('layouts.app')

@section('title', 'Jenis Barang | SIMBARA')

@section('content')
    <div x-data="{
        createOpen: false,
        selectedId: null,
        selectedEditData: null,
        submitPerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }
    }">
        @include('components.users.create')
        @include('components.users.edit')
        @include('components.users.delete')
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-semibold text-blue-900 text-lg">Pengguna SIMBARA</h2>
                <p class="font-light text-gray-400 text-sm">Daftar pengguna SIMBARA</p>
            </div>

            @include('components.header')
        </div>
        <div class="p-3 sm:p-6 bg-white rounded-2xl shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <form method="GET" class="relative w-full sm:max-w-xs">
                    <input type="text" name="search" placeholder="Cari Pengguna" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <div class="flex items-center gap-4">
                    <form method="GET" class="relative min-w-[220px]">
                        <i
                            class="fa-solid fa-user-shield absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select name="role" onchange="this.form.submit()"
                            class="w-full border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition">
                            <option value="">Semua Role</option>
                            <option value="ADMIN" {{ request('role') == 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="UNIT" {{ request('role') == 'UNIT' ? 'selected' : '' }}>Unit</option>
                            <option value="MAINTENANCE_UNIT" {{ request('role') == 'MAINTENANCE_UNIT' ? 'selected' : '' }}>
                                Unit Pemeliharaan</option>
                        </select>
                    </form>
                    @if (request()->anyFilled(['role', 'search']))
                        <a href="{{ route('users.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg text-sm font-medium border border-gray-300 hover:bg-gray-100 shadow-sm transition">
                            <i class="fa-solid fa-rotate-left text-gray-500"></i>
                            Reset
                        </a>
                    @endif

                    <button @click="createOpen = true"
                        class="flex items-center gap-2 px-4 py-2 text-blue-900 border border-blue-900 hover:bg-blue-900 hover:text-white rounded-lg text-sm font-medium shadow-sm transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah pengguna</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-sm text-left text-gray-600">
                    <thead class="text-gray-700 bg-gray-100 font-semibold text-center">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Unit</th>
                            <th class="px-4 py-3">Kode Unit</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Dibuat pada</th>
                            <th class="px-4 py-3">Diperbarui pada</th>
                            <th class="px-4 py-3">Dihapus pada</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($users as $data)
                            <tr class="hover:bg-gray-50 transition" data-id="{{ $data->id }}">
                                <td class="px-4 py-3 font-medium text-center">{{ $data->id }}</td>
                                <td class="px-4 py-3 font-medium">{{ $data->name }}</td>
                                <td class="px-4 py-3 font-medium">{{ $data->code }}</td>
                                <td class="px-4 py-3 font-medium text-center">{{ $data->role }}</td>
                                <td class="px-4 py-3 text-center">{{ $data?->created_at ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ $data?->updated_at ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ $data?->deleted_at ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if (!$data->deleted_at)
                                        <div class="flex justify-center space-x-2">
                                            <button
                                                class="border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-white p-2 rounded-lg"
                                                @click='selectedEditData = @json($data)'>
                                                <i class="fa-solid fa-pen"></i>
                                            </button>

                                            <button
                                                class="border border-red-500 text-red-500 hover:bg-red-600 hover:text-white p-2 rounded-lg"
                                                @click="selectedId = {{ $data->id }}">
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
                    <select name="per_page" @change="submitPerPage($event.target.value)"
                        class="border border-blue-900 rounded-md text-gray-700 px-2 py-1 focus:ring-1 focus:ring-blue-500">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                    </select>
                    <span>item</span>
                </div>

                <div class="flex space-x-1">
                    @if ($users->onFirstPage())
                        <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&lt;</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"
                            class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                            &lt;
                        </a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
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

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"
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

    </div>
@endsection
