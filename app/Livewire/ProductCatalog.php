<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bread;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $selectedCategory = null;

    public array $kuantitas = [];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function tambahJumlah(int $breadId): void
    {
        $bread = Bread::query()->findOrFail($breadId);
        $current = (int) ($this->kuantitas[$breadId] ?? 1);

        if ($current < $bread->stock) {
            $this->kuantitas[$breadId] = $current + 1;
        }
    }

    public function kurangiJumlah(int $breadId): void
    {
        $current = (int) ($this->kuantitas[$breadId] ?? 1);

        if ($current > 1) {
            $this->kuantitas[$breadId] = $current - 1;
        }
    }

    public function tambahKeKeranjang(int $breadId): void
    {
        $bread = Bread::query()->findOrFail($breadId);
        $jumlah = (int) ($this->kuantitas[$breadId] ?? 1);

        app(\App\Support\Keranjang::class)->tambah($bread, $jumlah);

        session()->flash('success', 'Roti berhasil ditambahkan ke keranjang.');
        $this->dispatch('keranjang-diperbarui');
        $this->kuantitas[$breadId] = 1;
    }

    public function render()
    {
        $breads = Bread::query()
            ->with('category')
            ->where('stock', '>', 0)
            ->when(filled($this->selectedCategory), function ($query) {
                $query->whereHas('category', function ($categoryQuery) {
                    $categoryQuery->where('name', $this->selectedCategory);
                });
            })
            ->when(trim($this->search) !== '', function ($query) {
                $query->where('name', 'like', '%' . trim($this->search) . '%');
            })
            ->orderByDesc('id')
            ->paginate(12);

        $categories = Category::query()
            ->leftJoin('breads', 'breads.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as name, COUNT(DISTINCT breads.name) as breads_count')
            ->groupBy('categories.name')
            ->orderBy('categories.name')
            ->get();

        return view('livewire.product-catalog', [
            'breads' => $breads,
            'categories' => $categories,
        ]);
    }
}