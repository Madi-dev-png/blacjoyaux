<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();   // ex: BJ-2026-0001

            // Coordonnées client (guest checkout — pas de compte requis)
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('shipping_address');
            $table->string('city')->default('Abidjan');

            // Livraison paramétrable
            $table->string('delivery_method')->default('abidjan'); // abidjan | interieur | retrait
            $table->unsignedInteger('delivery_fee')->default(0);

            // Paiement différé paramétrable (selon brief VF)
            $table->string('payment_method')->default('a_la_livraison'); // a_la_livraison | wave | orange_money
            $table->string('status')->default('en_attente'); // en_attente | confirmee | expediee | livree | annulee

            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('total');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
