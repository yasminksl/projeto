<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\OrderResource;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
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
                ->with('client') // Eager load client relation
                ->when(FacadesRequest::input('search'), function ($query, $search) {
                    $query->whereHas('client', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
                })
                ->where('status', '!=', 'Concluído')
                ->latest()
                ->paginate(10)
                ->withQueryString()),
            'filters' => FacadesRequest::only(['search']),
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
            'status' => 'required|string',
            'delivery_date' => 'nullable|date',
            'comments' => 'nullable|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric',
        ]);

        $mappedProducts = array_map(function ($product) {
            return [
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ];
        }, $attributes['products']);

        $order = Order::create([
            'client_id' => $attributes['client_id'],
            'total_amount' => $attributes['total_amount'],
            'status' => $attributes['status'],
            'delivery_date' => $attributes['delivery_date'],
            'comments' => $attributes['comments'],
        ]);

        foreach ($mappedProducts as $productData) {
            $order->products()->attach($productData['product_id'], [
                'quantity' => $productData['quantity'],
                'price' => $productData['price']
            ]);
        }

        sleep(2);

        return redirect('/orders/' . $order->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
    public function destroy(string $id)
    {
        //
    }
}
