<div id="edit-usulan"
  class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4 transition duration-200 ease-out">
  <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-3xl rounded-xl md:rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 relative transform scale-95 opacity-0 transition-all duration-200 ease-out
           max-h-[90vh] overflow-y-auto">
    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-6 text-center md:text-left">
      Edit Usulan
    </h2>

    <form id="editForm" method="POST" class="space-y-6">
      @csrf
      @method('PUT')
      <input type="hidden" name="user_id" value="{{ Auth::id() }}">
      <input type="hidden" name="id" id="edit_id">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
          <input type="text" name="name" id="edit_name"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang <span class="text-red-500">*</span></label>
          <select name="type_id" id="edit_type"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
            @foreach ($types as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi <span class="text-red-500">*</span></label>
          <input type="text" name="detail" id="edit_detail"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
          <input type="number" name="qty" id="edit_qty"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan <span class="text-red-500">*</span></label>
        <textarea name="reason" id="edit_reason" rows="3"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm md:text-base focus:ring-blue-900 focus:border-blue-900" required></textarea>
      </div>

      <div class="flex flex-col md:flex-row justify-center gap-3 border-t pt-4">
        <button type="button" id="closeModal"
          class="w-full md:w-auto px-6 py-2 rounded-lg border border-blue-900 text-gray-700 hover:bg-gray-200">
          Batal
        </button>
        <button type="submit"
          class="w-full md:w-auto px-6 py-2 rounded-lg bg-blue-900 text-white hover:bg-amber-400">
          Update
        </button>
      </div>
    </form>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('edit-usulan');
        const modalContent = modal.querySelector('div.bg-white');
        const closeBtn = modal.querySelector('#closeModal');
        const editForm = modal.querySelector('#editForm');

    document.querySelectorAll('button[data-modal-target="edit-usulan"]').forEach(button => {
        button.addEventListener('click', () => {
        const row = button.closest('tr');
        const id = row.dataset.id;
        const name = row.querySelector('td:nth-child(1)').innerText.trim();
        const detail = row.querySelector('td:nth-child(2)').innerText.trim();
        const type = row.querySelector('td:nth-child(3)').dataset.typeId;
        const qty = row.querySelector('td:nth-child(4)').innerText.trim();
        const reason = row.querySelector('td:nth-child(5)').innerText.trim();

        editForm.action = `/item-requests/${id}`;
        editForm.querySelector('#edit_id').value = id;
        editForm.querySelector('#edit_name').value = name;
        editForm.querySelector('#edit_type').value = type;
        editForm.querySelector('#edit_detail').value = detail;
        editForm.querySelector('#edit_qty').value = qty;
        editForm.querySelector('#edit_reason').value = reason;

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
