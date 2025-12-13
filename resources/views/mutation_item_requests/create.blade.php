@extends('layouts.app')

@section('title', 'Usulan Mutasi Barang | SIMBARA')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="font-semibold text-blue-900 text-lg">Usulan Mutasi Barang</h2>
            <p class="font-light text-gray-400 text-sm">Ajukan perpindahan barang antar unit</p>
        </div>

        @include('components.header')
    </div>

    <div class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm">
        <form x-data method="POST" action="{{ route('mutation-item-requests.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="item_id" id="item_select"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('item_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}"
                        required>
                        <option disabled selected>-- Pilih Barang --</option>

                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} ({{ $item->id }})
                            </option>
                        @endforeach

                    </select>
                    @error('item_id')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Pindahkan ke Unit <span class="text-red-500">*</span>
                    </label>
                    <select name="to_user_id"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-blue-900 focus:border-blue-900 {{ $errors->has('to_user_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}"
                        required>
                        <option disabled selected>-- Pilih Unit Tujuan --</option>

                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('to_user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                    @error('to_user_id')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-end gap-3 border-t pt-4 mt-4">
                <a href="{{ route('mutation-item-requests.index') }}"
                    class="w-full md:w-auto px-6 py-2 rounded-lg border border-blue-900 text-gray-700 hover:bg-gray-200 text-center">
                    Kembali
                </a>

                <button type="submit"
                    class="w-full md:w-auto px-6 py-2 rounded-lg bg-blue-900 text-white hover:bg-amber-400">
                    Ajukan Mutasi
                </button>
            </div>


        </form>
    </div>


@endsection
