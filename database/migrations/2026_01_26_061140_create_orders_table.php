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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable(); // អ្នកលក់ (nullable សិនបើអត់ Login)
        $table->decimal('total_price', 10, 2);    // តម្លៃសរុប
        $table->decimal('received_amount', 10, 2);// លុយទទួលពីភ្ញៀវ
        $table->decimal('change_amount', 10, 2);  // លុយអាប់
        $table->string('payment_method')->default('cash'); // cash ឬ QR
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
        Schema::dropIfExists('orders');
    }
};
