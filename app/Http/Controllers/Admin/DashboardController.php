<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products'       => Product::count(),
            'active'         => Product::active()->count(),
            'orders'         => Order::count(),
            'pending'        => Order::where('status', 'en_attente')->count(),
            'revenue'        => Order::whereIn('status', ['confirmee', 'expediee', 'livree'])->sum('total'),
            'low_stock'      => Product::where('stock', '<=', 3)->where('is_active', true)->count(),
        ];

        $recentOrders = Order::latest()->take(8)->get();
        $lowStock = Product::where('stock', '<=', 3)->where('is_active', true)->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStock'));
    }
}
