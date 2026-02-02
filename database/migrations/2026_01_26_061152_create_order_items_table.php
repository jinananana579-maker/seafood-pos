<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade'); // ភ្ជាប់ទៅ Order
        $table->foreignId('product_id')->constrained(); // ភ្ជាប់ទៅ Product
        $table->integer('quantity');       // ចំនួនទិញ
        $table->decimal('unit_price', 10, 2); // តម្លៃលក់នៅពេលនោះ
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
