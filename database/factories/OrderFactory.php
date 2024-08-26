<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $client = Client::factory()->create();
        $status = $this->faker->randomElement(['Concluído', 'Entrega Agendada', 'Entrega Não Agendada']);

        return [
            'client_id' => $client->id,
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'amount_paid' => $this->faker->randomFloat(2, 0, 500),
            'payment_method' => $this->faker->randomElement(['Crédito', 'Débito', 'Dinheiro', 'Pix']),
            'status' => $status,
            'scheduled_delivery_date' => $status === 'Entrega Agendada' || $status === 'Concluído' ? $this->faker->date() : null,
            'actual_delivery_date' => $status === 'Concluído' ? $this->faker->date() : null,
            'discount' => $this->faker->randomFloat(2, 0, 50), // desconto fictício
            'interest' => $this->faker->randomFloat(2, 0, 50), // juros fictícios
            'comments' => $this->faker->optional()->text(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::inRandomOrder()->take(3)->get();

            foreach ($products as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => $this->faker->numberBetween(1, 5),
                    'price' => $product->price,
                ]);
            }
        });
    }
}
