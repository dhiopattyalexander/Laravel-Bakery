<?php

namespace App\Support;

use App\Models\Bread;

class Keranjang
{
    private string $sessionKey = 'keranjang';

    public function semua(): array
    {
        return session()->get($this->sessionKey, []);
    }

    public function ada(): bool
    {
        return ! empty($this->semua());
    }

    public function jumlahItem(): int
    {
        return array_sum(array_map(static fn (array $item) => (int) $item['jumlah'], $this->semua()));
    }

    public function totalHarga(): int
    {
        return array_sum(array_map(static fn (array $item) => (int) $item['harga'] * (int) $item['jumlah'], $this->semua()));
    }

    public function tambah(Bread $bread, int $jumlah = 1): void
    {
        if ($bread->stock < 1) {
            return;
        }

        $items = $this->semua();
        $breadId = (string) $bread->id;
        $jumlah = max(1, $jumlah);

        if (isset($items[$breadId])) {
            $items[$breadId]['jumlah'] = min($bread->stock, (int) $items[$breadId]['jumlah'] + $jumlah);
        } else {
            $items[$breadId] = [
                'id' => $bread->id,
                'nama' => $bread->name,
                'harga' => (int) $bread->price,
                'gambar' => $bread->image_path,
                'stok' => (int) $bread->stock,
                'jumlah' => min($bread->stock, $jumlah),
            ];
        }

        $this->simpan($items);
    }

    public function tambahSatu(int $breadId): void
    {
        $items = $this->semua();
        $key = (string) $breadId;

        if (! isset($items[$key])) {
            return;
        }

        $stok = (int) ($items[$key]['stok'] ?? 0);
        $items[$key]['jumlah'] = min($stok, (int) $items[$key]['jumlah'] + 1);

        $this->simpan($items);
    }

    public function kurangi(int $breadId): void
    {
        $items = $this->semua();
        $key = (string) $breadId;

        if (! isset($items[$key])) {
            return;
        }

        $jumlahBaru = (int) $items[$key]['jumlah'] - 1;

        if ($jumlahBaru < 1) {
            unset($items[$key]);
        } else {
            $items[$key]['jumlah'] = $jumlahBaru;
        }

        $this->simpan($items);
    }

    public function hapus(int $breadId): void
    {
        $items = $this->semua();
        unset($items[(string) $breadId]);
        $this->simpan($items);
    }

    public function kosongkan(): void
    {
        session()->forget($this->sessionKey);
    }

    private function simpan(array $items): void
    {
        session()->put($this->sessionKey, $items);
    }
}