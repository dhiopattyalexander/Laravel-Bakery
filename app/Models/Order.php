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

            $wasActive = in_array($oldStatus, ['Processing', 'Completed'], true);
            $isActive = in_array($newStatus, ['Processing', 'Completed'], true);

            if (! $wasActive && $isActive) {
                foreach ($order->items as $item) {
                    if ($item->bread) {
                        $item->bread->decrement('stock', $item->quantity);
                    }
                }
            } elseif ($wasActive && ! $isActive) {
                foreach ($order->items as $item) {
                    if ($item->bread) {
                        $item->bread->increment('stock', $item->quantity);
                    }
                }
            }
        });

        static::deleted(function ($order) {
            $wasActive = in_array($order->status, ['Processing', 'Completed'], true);
            if ($wasActive) {
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