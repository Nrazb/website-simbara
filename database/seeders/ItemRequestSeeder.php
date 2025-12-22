<?php

namespace Database\Seeders;

use App\Models\ItemRequest;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemRequestSeeder extends Seeder
{
    public function run(): void
    {
        $medical = Type::firstOrCreate(['name' => 'Perangkat Medis']);
        $imaging = Type::firstOrCreate(['name' => 'Peralatan Pencitraan']);
        $it = Type::firstOrCreate(['name' => 'Peralatan TI']);
        $network = Type::firstOrCreate(['name' => 'Peralatan Jaringan']);

        $kp = User::where('code', 'KEPERAWATAN')->first();
        $upa = User::where('code', 'UPATIK')->first();
        $rpl = User::where('code', 'RPLUNIT')->first();
        $ti = User::where('code', 'TIUNIT')->first();

        $requests = [
            [
                'user_id' => $kp?->id,
                'type_id' => $medical->id,
                'name' => 'Infusion Pump',
                'detail' => 'Pompa infus volumetrik untuk kebutuhan bangsal',
                'qty' => 2,
                'reason' => 'Perangkat infus yang saat ini digunakan telah melewati masa pakai yang ideal dan sering mengalami gangguan saat jam sibuk. Penggantian diperlukan untuk meningkatkan keselamatan pasien dan memastikan prosedur terapi infus berjalan tanpa jeda layanan.',
                'sent_at' => null,
            ],
            [
                'user_id' => $kp?->id,
                'type_id' => $imaging->id,
                'name' => 'Portable Ultrasound',
                'detail' => 'Perangkat USG portabel untuk pemeriksaan di samping tempat tidur',
                'qty' => 1,
                'reason' => 'Unit membutuhkan perangkat USG portabel untuk pemeriksaan cepat di ruang perawatan tanpa perlu memindahkan pasien ke ruang radiologi. Pengadaan ini diharapkan mempercepat diagnosis, mengurangi antrean, dan meningkatkan kualitas pelayanan klinis.',
                'sent_at' => now(),
            ],
            [
                'user_id' => $upa?->id,
                'type_id' => $it->id,
                'name' => 'Server SSD 2TB',
                'detail' => 'SSD NVMe untuk penyimpanan virtualisasi',
                'qty' => 4,
                'reason' => 'Pertumbuhan beban kerja virtualisasi membuat kapasitas penyimpanan saat ini tidak lagi mencukupi. Penambahan SSD NVMe berkecepatan tinggi akan menurunkan latensi, meningkatkan reliabilitas, dan mendukung skala layanan internal yang terus bertambah.',
                'sent_at' => null,
            ],
            [
                'user_id' => $upa?->id,
                'type_id' => $network->id,
                'name' => 'Network Switch 24-Port',
                'detail' => 'Switch Gigabit 24-port terkelola untuk peningkatan laboratorium',
                'qty' => 3,
                'reason' => 'Laboratorium sering mengalami bottleneck pada port jaringan terutama saat praktikum bersamaan. Penambahan switch terkelola akan memperluas jumlah port, memudahkan segmentasi VLAN, dan menstabilkan performa jaringan untuk banyak perangkat sekaligus.',
                'sent_at' => now(),
            ],
            [
                'user_id' => $rpl?->id,
                'type_id' => $it->id,
                'name' => '3D Printing Filament',
                'detail' => 'Filamen PLA 1.75mm berbagai warna',
                'qty' => 20,
                'reason' => 'Kegiatan praktikum dan proyek akhir mahasiswa memerlukan banyak material prototyping. Penyediaan filamen PLA berbagai warna akan memperlancar proses desain dan iterasi, mengurangi waktu tunggu, serta meningkatkan mutu hasil model 3D.',
                'sent_at' => null,
            ],
            [
                'user_id' => $ti?->id,
                'type_id' => $it->id,
                'name' => 'Workstation GPU Upgrade',
                'detail' => 'NVIDIA RTX 4070 untuk pemrosesan data',
                'qty' => 2,
                'reason' => 'Komputasi intensif untuk pemrosesan data dan model memerlukan GPU yang lebih mumpuni. Peningkatan GPU pada workstation akan mempercepat pipeline riset, menurunkan waktu eksekusi, dan meningkatkan produktivitas tim.',
                'sent_at' => null,
            ],
        ];

        foreach ($requests as $data) {
            if ($data['user_id']) {
                ItemRequest::create($data);
            }
        }
    }
}
