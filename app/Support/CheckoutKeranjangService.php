<?php

namespace App\Support;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CheckoutKeranjangService
{
    public function proses(int $userId, array $items): int
    {
        if (empty($items)) {
            throw new RuntimeException('Keranjang Anda masih kosong.');
        }

        $newOrderId = 0;

        $totalHarga = array_sum(array_map(static function (array $item): int {
            return (int) $item['harga'] * (int) $item['jumlah'];
        }, $items));

        try {
            $newOrderId = DB::transaction(function () use ($userId, $totalHarga, $items): int {
                DB::statement('SET @new_order_id = 0');
                DB::statement('CALL sp_checkout_order_bulk(?, ?, @new_order_id)', [
                    $userId,
                    $totalHarga,
                ]);

                $orderId = (int) (DB::selectOne('SELECT @new_order_id AS new_order_id')->new_order_id ?? 0);

                if ($orderId < 1) {
                    throw new RuntimeException('Gagal membuat pesanan baru.');
                }

                foreach ($items as $item) {
                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'bread_id' => (int) $item['id'],
                        'quantity' => (int) $item['jumlah'],
                        'subtotal' => (int) $item['harga'] * (int) $item['jumlah'],
                    ]);
                }

                return $orderId;
            });
        } catch (QueryException $e) {
            // Fallback jika environment masih memakai SP lama (single-item checkout)
            // yang biasanya sudah mengelola transaksi di dalam SP.
            foreach ($items as $item) {
                $breadId = (int) $item['id'];
                $jumlah = (int) $item['jumlah'];

                DB::statement('CALL sp_checkout_order(?, ?, ?)', [$userId, $breadId, $jumlah]);

                $newOrderId = (int) (DB::selectOne('SELECT LAST_INSERT_ID() AS new_order_id')->new_order_id ?? 0);
            }

            if ($newOrderId < 1) {
                throw $e;
            }
        }

        return $newOrderId ?? 0;
    }
}