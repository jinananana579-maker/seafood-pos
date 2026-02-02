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
    public function up()
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('shop_name')->nullable();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->timestamps();
    });
    
    // បង្កើតទិន្នន័យលំនាំដើមមួយភ្លាមៗ
    DB::table('settings')->insert([
        'shop_name' => 'KH-SHOP (Seafood)',
        'phone' => '012 345 678',
        'address' => 'ផ្លូវ ២០០៤, ភ្នំពេញ',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
