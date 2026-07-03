<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmed;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::withCount('items')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(Order::STATUSES)),
        ]);

        $previousStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        // On envoie l'e-mail de confirmation uniquement au moment où la commande
        // BASCULE vers "confirmée" (pas si elle l'était déjà) et seulement si le
        // client a renseigné une adresse email lors de sa commande.
        $justConfirmed = $newStatus === 'confirmee' && $previousStatus !== 'confirmee';

        if ($justConfirmed && $order->customer_email) {
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmed($order));
                return back()->with('success', 'Commande confirmée. Un e-mail a été envoyé à '.$order->customer_email.'.');
            } catch (\Throwable $e) {
                report($e);
                return back()->with('success', 'Commande confirmée, mais l\'e-mail n\'a pas pu être envoyé (vérifiez la configuration mail).');
            }
        }

        if ($justConfirmed && ! $order->customer_email) {
            return back()->with('success', 'Commande confirmée. Le client n\'a pas renseigné d\'e-mail, aucune notification envoyée.');
        }

        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}