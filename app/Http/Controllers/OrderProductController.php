<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderProductController extends Controller
{
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
}
