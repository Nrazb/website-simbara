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
    <form method="POST" action="{{ route('mutation-item-requests.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <input type="hidden" name="from_user_id" value="{{ Auth::id() }}">
            <input type="hidden" name="maintenance_unit_id" id="maintenance_unit_id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Barang <span class="text-red-500">*</span>
                </label>
                <select name="item_id" id="item_select"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-900 focus:border-blue-900"
                    required>
                    <option disabled selected>-- Pilih Barang --</option>

                    @foreach ($items as $item)
                        <option value="{{ $item->id }}"
                            data-maintenance="{{ $item->maintenance_unit_id }}">
                            {{ $item->name }} ({{ $item->id }})
                        </option>
                    @endforeach

                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pindahkan ke Unit <span class="text-red-500">*</span>
                </label>
                <select name="to_user_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-900 focus:border-blue-900"
                    required>
                    <option disabled selected>-- Pilih Unit Tujuan --</option>

                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <input type="hidden" name="unit_confirmed" value="0">
            <input type="hidden" name="recipient_confirmed" value="0">

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

<script>
document.getElementById('item_select').addEventListener('change', function() {
    let selected = this.options[this.selectedIndex];
    document.getElementById('maintenance_unit_id').value =
        selected.getAttribute('data-maintenance');
});
</script>

@endsection
