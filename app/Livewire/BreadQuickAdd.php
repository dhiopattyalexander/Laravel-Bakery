<?php

namespace App\Livewire;

use App\Models\Bread;
use App\Support\Keranjang;
use Livewire\Component;

class BreadQuickAdd extends Component
{
    public Bread $bread;

    public int $jumlah = 1;

    public function mount(Bread $bread): void
    {
        $this->bread = $bread->loadMissing('category');
        $this->jumlah = 1;
    }

    public function tambahJumlah(): void
    {
        if ($this->jumlah < $this->bread->stock) {
            $this->jumlah++;
        }
    }

    public function kurangiJumlah(): void
    {
        if ($this->jumlah > 1) {
            $this->jumlah--;
        }
    }

    public function tambahKeKeranjang(): void
    {
        if ($this->bread->stock < 1) {
            return;
        }

        app(Keranjang::class)->tambah($this->bread, $this->jumlah);
        session()->flash('success', 'Roti berhasil ditambahkan ke keranjang.');

        $this->dispatch('keranjang-diperbarui');
        $this->jumlah = 1;
    }

    public function render()
    {
        return view('livewire.bread-quick-add');
    }
}