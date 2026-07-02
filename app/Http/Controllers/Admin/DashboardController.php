<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $paidStatuses = ['confirmee', 'expediee', 'livree'];

        $startThisMonth = now()->startOfMonth();
        $startLastMonth = now()->subMonthNoOverflow()->startOfMonth();
        $endLastMonth = now()->subMonthNoOverflow()->endOfMonth();

        $stats = [
            'products'  => Product::count(),
            'active'    => Product::active()->count(),
            'orders'    => Order::count(),
            'pending'   => Order::where('status', 'en_attente')->count(),
            'revenue'   => Order::whereIn('status', $paidStatuses)->sum('total'),
            'low_stock' => Product::where('stock', '<=', 3)->where('is_active', true)->count(),
        ];

        // Tendances simples : mois en cours vs mois précédent (commandes & chiffre d'affaires).
        $ordersThisMonth = Order::where('created_at', '>=', $startThisMonth)->count();
        $ordersLastMonth = Order::whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();
        $revenueThisMonth = Order::whereIn('status', $paidStatuses)->where('created_at', '>=', $startThisMonth)->sum('total');
        $revenueLastMonth = Order::whereIn('status', $paidStatuses)->whereBetween('created_at', [$startLastMonth, $endLastMonth])->sum('total');

        $trends = [
            'orders'  => $this->trend($ordersThisMonth, $ordersLastMonth),
            'revenue' => $this->trend($revenueThisMonth, $revenueLastMonth),
        ];

        $recentOrders = Order::latest()->take(8)->get();
        $lowStock = Product::where('stock', '<=', 3)->where('is_active', true)->take(5)->get();

        // Produits les plus vendus (quantité cumulée sur les commandes non annulées).
        $topProducts = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', '!=', 'annulee')
            ->select('order_items.product_id', 'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.line_total) as total_revenue'))
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_qty')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact('stats', 'trends', 'recentOrders', 'lowStock', 'topProducts'));
    }

    private function trend(int|float $current, int|float $previous): array
    {
        if ($previous <= 0) {
            return $current > 0 ? ['label' => 'Nouveau', 'direction' => 'up'] : ['label' => 'Stable', 'direction' => 'flat'];
        }

        $delta = round((($current - $previous) / $previous) * 100);

        if ($delta === 0) {
            return ['label' => 'Stable', 'direction' => 'flat'];
        }

        return [
            'label' => ($delta > 0 ? '+' : '').$delta.'%',
            'direction' => $delta > 0 ? 'up' : 'down',
        ];
    }
}