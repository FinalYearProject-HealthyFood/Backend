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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->double("serving_size");
            $table->double("price");
            $table->double("calories");
            $table->double("protein");
            $table->double("carb");
            $table->double("fat");
            $table->double("sat_fat")->nullable();
            $table->double("trans_fat")->nullable();
            $table->double("fiber")->nullable();
            $table->double("sugar")->nullable();
            $table->double("cholesterol")->nullable();
            $table->double("sodium")->nullable();
            $table->double("calcium")->nullable();
            $table->double("iron")->nullable();
            $table->double("zinc")->nullable();
            $table->enum('status',['active','deactive'])->default('active');
            $table->string('image')->nullable();
            $table->double('rate')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
