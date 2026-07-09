<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PromoCode;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    /** Frais de livraison selon la zone (paramétrable). */
    protected array $deliveryFees = [
        'abidjan' => 1500,
        'interieur' => 3000,
        'retrait' => 0,
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
        $promo = $this->cart->appliedPromo();
        $discount = $this->cart->discount();

        return view('shop.checkout', compact('items', 'subtotal', 'fees', 'promo', 'discount'));
    }

    public function store(Request $request)
    {
        $items = $this->cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:120',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:160',
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:120',
            'delivery_method' => 'required|in:abidjan,interieur,retrait',
            'payment_method' => 'required|in:a_la_livraison,wave,orange_money,mtn_momo',
            'notes' => 'nullable|string|max:1000',
        ]);

        $subtotal = $this->cart->subtotal();
        $deliveryFee = $this->deliveryFees[$validated['delivery_method']] ?? 0;
        $promoCode = $this->cart->appliedPromo()?->code;

        try {
            $order = DB::transaction(function () use ($validated, $items, $subtotal, $deliveryFee, $promoCode) {
                // On verrouille les lignes produits le temps de la commande
                // (SELECT ... FOR UPDATE) : si deux clientes commandent le même
                // sac au même instant, la seconde attend et voit le stock à jour.
                $locked = Product::whereIn('id', collect($items)->pluck('product.id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // Revalidation du code promo sous verrou (au cas où il aurait expiré,
                // atteint sa limite d'usage, ou été désactivé depuis l'ajout au panier).
                $discount = 0;
                $promo = null;
                if ($promoCode) {
                    $promo = PromoCode::where('code', $promoCode)->lockForUpdate()->first();
                    if ($promo && $promo->isUsableFor($subtotal)) {
                        $discount = $promo->discountFor($subtotal);
                    } else {
                        $promo = null;
                    }
                }
                $total = $subtotal + $deliveryFee - $discount;

                // Revalidation du stock au moment T (le panier peut être vieux).
                foreach ($items as $item) {
                    $product = $locked[$item['product']->id] ?? null;

                    if (! $product || ! $product->is_active) {
                        throw ValidationException::withMessages([
                            'cart' => "« {$item['product']->name} » n'est plus disponible à la vente.",
                        ]);
                    }

                    if ($product->stock < $item['quantity']) {
                        throw ValidationException::withMessages([
                            'cart' => "Stock insuffisant pour « {$product->name} » : il en reste {$product->stock}. Merci d'ajuster votre panier.",
                        ]);
                    }
                }

                $order = Order::create([
                    'reference' => Order::generateReference(),
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_email' => $validated['customer_email'] ?? null,
                    'shipping_address' => $validated['shipping_address'],
                    'city' => $validated['city'],
                    'delivery_method' => $validated['delivery_method'],
                    'delivery_fee' => $deliveryFee,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'en_attente',
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'notes' => $validated['notes'] ?? null,
                    'promo_code' => $promo?->code,
                    'discount' => $discount,
                ]);

                if ($promo) {
                    $promo->increment('used_count');
                }

                foreach ($items as $item) {
                    $product = $locked[$item['product']->id];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $product->price,
                        'quantity' => $item['quantity'],
                        'line_total' => $item['line_total'],
                    ]);

                    // Décrémente le stock (ne peut plus passer en négatif
                    // grâce au contrôle ci-dessus, sous verrou).
                    $product->decrement('stock', $item['quantity']);
                }

                return $order;
            });
        } catch (ValidationException $e) {
            return redirect()->route('cart.index')
                ->with('error', collect($e->errors())->flatten()->first());
        }

        $this->cart->clear();

        return redirect()->route('checkout.confirmation', $order->reference);
    }

    public function confirmation(string $reference)
    {
        $order = Order::with('items')->where('reference', $reference)->firstOrFail();

        return view('shop.confirmation', compact('order'));
    }
}
