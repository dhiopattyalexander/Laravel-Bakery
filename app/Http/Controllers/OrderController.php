<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\UserProfile;
use App\Support\CheckoutKeranjangService;
use App\Support\Keranjang;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    private const STORE_OPEN_AT = '08:00';

    private const STORE_CLOSE_AT = '21:00';

    public function index()
    {
        $breads = DB::table('view_available_breads')->get();

        return view('orders.index', compact('breads'));
    }

    public function show(Order $order)
    {
        abort_if(auth()->id() !== $order->user_id, 403, 'Unauthorized');

        $orderDetails = DB::table('view_order_details')
            ->where('order_id', $order->id)
            ->get();

        $checkoutMeta = $this->getCheckoutMeta($order);

        return view('orders.show', compact('order', 'orderDetails', 'checkoutMeta'));
    }

    public function history(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $statusFilter = (string) $request->input('status', 'all');
        $hasMetaTable = Schema::hasTable('order_checkout_meta');

        $orders = Order::query()
            ->where('orders.user_id', auth()->id())
            ->with(['items.bread']);

        if ($hasMetaTable) {
            $orders->with('checkoutMeta');
        }

        if ($q !== '') {
            $orders->where(function ($query) use ($q): void {
                if (ctype_digit($q)) {
                    $query->orWhere('orders.id', (int) $q);
                }

                $query->orWhereHas('items.bread', function ($breadQuery) use ($q): void {
                    $breadQuery->where('name', 'like', '%' . $q . '%');
                });
            });
        }

        if ($statusFilter !== 'all') {
            $orders->where(function ($query) use ($statusFilter, $hasMetaTable): void {
                switch ($statusFilter) {
                    case 'pending':
                        $query->where('orders.status', 'Pending');

                        if ($hasMetaTable) {
                            $query->where(function ($metaState): void {
                                $metaState->whereDoesntHave('checkoutMeta')
                                    ->orWhereHas('checkoutMeta', function ($meta): void {
                                        $meta->whereNull('paid_at')->whereNull('expired_at');
                                    });
                            });
                        }
                        break;

                    case 'paid':
                        if ($hasMetaTable) {
                            $query->whereHas('checkoutMeta', function ($meta): void {
                                $meta->whereNotNull('paid_at');
                            });
                        } else {
                            $query->whereIn('orders.status', ['Processing', 'Completed']);
                        }
                        break;

                    case 'expired':
                        if ($hasMetaTable) {
                            $query->whereHas('checkoutMeta', function ($meta): void {
                                $meta->whereNotNull('expired_at');
                            });
                        } else {
                            $query->where('orders.status', 'Cancelled');
                        }
                        break;

                    case 'processing':
                        $query->where('orders.status', 'Processing');
                        break;

                    case 'completed':
                        $query->where('orders.status', 'Completed');
                        break;

                    case 'cancelled':
                        $query->where('orders.status', 'Cancelled');
                        break;
                }
            });
        }

        $orders = $orders
            ->orderByDesc('orders.created_at')
            ->get();

        return view('orders.history', compact('orders', 'q', 'statusFilter'));
    }

    public function checkoutPage(Keranjang $keranjang)
    {
        $items = $keranjang->semua();

        if (empty($items)) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Keranjang masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $totalHarga = $keranjang->totalHarga();
        $now = Carbon::now();
        $storeOpenAt = Carbon::createFromTimeString(self::STORE_OPEN_AT);
        $storeCloseAt = Carbon::createFromTimeString(self::STORE_CLOSE_AT);
        $pickupAvailable = $now->between($storeOpenAt, $storeCloseAt);

        $profile = UserProfile::firstOrCreate(
            ['user_id' => auth()->id()],
            ['address' => null, 'phone' => null, 'birth_date' => null, 'gender' => null]
        );

        $shippingAddress = $this->resolveShippingAddress(auth()->user());

        return view('orders.checkout', [
            'items' => $items,
            'totalHarga' => $totalHarga,
            'storeOpenAt' => self::STORE_OPEN_AT,
            'storeCloseAt' => self::STORE_CLOSE_AT,
            'pickupAvailable' => $pickupAvailable,
            'profile' => $profile,
            'shippingAddress' => $shippingAddress,
        ]);
    }

    public function updateCheckoutItem(Request $request, Keranjang $keranjang)
    {
        $validated = $request->validate([
            'bread_id' => ['required', 'integer'],
            'action' => ['required', 'in:increment,decrement,remove'],
        ]);

        $breadId = (int) $validated['bread_id'];
        $action = $validated['action'];

        if ($action === 'increment') {
            $keranjang->tambahSatu($breadId);
        }

        if ($action === 'decrement') {
            $keranjang->kurangi($breadId);
        }

        if ($action === 'remove') {
            $keranjang->hapus($breadId);
        }

        if (! $keranjang->ada()) {
            return redirect()
                ->route('orders.index')
                ->with('success', 'Keranjang sudah kosong. Silakan pilih produk lagi.');
        }

        return redirect()->route('checkout.page');
    }

    public function processCheckout(Request $request, CheckoutKeranjangService $checkoutKeranjangService, Keranjang $keranjang)
    {
        $validated = $request->validate([
            'delivery_method' => ['required', 'in:pickup,instant'],
            'pickup_time' => ['nullable', 'date_format:H:i'],
            'order_notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:qris'],
        ]);

        $items = $keranjang->semua();

        if (empty($items)) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Keranjang kosong. Tidak ada yang bisa di-checkout.');
        }

        $deliveryMethod = $validated['delivery_method'];
        $pickupTime = $validated['pickup_time'] ?? null;
        $shippingAddress = $this->resolveShippingAddress(auth()->user());

        if ($deliveryMethod === 'pickup') {
            if (! $this->isWithinStoreHours(Carbon::now()->format('H:i'))) {
                return back()->withInput()->withErrors([
                    'delivery_method' => 'Pickup sedang tidak tersedia di luar jam operasional toko.',
                ]);
            }

            if (empty($pickupTime) || ! $this->isWithinStoreHours($pickupTime)) {
                return back()->withInput()->withErrors([
                    'pickup_time' => 'Waktu pickup harus berada dalam jam operasional toko.',
                ]);
            }
        }

        if ($deliveryMethod === 'instant' && empty($shippingAddress['address'])) {
            return back()->withInput()->withErrors([
                'delivery_method' => 'Alamat pengiriman belum tersedia. Silakan isi alamat profil atau tambahkan alamat baru terlebih dahulu.',
            ]);
        }

        try {
            $newOrderId = $checkoutKeranjangService->proses(auth()->id(), $items);

            if ($newOrderId < 1) {
                return back()->withInput()->with('error', 'Checkout gagal diproses. Silakan coba lagi.');
            }

            $expiresAt = Carbon::now()->addMinutes(10);

            $meta = [
                'delivery_method' => $deliveryMethod,
                'pickup_time' => $pickupTime,
                'order_notes' => trim((string) ($validated['order_notes'] ?? '')),
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $shippingAddress['address'] ?? null,
                'payment_expires_at' => $expiresAt->toIso8601String(),
            ];

            $this->saveCheckoutMeta($newOrderId, $meta);
            session()->put('checkout_meta.' . $newOrderId, $meta);

            $keranjang->kosongkan();

            return redirect()->route('checkout.payment', $newOrderId);
        } catch (\Throwable $e) {
            $pesan = str_contains(strtolower($e->getMessage()), 'stok roti tidak mencukupi') || str_contains($e->getMessage(), '45000')
                ? 'Maaf, stok roti tidak mencukupi untuk pesanan ini.'
                : 'Transaksi gagal: ' . $e->getMessage();

            return back()->withInput()->with('error', $pesan);
        }
    }

    public function paymentPage(Order $order)
    {
        abort_if(auth()->id() !== $order->user_id, 403, 'Unauthorized');

        $checkoutMeta = $this->getCheckoutMeta($order);
        $expiresAtRaw = $checkoutMeta['payment_expires_at'] ?? Carbon::parse($order->created_at)->addMinutes(10)->toIso8601String();
        $expiresAt = Carbon::parse($expiresAtRaw);
        $isPaid = ! empty($checkoutMeta['paid_at']) || in_array($order->status, ['Processing', 'Completed'], true);
        $isExpired = Carbon::now()->greaterThan($expiresAt);

        if ($isExpired && ! $isPaid && $order->status === 'Pending') {
            $order->update(['status' => 'Cancelled']);
            $this->saveCheckoutMeta($order->id, array_merge($checkoutMeta, [
                'expired_at' => Carbon::now()->toIso8601String(),
            ]));
        }

        return view('orders.payment', [
            'order' => $order,
            'checkoutMeta' => $checkoutMeta,
            'paymentExpiresAt' => $expiresAt,
            'secondsLeft' => max(0, (int) Carbon::now()->diffInSeconds($expiresAt, false)),
            'isPaid' => $isPaid,
            'isExpired' => $isExpired,
        ]);
    }

    public function confirmPayment(Order $order)
    {
        abort_if(auth()->id() !== $order->user_id, 403, 'Unauthorized');

        $checkoutMeta = $this->getCheckoutMeta($order);
        $expiresAtRaw = $checkoutMeta['payment_expires_at'] ?? Carbon::parse($order->created_at)->addMinutes(10)->toIso8601String();
        $expiresAt = Carbon::parse($expiresAtRaw);

        if (Carbon::now()->greaterThan($expiresAt)) {
            if ($order->status === 'Pending') {
                $order->update(['status' => 'Cancelled']);
            }

            $this->saveCheckoutMeta($order->id, array_merge($checkoutMeta, [
                'expired_at' => Carbon::now()->toIso8601String(),
            ]));

            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'Waktu pembayaran QRIS sudah habis. Pesanan otomatis dibatalkan.');
        }

        if ($order->status === 'Pending') {
            $order->update([
                'status' => 'Processing',
            ]);
        }

        $this->saveCheckoutMeta($order->id, array_merge($checkoutMeta, [
            'paid_at' => Carbon::now()->toIso8601String(),
        ]));
        session()->put('checkout_meta.' . $order->id . '.paid_at', Carbon::now()->toIso8601String());

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Berhasil dibayar. Status pesanan sekarang Processing.');
    }

    private function isWithinStoreHours(string $time): bool
    {
        $parsed = Carbon::createFromFormat('H:i', $time);
        $openAt = Carbon::createFromTimeString(self::STORE_OPEN_AT);
        $closeAt = Carbon::createFromTimeString(self::STORE_CLOSE_AT);

        return $parsed->betweenIncluded($openAt, $closeAt);
    }

    private function getCheckoutMeta(Order $order): array
    {
        if (Schema::hasTable('order_checkout_meta')) {
            $metaRow = DB::table('order_checkout_meta')
                ->where('order_id', $order->id)
                ->first();

            if ($metaRow) {
                return [
                    'delivery_method' => $metaRow->delivery_method,
                    'pickup_time' => $metaRow->pickup_time,
                    'order_notes' => $metaRow->order_notes,
                    'payment_method' => $metaRow->payment_method,
                    'shipping_address' => $metaRow->shipping_address ?? null,
                    'payment_expires_at' => $metaRow->payment_expires_at,
                    'paid_at' => $metaRow->paid_at,
                    'expired_at' => $metaRow->expired_at,
                ];
            }
        }

        return session('checkout_meta.' . $order->id, []);
    }

    private function saveCheckoutMeta(int $orderId, array $meta): void
    {
        $this->ensureCheckoutMetaTable();

        DB::table('order_checkout_meta')->updateOrInsert(
            ['order_id' => $orderId],
            [
                'delivery_method' => $meta['delivery_method'] ?? 'instant',
                'pickup_time' => $meta['pickup_time'] ?? null,
                'order_notes' => $meta['order_notes'] ?? null,
                'payment_method' => $meta['payment_method'] ?? 'qris',
                'shipping_address' => $meta['shipping_address'] ?? null,
                'payment_expires_at' => $meta['payment_expires_at'] ?? null,
                'paid_at' => $meta['paid_at'] ?? null,
                'expired_at' => $meta['expired_at'] ?? null,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]
        );
    }

    private function ensureCheckoutMetaTable(): void
    {
        if (Schema::hasTable('order_checkout_meta')) {
            if (! Schema::hasColumn('order_checkout_meta', 'shipping_address')) {
                Schema::table('order_checkout_meta', function (Blueprint $table): void {
                    $table->text('shipping_address')->nullable()->after('order_notes');
                });
            }

            return;
        }

        Schema::create('order_checkout_meta', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->string('delivery_method', 20)->default('instant');
            $table->string('pickup_time', 5)->nullable();
            $table->text('order_notes')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('payment_method', 20)->default('qris');
            $table->timestamp('payment_expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    private function resolveShippingAddress(?User $user): array
    {
        if (! $user) {
            return [
                'label' => null,
                'recipient_name' => null,
                'phone' => null,
                'address' => null,
            ];
        }

        $primaryAddress = $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->first();

        if ($primaryAddress) {
            return [
                'label' => $primaryAddress->label,
                'recipient_name' => $primaryAddress->recipient_name,
                'phone' => $primaryAddress->phone,
                'address' => $primaryAddress->address,
            ];
        }

        $profile = $user->profile()->first();

        return [
            'label' => 'Alamat Profil',
            'recipient_name' => $user->name,
            'phone' => $profile?->phone,
            'address' => $profile?->address,
        ];
    }
}
