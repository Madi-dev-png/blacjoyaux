<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            // Informations produit
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('price'); // prix en F CFA (entier, pas de centimes)
            $table->unsignedInteger('stock')->default(0);
            $table->string('image')->nullable();          // image principale
            $table->json('gallery')->nullable();          // images supplémentaires
            $table->string('color')->nullable();
            $table->string('material')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            // Champs SEO (générés automatiquement par le formulaire admin)
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->unsignedTinyInteger('seo_score')->default(0); // indicateur qualité 0-100

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
