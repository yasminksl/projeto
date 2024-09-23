<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\OrderResource;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Laravel\Prompts\Themes\Default\SelectPromptRenderer;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Orders/Index', [
            'orders' => OrderResource::collection(Order::query()
                ->with('client')
                ->when(FacadesRequest::input('client'), function ($query, $clientId) {
                    $query->where('client_id', $clientId);
                })
                ->when(FacadesRequest::input('status'), function ($query, $status) {
                    $query->where('status', $status);
                })
                ->when(FacadesRequest::input('start_scheduled_date') && FacadesRequest::input('end_scheduled_date'), function ($query) {
                    $startDate = Carbon::parse(FacadesRequest::input('start_scheduled_date'))->startOfDay();
                    $endDate = Carbon::parse(FacadesRequest::input('end_scheduled_date'))->endOfDay();
                    $query->whereBetween('scheduled_delivery_date', [$startDate, $endDate]);
                })
                ->when(FacadesRequest::input('start_actual_date') && FacadesRequest::input('end_actual_date'), function ($query) {
                    $startDate = Carbon::parse(FacadesRequest::input('start_actual_date'))->startOfDay();
                    $endDate = Carbon::parse(FacadesRequest::input('end_actual_date'))->endOfDay();
                    $query->whereBetween('actual_delivery_date', [$startDate, $endDate]);
                })
                ->when(FacadesRequest::input('start_created_at') && FacadesRequest::input('end_created_at'), function ($query) {
                    $startDate = Carbon::parse(FacadesRequest::input('start_created_at'))->startOfDay();
                    $endDate = Carbon::parse(FacadesRequest::input('end_created_at'))->endOfDay();
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->latest()
                ->paginate(10)
                ->withQueryString()),
            'filters' => FacadesRequest::only(['client', 'status', 'start_scheduled_date', 'end_scheduled_date', 'start_actual_date', 'end_actual_date', 'start_created_at', 'end_created_at']),
            'clients' => ClientResource::collection(Client::all()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Orders/Create', [
            'clients' => ClientResource::collection(Client::all()),
            'products' => Product::all()->map->only(['id', 'name', 'price'])->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'total_amount' => 'required|numeric',
            'status' => 'nullable|string',
            'scheduled_delivery_date' => 'nullable|date',
            'comments' => 'nullable|string',
            'products' => 'required|array',
            'discount' => 'nullable|numeric',
            'interest' => 'nullable|numeric',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric',
        ]);

        $order = Order::create([
            'client_id' => $attributes['client_id'],
            'total_amount' => $attributes['total_amount'],
            'status' => $attributes['status'],
            'scheduled_delivery_date' => $attributes['scheduled_delivery_date'],
            'comments' => $attributes['comments'],
            'discount' => $attributes['discount'] ?? 0,
            'interest' => $attributes['interest'] ?? 0,
        ]);

        foreach ($attributes['products'] as $product) {
            $order->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ]);
        }

        $total = $order->calculateTotal();
        $order->update(['total_amount' => $total]);

        return redirect('/orders/' . $order->id);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with('products', 'client', 'histories')->findOrFail($id);

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'orderHistories' => $order->histories,
            'products' => Product::all()->map->only(['id', 'name', 'price'])->values(),
            'clients' => ClientResource::collection(Client::all()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect('/orders');
    }

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

    public function updateProducts(Request $request)
    {
        $attributes = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric',
        ]);

        $order = Order::findOrFail($request->order_id);

        $syncData = [];
        $historyChanges = [];

        foreach ($attributes['products'] as $productData) {
            $product = Product::find($productData['id']);
            $syncData[$productData['id']] = [
                'quantity' => $productData['quantity'],
                'price' => $productData['price'],
            ];

            $historyChanges[] = "Produto: {$product->name}, Quantidade: {$productData['quantity']}, Preço Unitário: {$productData['price']}, Preço Total: " . ($productData['price'] * $productData['quantity']);
        }

        $order->products()->sync($syncData, false);

        $total = $order->calculateTotal();
        $order->update(['total_amount' => $total]);

        $historyText = implode('; ', $historyChanges);
        $order->addHistory("Produtos adicionados: $historyText", Auth::user()->name);
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

    public function updateProductQuantity(Request $request, Order $order, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $quantity = $validated['quantity'];

        $currentQuantity = $order->products()->where('product_id', $product->id)->first()->pivot->quantity;

        if ($quantity !== $currentQuantity) {
            $order->products()->updateExistingPivot($product->id, ['quantity' => $quantity]);

            $order->addHistory("Quantidade do produto {$product->name} atualizada de {$currentQuantity} para {$quantity}.", Auth::user()->name);
        }

        $total = $order->calculateTotal();
        $order->update(['total_amount' => $total]);
    }

    public function removeProduct(Order $order, Product $product)
    {
        $order->products()->detach($product->id);

        $order->addHistory("Produto {$product->name} removido.", Auth::user()->name);

        $total = $order->calculateTotal();
        $order->update(['total_amount' => $total]);
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
