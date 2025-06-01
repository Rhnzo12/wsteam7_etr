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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->increments('or_number', 100);
            $table->string('name');
            $table->string('address');
            $table->string('number');
            $table->comment('note');
            
            $table->enum('pay_mode', ['cash on delivery', 'online payment']);
            $table->enum('status', ['to pay', 'to ship', 'to recieve', 'delivered']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
