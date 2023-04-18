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
        Schema::create('meal_ingredients', function (Blueprint $table) {
            $table->id();
            $table->double('quantity')->default(1.0);
            $table->bigInteger('meal_id')->unsigned()->index();
            $table->foreign('meal_id')->references('id')->on('meals')->onDelete('cascade');
            $table->bigInteger('ingredient_id')->unsigned()->index();
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_ingredients');
    }
};
