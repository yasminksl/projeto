<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $sales = Order::selectRaw('SUM(total_amount) as total_sales, MONTH(created_at) as month')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $profit = Order::selectRaw('SUM(amount_paid) as total_profit, MONTH(created_at) as month')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $delinquentClients = Client::with(['orders' => function ($query) {
            $query->where('status', '!=', 'Concluído');
        }])
            ->get()
            ->filter(function ($client) {
                return $client->orders->isNotEmpty();
            })
            ->map(function ($client) {
                $outstandingBalance = $client->orders->sum(function ($order) {
                    return round($order->total_amount - $order->amount_paid);
                });

                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'outstanding_balance' => $outstandingBalance,
                ];
            });

        $totalOutstandingBalance  = $delinquentClients->sum('outstanding_balance');

        return Inertia::render('Dashboard', [
            'sales' => $sales,
            'profit' => $profit,
            'clients' => $delinquentClients,
            'total_outstanding_balance' => $totalOutstandingBalance,
        ]);
    }
}
