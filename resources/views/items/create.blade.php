@extends('layouts.app')

@section('title', 'Masukan Barang | SIMBARA')

@section('content')
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
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="font-semibold text-blue-900 text-lg">Masukan Barang</h2>
            <p class="font-light text-gray-400 text-sm">Masukan detail barang yang diterima</p>
        </div>

        @include('components.header')
    </div>
    <div x-data='{ requestsOpen: false, selectedRequest: null, searchTerm: "", requests: @json($itemRequests) }'
        class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">

        <template x-if="requestsOpen">
            <div x-show="requestsOpen"
                class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4"
                x-transition @click.self="requestsOpen=false">
                <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-3xl rounded-xl md:rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 relative"
                    x-transition.opacity x-transition.scale>
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 text-center md:text-left">Pilih Usulan
                        Barang</h2>
                    <div class="mb-4">
                        <input type="text" placeholder="Cari usulan" x-model="searchTerm"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base">
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                        <template
                            x-for="r in requests.filter(rr => rr.name.toLowerCase().includes(searchTerm.toLowerCase()) || rr.detail.toLowerCase().includes(searchTerm.toLowerCase()))"
                            :key="r.id">
                            <button type="button" class="w-full text-left px-3 py-3 hover:bg-gray-50"
                                @click="selectedRequest = r; requestsOpen=false">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-900" x-text="r.name"></span>
                                    <span class="text-xs text-gray-500">Qty: <span x-text="r.qty"></span></span>
                                </div>
                                <div class="text-xs text-gray-500">Jenis: <span x-text="r.type?.name"></span> • Spesifikasi:
                                    <span x-text="r.detail"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                    <div class="flex justify-center gap-3 pt-4">
                        <button type="button"
                            class="px-6 py-2 rounded-lg border border-blue-900 text-gray-700 hover:bg-gray-200"
                            @click="requestsOpen=false">Tutup</button>
                    </div>
                </div>
            </div>
        </template>

        <form x-ref="itemForm" method="POST" :action="`{{ url('items') }}/${selectedRequest.id}`">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kode Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" placeholder="Contoh: BRG-2025-001" value="{{ old('code') }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('code') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-blue-900' }}"
                        required>
                    @error('code')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity" placeholder="Contoh: 10" min="1"
                        value="{{ old('quantity') }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('quantity') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-blue-900' }}"
                        required>
                    @error('quantity')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" placeholder="Contoh: Laptop Dell Inspiron"
                        value="{{ old('name') }}" x-bind:value="selectedRequest?.name ?? ''"
                        class="w-full border rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-blue-900' }}"
                        required>
                    @error('name')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="type_id" x-bind:value="selectedRequest?.type_id ?? ''"
                        class="w-full border rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('type_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}"
                        required>
                        <option value="" disabled selected>Pilih jenis barang</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('type_id')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nilai BMN <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="cost" placeholder="Contoh: 15000000" value="{{ old('cost') }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('cost') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-blue-900' }}"
                        required>
                    @error('cost')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Perolehan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="acquisition_date" placeholder="Pilih tanggal"
                        value="{{ old('acquisition_date') }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('acquisition_date') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-blue-900' }}"
                        required>
                    @error('acquisition_date')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tahun Perolehan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="1" name="acquisition_year" value="{{ old('acquisition_year') }}"
                        placeholder="Contoh: 2025"
                        class="w-full border rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('acquisition_year') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-blue-900' }}"
                        required>
                    @error('acquisition_year')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>


            </div>
            <div class="flex flex-col md:flex-row justify-end gap-3 border-t pt-4">
                <button type="button"
                    class="w-full md:w-auto px-6 py-2 rounded-lg border border-blue-900 text-gray-700 hover:bg-gray-200"
                    @click="requestsOpen=true">Pilih dari Usulan</button>
                <button type="submit" :disabled="!selectedRequest"
                    class="w-full md:w-auto px-6 py-2 rounded-lg bg-blue-900 text-white hover:bg-amber-400">
                    Masukan Barang
                </button>
            </div>
        </form>
    </div>
@endsection
