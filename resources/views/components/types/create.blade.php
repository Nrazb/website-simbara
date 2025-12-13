<template x-if="createOpen">
    <div id="create-type" x-show="createOpen"
        class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4"
        x-transition @click.self="createOpen=false">
        <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-3xl rounded-xl md:rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 relative"
            x-transition.opacity x-transition.scale>
            <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-6 text-center md:text-left">
              Tambah Jenis Barang Baru
            </h2>

            <form method="POST" action="{{ route('types.store') }}" class="space-y-6">
              @csrf
              <input type="text" name="user_id" value="{{ Auth::id() }}" hidden>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Jenis <span class="text-red-500">*</span>
                  </label>
                  <input type="text" name="name" placeholder="Masukan jenis barang baru"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
                </div>

                <div class="flex flex-col md:flex-row justify-center gap-3 border-t pt-4">
                    <button type="button"
                    class="w-full md:w-auto px-6 py-2 rounded-lg border border-blue-900 text-gray-700 hover:bg-gray-200"
                    x-on:click="createOpen=false">Batal</button>
                    <button type="submit"
                    class="w-full md:w-auto px-6 py-2 rounded-lg bg-blue-900 text-white hover:bg-amber-400">
                    Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
