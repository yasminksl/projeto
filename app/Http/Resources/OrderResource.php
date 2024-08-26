<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'client_name' => $this->client->name,
            'total_amount' => $this->total_amount,
            'amount_paid' => $this->amount_paid,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'scheduled_delivery_date' => $this->scheduled_delivery_date,
            'actual_delivery_date' => $this->actual_delivery_date,
            'comments' => $this->comments,
            'created_at' => $this->created_at,
        ];
    }
}
