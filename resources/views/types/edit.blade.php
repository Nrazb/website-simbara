<div id="edit-type" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4 transition duration-200 ease-out">
    <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-3xl rounded-xl md:rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 relative transform scale-95 opacity-0 transition-all duration-200 ease-out
           max-h-[90vh] overflow-y-auto">
    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-6 text-center md:text-left">
      Edit Jenis Barang
    </h2>

    <form id="editForm" method="POST" class="space-y-6">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit_id">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Nama Jenis <span class="text-red-500">*</span>
          </label>
          <input id="edit_name" type="text" name="name" placeholder="Masukan jenis barang baru"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
        </div>

        <div class="flex flex-col md:flex-row justify-center gap-3 border-t pt-4">
            <button type="button" id="closeModal"
            class="w-full md:w-auto px-6 py-2 rounded-lg border border-blue-900 text-gray-700 hover:bg-gray-200">
            Batal
            </button>
            <button type="submit"
            class="w-full md:w-auto px-6 py-2 rounded-lg bg-blue-900 text-white hover:bg-amber-400">
            Perbarui
            </button>
        </div>
    </form>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('edit-type');
        const modalContent = modal.querySelector('div.bg-white');
        const closeBtn = modal.querySelector('#closeModal');
        const editForm = modal.querySelector('#editForm');

        document.querySelectorAll('button[data-modal-target="edit-type"]').forEach(button => {
            button.addEventListener('click', () => {
            const row = button.closest('tr');
            const id = row.dataset.id;
            const name = row.querySelector('td:nth-child(2)').innerText.trim();

            editForm.action = `/types/${id}`;
            editForm.querySelector('#edit_name').value = name;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            });
        });

        const closeModal = () => {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 150);
        };

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
        });
</script>
