<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ItemsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $code = $this->pick($row, ['code', 'kode', 'kode_barang']);
        $orderNumber = $this->pick($row, ['order_number', 'nup']);
        $name = $this->pick($row, ['name', 'nama_barang', 'nama_item', 'item_name']);

        if ($code === null && $orderNumber === null && $name === null) {
            return null;
        }

        $userId = $this->resolveUserId($row);
        $typeId = $this->resolveTypeId($row);

        if ($userId === null || $userId === '') {
            throw new \Exception('Kolom Unit wajib diisi.');
        }
        if ($typeId === null || $typeId === '') {
            throw new \Exception('Kolom Jenis Barang wajib diisi.');
        }

        $costRaw = $this->pick($row, ['cost', 'harga', 'biaya']);
        $cost = $this->normalizeInt($costRaw);
        $acquisitionDate = $this->normalizeDate($this->pick($row, ['acquisition_date', 'tanggal_perolehan']));
        $acquisitionYear = $this->pick($row, ['acquisition_year', 'tahun_perolehan']);
        if ($acquisitionYear === null && $acquisitionDate !== null) {
            $acquisitionYear = Carbon::parse($acquisitionDate)->year;
        }

        if ($code === null || $code === '') {
            throw new \Exception('Kolom Kode Barang wajib diisi.');
        }
        if ($orderNumber === null || $orderNumber === '') {
            throw new \Exception('Kolom NUP wajib diisi.');
        }
        if ($name === null || $name === '') {
            throw new \Exception('Kolom Nama Barang wajib diisi.');
        }
        if ($cost === null) {
            throw new \Exception('Kolom Harga wajib diisi.');
        }
        if ($acquisitionDate === null) {
            throw new \Exception('Kolom Tanggal Perolehan wajib diisi.');
        }
        if ($acquisitionYear === null || $acquisitionYear === '') {
            throw new \Exception('Kolom Tahun Perolehan wajib diisi.');
        }

        $status = strtoupper((string) ($this->pick($row, ['status', 'status_barang']) ?? 'AVAILABLE'));
        if (! in_array($status, ['AVAILABLE', 'BORROWED'], true)) {
            $status = 'AVAILABLE';
        }

        $id = $this->pick($row, ['id', 'id_barang', 'id_item']);
        if (($id === null || $id === '') && $code !== null && $orderNumber !== null) {
            $id = (string) $code . '-' . (string) $orderNumber;
        }

        return new Item([
            'id' => $id,
            'user_id' => $userId,
            'type_id' => $typeId,
            'code' => $code,
            'order_number' => $orderNumber,
            'name' => $name,
            'cost' => $cost,
            'acquisition_date' => $acquisitionDate,
            'acquisition_year' => $acquisitionYear,
            'status' => $status,
        ]);
    }

    private function pick(array $row, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
                return $row[$key];
            }
        }

        return null;
    }

    private function resolveUserId(array $row)
    {
        $userId = $this->pick($row, ['user_id', 'id_user']);
        if ($userId !== null && $userId !== '') {
            return $userId;
        }

        $unitName = $this->pick($row, ['unit', 'user', 'nama_unit']);
        if ($unitName === null || $unitName === '') {
            return null;
        }

        $user = User::where('name', $unitName)->first();
        if (! $user) {
            throw new \Exception('Unit tidak ditemukan: ' . $unitName);
        }

        return $user->id;
    }

    private function resolveTypeId(array $row)
    {
        $typeId = $this->pick($row, ['type_id', 'id_jenis']);
        if ($typeId !== null && $typeId !== '') {
            return $typeId;
        }

        $typeName = $this->pick($row, ['type', 'jenis', 'jenis_barang']);
        if ($typeName === null || $typeName === '') {
            return null;
        }

        $type = Type::where('name', $typeName)->first();
        if (! $type) {
            throw new \Exception('Jenis barang tidak ditemukan: ' . $typeName);
        }

        return $type->id;
    }

    private function normalizeDate($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        return Carbon::parse((string) $value)->format('Y-m-d');
    }

    private function normalizeInt($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        $clean = preg_replace('/[^0-9\-]/', '', (string) $value);
        if ($clean === null || $clean === '' || $clean === '-') {
            return null;
        }

        return (int) $clean;
    }
}
