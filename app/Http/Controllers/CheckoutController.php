<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    /** Frais de livraison selon la zone (paramétrable). */
    protected array $deliveryFees = [
        'abidjan'   => 1500,
        'interieur' => 3000,
        'retrait'   => 0,
    ];

    public function index()
    {
        $items = $this->cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $subtotal = $this->cart->subtotal();
        $fees = $this->deliveryFees;

        return view('shop.checkout', compact('items', 'subtotal', 'fees'));
    }

    public function store(Request $request)
    {
        $items = $this->cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $validated = $request->validate([
            'customer_name'    => 'required|string|max:120',
            'customer_phone'   => 'required|string|max:30',
            'customer_email'   => 'nullable|email|max:160',
            'shipping_address' => 'required|string|max:500',
            'city'             => 'required|string|max:120',
            'delivery_method'  => 'required|in:abidjan,interieur,retrait',
            'payment_method'   => 'required|in:a_la_livraison,wave,orange_money',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $subtotal = $this->cart->subtotal();
        $deliveryFee = $this->deliveryFees[$validated['delivery_method']] ?? 0;
        $total = $subtotal + $deliveryFee;

        $order = DB::transaction(function () use ($validated, $items, $subtotal, $deliveryFee, $total) {
            $order = Order::create([
                'reference'        => Order::generateReference(),
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_email'   => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'city'             => $validated['city'],
                'delivery_method'  => $validated['delivery_method'],
                'delivery_fee'     => $deliveryFee,
                'payment_method'   => $validated['payment_method'],
                'status'           => 'en_attente',
                'subtotal'         => $subtotal,
                'total'            => $total,
                'notes'            => $validated['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = $item['product'];
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'unit_price'   => $product->price,
                    'quantity'     => $item['quantity'],
                    'line_total'   => $item['line_total'],
                ]);

                // Décrémente le stock
                $product->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.confirmation', $order->reference);
    }

    public function confirmation(string $reference)
    {
        $order = Order::with('items')->where('reference', $reference)->firstOrFail();

        return view('shop.confirmation', compact('order'));
    }
}
