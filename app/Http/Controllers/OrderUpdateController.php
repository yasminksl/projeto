<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderUpdateController extends Controller
{
    public function updateDates(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'scheduledDeliveryDate' => 'nullable|date',
            'actualDeliveryDate' => 'nullable|date',
        ]);

        $order = Order::findOrFail($request->order_id);

        $currentScheduledDeliveryDate = $order->scheduled_delivery_date ? Carbon::parse($order->scheduled_delivery_date) : null;
        $currentActualDeliveryDate = $order->actual_delivery_date ? Carbon::parse($order->actual_delivery_date) : null;

        $newScheduledDeliveryDate = $request->scheduledDeliveryDate ? Carbon::parse($request->scheduledDeliveryDate) : null;
        $newActualDeliveryDate = $request->actualDeliveryDate ? Carbon::parse($request->actualDeliveryDate) : null;

        $dateChanges = [];

        if ($currentScheduledDeliveryDate && $newScheduledDeliveryDate && $currentScheduledDeliveryDate->format('d/m/Y') !== $newScheduledDeliveryDate->format('d/m/Y')) {
            $dateChanges[] = "Data de entrega agendada: {$currentScheduledDeliveryDate->format('d/m/Y')} para {$newScheduledDeliveryDate->format('d/m/Y')}";
        }

        if ($currentActualDeliveryDate && $newActualDeliveryDate && $currentActualDeliveryDate->format('d/m/Y') !== $newActualDeliveryDate->format('d/m/Y')) {
            $dateChanges[] = "Data de entrega concluída: {$currentActualDeliveryDate->format('d/m/Y')} para {$newActualDeliveryDate->format('d/m/Y')}";
        }

        $order->scheduled_delivery_date = $request->scheduledDeliveryDate;
        $order->actual_delivery_date = $request->actualDeliveryDate;
        $order->save();

        foreach ($dateChanges as $change) {
            $order->addHistory($change, Auth::user()->name);
        }

        $order->updateDeliveryStatus();
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amountPaid' => 'required|numeric|min:0',
            'paymentMethod' => 'required|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        $currentAmountPaid = $order->amount_paid ?? 0;

        $paymentChange = "Valor pago: de R$ {$currentAmountPaid} para R$ " . ($currentAmountPaid + $request->amountPaid);
        $methodChange = "Método de pagamento: {$request->paymentMethod}";

        $order->amount_paid += $request->amountPaid;
        $order->payment_method = $request->paymentMethod;
        $order->save();

        $total = $order->calculateTotal();
        $order->update(['total_amount' => $total]);

        $order->addHistory("Pagamento atualizado: {$paymentChange}. {$methodChange}", Auth::user()->name);
    }

    public function updateOrderValues(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'discount' => 'nullable|numeric|min:0',
            'interest' => 'nullable|numeric|min:0',
            'amountPaid' => 'nullable|numeric|min:0',
        ]);

        $order = Order::findOrFail($request->order_id);

        $currentDiscount = $order->discount ?? 0;
        $currentInterest = $order->interest ?? 0;
        $currentAmountPaid = $order->amount_paid ?? 0;

        $changes = [];

        if ($request->filled('discount') && $request->discount != $currentDiscount) {
            $changes[] = "Desconto: de R$ {$currentDiscount} para R$ {$request->discount}";
            $order->discount = $request->discount;
        }

        if ($request->filled('interest') && $request->interest != $currentInterest) {
            $changes[] = "Juros: de R$ {$currentInterest} para R$ {$request->interest}";
            $order->interest = $request->interest;
        }

        if ($request->filled('amountPaid') && $request->amountPaid != $currentAmountPaid) {
            $changes[] = "Valor pago: de R$ {$currentAmountPaid} para R$ {$request->amountPaid}";
            $order->amount_paid = $request->amountPaid;
        }

        $order->save();

        $total = $order->calculateTotal();
        $order->update(['total_amount' => $total]);

        if (!empty($changes)) {
            $order->addHistory("Valores atualizados: " . implode('. ', $changes), Auth::user()->name);
        }
    }

    public function updateOrderClient(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'client_id' => 'required|exists:clients,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $newClient = Client::findOrFail($request->client_id);

        $currentClient = $order->client;
        $currentClientId = $order->client->id;

        if ($currentClientId !== $newClient->id) {
            $order->client_id = $newClient->id;
            $order->save();

            $order->addHistory("Cliente atualizado de {$currentClient->name} para {$newClient->name}.", Auth::user()->name);
        }
    }
}
