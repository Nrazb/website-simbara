<div id="create-user" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4 transition duration-200 ease-out">
    <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-3xl rounded-xl md:rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 relative transform scale-95 opacity-0 transition-all duration-200 ease-out
           max-h-[90vh] overflow-y-auto">
    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-6 text-center md:text-left">
      Tambah pengguna baru
    </h2>

    <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
      @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Nama Unit <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" placeholder="Masukan nama unit"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Kode Unit <span class="text-red-500">*</span>
          </label>
          <input type="text" name="code" placeholder="Masukan kode unit"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Kata Sandi <span class="text-red-500">*</span>
          </label>
          <input type="password" name="password" placeholder="Masukan kata sandi"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
        </div>
         <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Role <span class="text-red-500">*</span>
            </label>
            <select name="role"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
                <option value="" disabled selected>Pilih role pengguna</option>
                <option value="ADMIN">Admin</option>
                <option value="UNIT">Unit</option>
                <option value="MAINTENANCE_UNIT">Unit Pemeliharaan</option>
            </select>
        </div>
        <input type="hidden" name="can_borrow" value="0">

        <div class="flex flex-col md:flex-row justify-center gap-3 border-t pt-4">
            <button type="button" id="closeModal"
            class="w-full md:w-auto px-6 py-2 rounded-lg border border-blue-900 text-gray-700 hover:bg-gray-200">
            Batal
            </button>
            <button type="submit"
            class="w-full md:w-auto px-6 py-2 rounded-lg bg-blue-900 text-white hover:bg-amber-400">
            Tambah
            </button>
        </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const openButtons = document.querySelectorAll('[data-modal-target]');

  openButtons.forEach(button => {
    const modalId = button.getAttribute('data-modal-target');
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const modalContent = modal.querySelector('div.bg-white');
    const closeBtn = modal.querySelector('#closeModal');

    const openModal = () => {
      modal.classList.remove('hidden');
      setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
      }, 10);
    };

    const closeModal = () => {
      modalContent.classList.remove('scale-100', 'opacity-100');
      modalContent.classList.add('scale-95', 'opacity-0');
      setTimeout(() => modal.classList.add('hidden'), 150);
    };

    button.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
  });
});
</script>
