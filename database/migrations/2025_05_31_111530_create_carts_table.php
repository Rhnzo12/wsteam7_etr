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
        Schema::create('carts', function (Blueprint $table) {
             $table->id();

            // User who owns this cart record
            $table->unsignedBigInteger('user_id');

            // Linked product
            $table->unsignedBigInteger('product_id');

            // Quantity added to cart
            $table->integer('quantity')->default(1);

            // Store product info for easy access (optional but useful for history, snapshots)
            $table->string('title');
            $table->decimal('price', 10, 2);
            $table->string('image_path')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Optional: to prevent duplicate cart entries for same user/product
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
