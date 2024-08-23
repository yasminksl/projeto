<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'postal_code',
        'street_address',
        'address_number',
        'city',
        'neighborhood',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'client_id');
    }
}
