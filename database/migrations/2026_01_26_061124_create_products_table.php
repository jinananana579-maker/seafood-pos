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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');         // ឈ្មោះទំនិញ (ឧ. ក្តាមសេះ)
        $table->string('image')->nullable(); // រូបភាព
        $table->decimal('price', 10, 2); // តម្លៃ ($10.50)
        $table->integer('stock')->default(0); // ចំនួនក្នុងស្តុក
        $table->string('unit')->default('kg'); // ខ្នាត: kg, can, box, plate
        $table->string('category');     // ប្រភេទ: seafood, beer, vegetable
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
        Schema::dropIfExists('products');
    }
};
