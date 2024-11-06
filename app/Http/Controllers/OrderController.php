<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\OrderResource;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;

class OrderController extends Controller
{
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

    public function create()
    {
        return Inertia::render('Orders/Create', [
            'clients' => ClientResource::collection(Client::all()),
            'products' => Product::all()->map->only(['id', 'name', 'price'])->values(),
        ]);
    }

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

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect('/orders');
    }
}
