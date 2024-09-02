<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'total_amount',
        'amount_paid',
        'payment_method',
        'status',
        'scheduled_delivery_date',
        'actual_delivery_date',
        'discount',
        'interest',
        'comments',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class, 'order_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function updateDeliveryStatus()
    {
        if (is_null($this->scheduled_delivery_date) && is_null($this->actual_delivery_date)) {
            $this->status = 'Entrega Não Agendada';
        } elseif (!is_null($this->scheduled_delivery_date) && is_null($this->actual_delivery_date)) {
            $this->status = 'Entrega Agendada';
        } elseif (is_null($this->scheduled_delivery_date) && !is_null($this->actual_delivery_date)) {
            $this->scheduled_delivery_date = $this->actual_delivery_date;
            $this->status = 'Concluído';
        } elseif (!is_null($this->scheduled_delivery_date) && !is_null($this->actual_delivery_date)) {
            $this->status = 'Concluído';
        }

        $this->save();
    }

    public function addHistory($description, $changedBy)
    {
        $this->histories()->create([
            'description' => $description,
            'changed_by' => $changedBy,
        ]);
    }

    public function calculateTotal()
    {
        $totalProducts = $this->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->price;
        });

        return $totalProducts - $this->discount + $this->interest;
    }
}
