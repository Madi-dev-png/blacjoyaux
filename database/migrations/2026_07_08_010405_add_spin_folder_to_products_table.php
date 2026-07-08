<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Chemin (relatif au disque "public") du dossier contenant les images de
            // rotation 360°, ex: "products/360/sac-lifestyle-joyaux-kaki". Null si le
            // produit n'a pas de vue 360° (cas de la grande majorité du catalogue).
            $table->string('spin_folder')->nullable()->after('gallery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('spin_folder');
        });
    }
};
