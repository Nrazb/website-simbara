<template x-if="selectedId">
    <div id="delete-user" x-show="selectedId"
        class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4"
        x-transition @click.self="selectedId=null">
        <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-md rounded-xl shadow-lg p-6 relative"
            x-transition.opacity x-transition.scale>
            <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Hapus Pengguna</h2>
            <p class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>

            <form id="deleteForm" method="POST" x-bind:action="'/users/' + selectedId">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <button type="button" id="closeDeleteModal" class="px-6 py-2 rounded-lg border border-gray-400 hover:bg-gray-100" x-on:click="selectedId=null">Batal</button>
                    <button type="submit" class="px-6 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</template>
