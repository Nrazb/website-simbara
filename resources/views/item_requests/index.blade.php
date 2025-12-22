@extends('layouts.app')

@section('title', 'Usulan Barang Baru | SIMBARA')

@section('content')
    <div x-data="{
        createOpen: false,
        selectedId: null,
        selectedEditData: null,
        showData: null,
        submitPerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }
    }" class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-blue-900 text-lg">Usulan Barang Baru</h2>
                <p class="font-light text-gray-400 text-sm">Catat dan kirim usulan barang yang dibutuhkan unit anda</p>
            </div>

            @include('components.header')
        </div>
        <div class="flex flex-col gap-4 bg-white rounded-2xl p-3">
            <form method="GET" class="flex flex-col gap-2" x-ref="filterForm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <label class="text-gray-500 text-sm font-medium">Cari</label>
                        <div
                            class="flex items-center max-w-xs border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                            <i class="fa-solid fa-magnifying-glass text-gray-300 pl-2"></i>
                            <input type="text" name="search" placeholder="Cari usulan barang"
                                value="{{ request('search') }}" class="w-full p-2 rounded-lg text-sm outline-none">
                        </div>
                    </div>

                    <button type="button" @click="createOpen = true"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:border border-blue-900 hover:bg-white text-white hover:text-blue-900
                       rounded-lg text-sm font-medium shadow-sm transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Usulan</span>
                    </button>
                </div>

                <div class="flex justify-between items-center gap-4">
                    <div class="flex flex-col">
                        <label class="text-gray-500 text-sm font-medium">Saring</label>
                        <div class="flex items-center gap-4">
                            @if (auth()->user()->role === 'ADMIN')
                                <div class="relative min-w-[220px]">
                                    <i
                                        class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2
                                  text-gray-400 text-sm"></i>
                                    <select name="user_id" @change="$refs.filterForm.submit()"
                                        class="w-full border rounded-lg pl-9 pr-8 py-2 bg_white shadow-sm text-sm
                                   focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition">
                                        <option value="">Semua User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="relative min-w-[150px]">
                                <i
                                    class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <select name="year" x-ref="year"
                                    @change="$refs.start && ($refs.start.value=''); $refs.end && ($refs.end.value=''); $refs.filterForm.submit()"
                                    class="border rounded-lg pl-9 pr-8 py-2 bg-white shadow-sm
                                focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition w-full text-sm">
                                    <option value="">Semua Tahun</option>

                                    @foreach ($years as $y)
                                        <option value="{{ $y->year }}"
                                            {{ request('year') == $y->year ? 'selected' : '' }}>
                                            {{ $y->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="relative min-w-[150px]">
                                <select name="status" @change="$refs.filterForm.submit()"
                                    class="border rounded-lg px-3 py-2 bg-white shadow_sm text-sm focus:ring-2 focus:ring-blue-400">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft
                                    </option>
                                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim
                                    </option>
                                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Dihapus
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <label class="text-gray-500 text-sm font-medium">Rentang Tanggal</label>
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <input type="date" name="start" x-ref="start" value="{{ request('start') }}"
                                        @change="$refs.year && ($refs.year.value=''); $refs.filterForm.submit()"
                                        class="border rounded-lg px-3 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400" />
                                </div>
                                <span class="text-gray-500">-</span>
                                <div class="relative">
                                    <input type="date" name="end" x-ref="end" value="{{ request('end') }}"
                                        @change="$refs.year && ($refs.year.value=''); $refs.filterForm.submit()"
                                        class="border rounded-lg px-3 py-2 bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-400" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @if (request()->anyFilled(['user_id', 'year', 'status', 'start', 'end']))
                    <a type="button" href="{{ route('item-requests.index') }}"
                        class="flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-lg w-max
                       text-sm font-medium border border-gray-300 hover:bg-gray-100 shadow-sm transition">
                        <i class="fa-solid fa-rotate-left text-gray-500"></i>
                        Reset
                    </a>
                @endif
            </form>

            <div class="">
                <!-- Tampilan tabel di dekstop -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="table-fixed border-collapse text-sm text-left text-gray-600">
                        <thead class="text-gray-700 bg-gray-100 font-semibold">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Nama Barang</th>
                                <th class="px-4 py-3">Spesifikasi</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3">Kuantitas</th>
                                <th class="px-4 py-3">Alasan</th>
                                @if (auth()->user()->role === 'ADMIN')
                                    <th class="px-4 py-3">Unit</th>
                                @endif
                                <th class="px-4 py-3">Usulan Dibuat</th>
                                <th class="px-4 py-3">Usulan Dihapus</th>
                                <th class="px-4 py-3">Usulan Dikirim</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($itemRequests as $data)
                                <tr class="hover:bg-gray-50 transition {{ $data->trashed() ? 'text-red-600' : '' }}"
                                    data-id="{{ $data->id }}">
                                    <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $data->name }}</td>
                                    <td class="px-4 py-3">{{ $data->detail }}</td>
                                    <td class="px-4 py-3" data-type-id="{{ $data->type->id }}">{{ $data->type->name }}
                                    </td>
                                    <td class="px-4 py-3">{{ $data->qty }}</td>
                                    <td class="px-4 py-3 line-clamp-3">{{ $data->reason }}</td>
                                    @if (auth()->user()->role === 'ADMIN')
                                        <td class="px-4 py-3">{{ $data->user->name }}</td>
                                    @endif
                                    <td class="px-4 py-3">{{ $data->created_at }}</td>
                                    <td class="px-4 py-3">{{ $data->deleted_at ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $data->sent_at ?? '-' }}</td>

                                    <td class="px-4 py-3 text-center">
                                        @if (!$data->deleted_at)
                                            <div class="flex justify-center space-x-2">

                                                @if ($data->isSent())
                                                    <button onclick='openShowModal(@json($data))'
                                                        class="border border-blue-900 hover:bg-blue-900 text-blue-900 hover:text-white p-2 rounded-lg"
                                                        title="Lihat Detail">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                @endif

                                                @if ($data->isDraft())
                                                    <button type="button"
                                                        class="border border-purple-500 hover:bg-purple-500 text-purple-500 hover:text-white p-2 rounded-lg"
                                                        title="Kirim Usulan"
                                                        onclick="openSendModal({{ $data->id }})">
                                                        <i class="fa-solid fa-paper-plane"></i>
                                                    </button>
                                                @endif

                                                @if ($data->isDraft())
                                                    <button
                                                        class="border border-amber-500 hover:bg-amber-500 text-amber-500 hover:text-white p-2 rounded-lg"
                                                        @click='selectedEditData = @json($data)'
                                                        title="Edit">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>
                                                @endif

                                                @if ($data->isDraft())
                                                    <button
                                                        class="border border-red-500 hover:bg-red-500 text-red-500 hover:text-white p-2 rounded-lg"
                                                        @click="selectedId = {{ $data->id }}" title="Hapus">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Usulan ini sudah dihapus</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        @if ($data->isDraft())
                                            <span
                                                class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-lg">Draft</span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-lg">Terkirim</span>
                                        @endif
                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="10" class="p-3 text-gray-500 font-light italic text-center">
                                        Tidak ada data saat ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="block md:hidden space-y-3">
                    @forelse ($itemRequests as $data)
                        <div class="border border-gray-200 rounded-xl p-3 shadow-sm {{ $data->trashed() ? 'text-red-600' : '' }}"
                            data-id="{{ $data->id }}" data-id="{{ $data->id }}">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold">{{ $data->name }}</h3>
                                <p>
                                    @if ($data->isDraft())
                                        <span
                                            class="px-2 py-1 bg-yellow-100 text-yellow-700 text-medium rounded-lg">Draft</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-700 text-medium rounded-lg">Terkirim</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mt-2 text-sm space-y-1">
                                <p><span class="font-medium">Spesifikasi:</span> {{ $data->detail }}</p>
                                <p><span class="font-medium">Jenis:</span> {{ $data->type->name }}</p>
                                <p><span class="font-medium">Kuantitas:</span> {{ $data->qty }}</p>
                            </div>
                            <div class="flex justify-between space-x-2 mt-3">
                                <h3 class="font-semibold text-blue-900">{{ $data->user->name }}</h3>
                                @if (!$data->deleted_at)
                                    <div class="flex justify-center space-x-2">
                                        @if ($data->isDraft())
                                            <button type="button"
                                                class="border border-purple-500 hover:bg-purple-500 text-purple-500 hover:text-white p-2 rounded-lg"
                                                title="Kirim Usulan" onclick="openSendModal({{ $data->id }})">
                                                <i class="fa-solid fa-paper-plane"></i>
                                            </button>
                                        @endif

                                        @if ($data->isDraft())
                                            <button
                                                class="border border-amber-500 hover:bg-amber-500 text-amber-500 hover:text-white p-2 rounded-lg"
                                                @click='selectedEditData = @json($data)'>
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        @endif

                                        @if ($data->isDraft())
                                            <button
                                                class="border border-red-500 hover:bg-red-500 text-red-500 hover:text-white p-2 rounded-lg"
                                                @click="selectedId = {{ $data->id }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        @endif

                                        @if ($data->isSent())
                                            <button onclick='openShowModal(@json($data))'
                                                class="border border-blue-900 hover:bg-blue-900 text-blue-900 hover:text-white p-2 rounded-lg"
                                                title="Lihat Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Usulan ini sudah dihapus</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="mt-2 text-sm space-y-1 italic text-center">
                            <p><span class="text-gray-500 font-light">Tidak ada data saat ini</span></p>
                        </div>
                    @endforelse
                </div>

                <div
                    class="flex flex-col sm:flex-row justify-between items-center mt-4 text-xs sm:text-sm text-gray-500 gap-3">
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
                        @if ($itemRequests->onFirstPage())
                            <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&lt;</span>
                        @else
                            <a href="{{ $itemRequests->previousPageUrl() }}"
                                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                                &lt;
                            </a>
                        @endif

                        @foreach ($itemRequests->getUrlRange(1, $itemRequests->lastPage()) as $page => $url)
                            @if ($page == $itemRequests->currentPage())
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

                        @if ($itemRequests->hasMorePages())
                            <a href="{{ $itemRequests->nextPageUrl() }}"
                                class="px-2 sm:px-3 py-1 rounded-lg text-gray-600 hover:bg-gray-100">
                                &gt;
                            </a>
                        @else
                            <span class="px-2 sm:px-3 py-1 rounded-lg text-gray-400">&gt;</span>
                        @endif
                    </div>
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

        @include('components.item_requests.create')
        @include('components.item_requests.edit')
        @include('components.item_requests.delete')
    </div>

    <!-- MODAL SHOW (NO ALPINE) -->
    <div id="showModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">

        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">
            <button onclick="closeShowModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <h2 class="text-lg font-semibold text-blue-900 mb-3">Detail Usulan Barang</h2>

            <div class="space-y-2 text-sm">
                <p><strong>Nama Barang:</strong> <span id="show_name"></span></p>
                <p><strong>Spesifikasi:</strong> <span id="show_detail"></span></p>
                <p><strong>Kuantitas:</strong> <span id="show_qty"></span></p>
                <p><strong>Jenis:</strong> <span id="show_type"></span></p>
                <p><strong>Alasan:</strong> <span id="show_reason"></span></p>
                <p><strong>Diusulkan Oleh:</strong> <span id="show_user"></span></p>
                <p><strong>Tanggal Dibuat:</strong> <span id="show_created"></span></p>
                <p><strong>Tanggal Terkirim:</strong> <span id="show_sent"></span></p>
            </div>

            <div class="text-right mt-4">
                <button onclick="closeShowModal()"
                    class="px-4 py-2 bg-blue-900 hover:bg-amber-400 text-white rounded-lg text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <div id="sendConfirmModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">

        <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-6 relative">

            <h2 class="text-lg font-semibold text-blue-900 mb-3">
                Konfirmasi Pengiriman
            </h2>

            <p class="text-sm text-gray-600 mb-4">
                Apakah Anda yakin ingin mengirim usulan ini?
                Setelah dikirim, usulan tidak dapat diedit lagi.
            </p>

            <form id="sendForm" method="POST">
                @csrf
                @method('PATCH')

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeSendModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg text-sm">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 border border-blue-900 hover:bg-blue-900 text-blue-900 hover:text-white rounded-lg text-sm">
                        Ya, Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openSendModal(id) {
            const form = document.getElementById("sendForm");
            form.action = `/item-requests/${id}/send`;

            const modal = document.getElementById("sendConfirmModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }

        function closeSendModal() {
            const modal = document.getElementById("sendConfirmModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }

        function openShowModal(data) {
            document.getElementById("show_name").innerText = data.name;
            document.getElementById("show_detail").innerText = data.detail;
            document.getElementById("show_qty").innerText = data.qty;
            document.getElementById("show_type").innerText = data.type?.name ?? '-';
            document.getElementById("show_reason").innerText = data.reason;
            document.getElementById("show_user").innerText = data.user?.name ?? '-';
            document.getElementById("show_created").innerText = data.created_at;
            document.getElementById("show_sent").innerText = data.sent_at ?? '-';

            document.getElementById("showModal").classList.remove("hidden");
            document.getElementById("showModal").classList.add("flex");
        }

        function closeShowModal() {
            document.getElementById("showModal").classList.add("hidden");
            document.getElementById("showModal").classList.remove("flex");
        }
    </script>
@endsection
