<?php

namespace App\Livewire;

use App\Support\Keranjang;
use Livewire\Attributes\On;
use Livewire\Component;

class FloatingCartPopup extends Component
{
    public bool $terbuka = false;

    public array $items = [];

    public int $jumlahItem = 0;

    public int $totalHarga = 0;

    public function mount(): void
    {
        $this->sinkronkanKeranjang();
    }

    #[On('keranjang-diperbarui')]
    public function sinkronkanKeranjang(): void
    {
        $keranjang = app(Keranjang::class);

        $this->items = $keranjang->semua();
        $this->jumlahItem = $keranjang->jumlahItem();
        $this->totalHarga = $keranjang->totalHarga();
    }

    public function toggle(): void
    {
        $this->terbuka = ! $this->terbuka;
    }

    public function tutup(): void
    {
        $this->terbuka = false;
    }

    public function tambah(int $breadId): void
    {
        app(Keranjang::class)->tambahSatu($breadId);
        $this->sinkronkanKeranjang();
        $this->dispatch('keranjang-diperbarui');
    }

    public function kurangi(int $breadId): void
    {
        app(Keranjang::class)->kurangi($breadId);
        $this->sinkronkanKeranjang();
        $this->dispatch('keranjang-diperbarui');
    }

    public function hapus(int $breadId): void
    {
        app(Keranjang::class)->hapus($breadId);
        $this->sinkronkanKeranjang();
        $this->dispatch('keranjang-diperbarui');
    }

    public function kosongkan(): void
    {
        app(Keranjang::class)->kosongkan();
        $this->sinkronkanKeranjang();
        $this->dispatch('keranjang-diperbarui');
    }

    public function checkout()
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan masuk terlebih dahulu sebelum checkout.');
        }

        if (! app(Keranjang::class)->ada()) {
            session()->flash('error', 'Keranjang masih kosong.');

            return null;
        }

        return redirect()->route('checkout.page');
    }

    public function render()
    {
        return view('livewire.floating-cart-popup');
    }
}