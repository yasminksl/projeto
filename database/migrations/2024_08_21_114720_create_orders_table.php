<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // id do cliente
            $table->decimal('total_amount', 8, 2); // total
            $table->decimal('amount_paid', 8, 2)->nullable(); // valor pago
            $table->string('payment_method')->nullable(); // forma de pagamento
            $table->string('status'); // status da entrega
            $table->date('scheduled_delivery_date')->nullable(); // data de agendamento
            $table->date('actual_delivery_date')->nullable(); // data de conclusão
            $table->decimal('discount', 8, 2)->default(0); // desconto
            $table->decimal('interest', 8, 2)->default(0); // juros
            $table->text('comments')->nullable(); // comentários
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
