<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    // Buka gembok agar bisa insert data dari controller
    protected $guarded = [];

    // Relasi balik ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted()
    {
        static::updated(function ($order) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            if ($oldStatus !== 'Completed' && $newStatus === 'Completed') {
                foreach ($order->items as $item) {
                    if ($item->bread) {
                        $item->bread->decrement('stock', $item->quantity);
                    }
                }
            } elseif ($oldStatus === 'Completed' && $newStatus !== 'Completed') {
                foreach ($order->items as $item) {
                    if ($item->bread) {
                        $item->bread->increment('stock', $item->quantity);
                    }
                }
            }
        });

        static::deleted(function ($order) {
            if ($order->status === 'Completed') {
                foreach ($order->items as $item) {
                    if ($item->bread) {
                        $item->bread->increment('stock', $item->quantity);
                    }
                }
            }
        });
    }

    public function checkoutMeta(): HasOne
    {
        return $this->hasOne(OrderCheckoutMeta::class);
    }
}