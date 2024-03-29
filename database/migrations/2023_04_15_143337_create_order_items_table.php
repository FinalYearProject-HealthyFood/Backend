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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->bigInteger('meal_id')->unsigned()->nullable();
            $table->bigInteger('ingredient_id')->unsigned()->nullable();
            $table->enum('status', ['incart', 'pending', 'accepted', 'delivered', 'canceled'])->default('incart');
            $table->enum('for_me', ['no', 'yes'])->default('no');
            $table->integer('quantity');
            $table->double("total_price")->default(0.0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('meal_id')->references('id')->on('meals')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
