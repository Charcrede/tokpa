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
        Schema::create('boutiques', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description');
            $table->string('address', 50);
            $table->string('category', 50);
            $table->string('site', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('telephone')->nullable();
            $table->integer('commande');
            $table->integer('visit');
            $table->UnsignedBigInteger('user_id');
            $table->UnsignedBigInteger('logo')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('logo')->references('id')->on('boutique_media')->onDelete('set null')->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boutiques');
    }
};
