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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('market_id');
            $table->string('reference')->unique();
            $table->float('stock')->nullable();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->float('cost_price')->nullable();
            $table->float('sale_price')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('description')->nullable();
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
