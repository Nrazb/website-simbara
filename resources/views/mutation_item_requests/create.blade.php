<!-- Modal Mutasi Barang -->
<div id="mutasi-modal"
    class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4 transition duration-200 ease-out">
    <div class="bg-white w-[90%] sm:w-[80%] md:w-full max-w-md rounded-xl shadow-lg p-6 relative transform scale-95 opacity-0 transition-all duration-200 ease-out">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Mutasi Barang</h2>

        <form id="mutasiForm" class="space-y-4">
            <!-- Pilih Barang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Barang</label>
                <select id="barang" name="barang" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Barang --</option>
                </select>
            </div>

            <!-- Asal Unit -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Asal Unit</label>
                <select id="asal_unit" name="asal_unit" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Asal Unit --</option>
                </select>
            </div>

            <!-- Tujuan Unit -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Unit</label>
                <select id="tujuan_unit" name="tujuan_unit" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Tujuan Unit --</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="closeMutasiModal"
                    class="px-5 py-2 rounded-lg border border-gray-400 hover:bg-gray-100 text-sm font-medium">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-blue-900 text-white hover:bg-amber-400 hover:text-black text-sm font-medium">
                    Simpan Mutasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === HARD CODE DATA BARANG & UNIT ===
    const barangList = [
        { id: 1, name: 'Laptop Dell Latitude 3420' },
        { id: 2, name: 'Printer Epson L3150' },
        { id: 3, name: 'Proyektor BenQ MX550' },
        { id: 4, name: 'Router TP-Link Archer C6' },
    ];

    const unitList = [
        { id: 1, name: 'Unit Keuangan' },
        { id: 2, name: 'Unit SDM' },
        { id: 3, name: 'Unit Akademik' },
        { id: 4, name: 'Unit Umum' },
    ];

    // === INISIALISASI MODAL ===
    const mutasiModal = document.getElementById('mutasi-modal');
    const mutasiModalContent = mutasiModal.querySelector('div.bg-white');
    const openMutasiModal = document.getElementById('openMutasiModal');
    const closeMutasiBtn = document.getElementById('closeMutasiModal');
    const form = document.getElementById('mutasiForm');

    // === ISI DROPDOWN BARANG & UNIT ===
    const barangSelect = document.getElementById('barang');
    const asalSelect = document.getElementById('asal_unit');
    const tujuanSelect = document.getElementById('tujuan_unit');

    barangList.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.name;
        barangSelect.appendChild(opt);
    });

    unitList.forEach(unit => {
        const opt1 = document.createElement('option');
        const opt2 = document.createElement('option');
        opt1.value = unit.id;
        opt1.textContent = unit.name;
        opt2.value = unit.id;
        opt2.textContent = unit.name;
        asalSelect.appendChild(opt1);
        tujuanSelect.appendChild(opt2);
    });

    // === BUKA MODAL ===
    openMutasiModal.addEventListener('click', () => {
        mutasiModal.classList.remove('hidden');
        setTimeout(() => {
            mutasiModalContent.classList.remove('scale-95', 'opacity-0');
            mutasiModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    });

    // === TUTUP MODAL ===
    const closeMutasiModal = () => {
        mutasiModalContent.classList.remove('scale-100', 'opacity-100');
        mutasiModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => mutasiModal.classList.add('hidden'), 150);
    };

    closeMutasiBtn.addEventListener('click', closeMutasiModal);
    mutasiModal.addEventListener('click', e => { if (e.target === mutasiModal) closeMutasiModal(); });

    // === SIMULASI SUBMIT FORM ===
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const barang = barangSelect.options[barangSelect.selectedIndex].text;
        const asal = asalSelect.options[asalSelect.selectedIndex].text;
        const tujuan = tujuanSelect.options[tujuanSelect.selectedIndex].text;
        const keterangan = document.getElementById('keterangan').value;

        alert(`✅ Mutasi Barang Tersimpan:\n\nBarang: ${barang}\nAsal Unit: ${asal}\nTujuan Unit: ${tujuan}\nKeterangan: ${keterangan || '-'}`);
        closeMutasiModal();
        form.reset();
    });
});
</script>
