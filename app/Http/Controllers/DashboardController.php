<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    private function calculatePercentageIncrease($currentValue, $previousValue)
    {
        return $previousValue > 0 ? (($currentValue - $previousValue) / $previousValue) * 100 : 0;
    }

    public function index()
    {
        // Total de Vendas por mês
        $sales = Order::selectRaw('SUM(total_amount) as total_sales, MONTH(created_at) as month')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Total de valor recebido no mês
        $profit = Order::selectRaw('SUM(amount_paid) as total_profit, MONTH(created_at) as month')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Clientes Inadimplentes
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

        // Status dos pedidos
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Aumento percentual de vendas
        $currentMonthSales = $sales->last()->total_sales ?? 0;
        $previousMonthSales = $sales->count() > 1 ? $sales[$sales->count() - 2]->total_sales : 0;
        $salesPercentageIncrease = $this->calculatePercentageIncrease($currentMonthSales, $previousMonthSales);

        // Aumento percentual de lucro
        $currentMonthProfit = $profit->last()->total_profit ?? 0;
        $previousMonthProfit = $profit->count() > 1 ? $profit[$profit->count() - 2]->total_profit : 0;
        $profitPercentageIncrease = $this->calculatePercentageIncrease($currentMonthProfit, $previousMonthProfit);

        // Pedidos concluídos por mês
        $completedOrders = Order::selectRaw('COUNT(*) as total, MONTH(created_at) as month')
            ->where('status', 'Concluído')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Aumento percentual de pedidos concluídos
        $currentMonthCompletedOrders = $completedOrders->last()->total ?? 0;
        $previousMonthCompletedOrders = $completedOrders->count() > 1 ? $completedOrders[$completedOrders->count() - 2]->total : 0;
        $completedOrdersPercentageIncrease = $this->calculatePercentageIncrease($currentMonthCompletedOrders, $previousMonthCompletedOrders);

        return Inertia::render('Dashboard', [
            'sales' => $sales,
            'profit' => $profit,
            'clients' => $delinquentClients,
            'totalOutstandingBalance' => $totalOutstandingBalance,
            'ordersByStatus' => $ordersByStatus,
            'salesPercentageIncrease' => $salesPercentageIncrease,
            'profitPercentageIncrease' => $profitPercentageIncrease,
            'completedOrdersPercentageIncrease' => $completedOrdersPercentageIncrease,
        ]);
    }
}
