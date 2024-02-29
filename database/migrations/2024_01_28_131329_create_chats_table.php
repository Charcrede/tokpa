<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('chats', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('message');
    //         $table->UnsignedBigInteger('sender');
    //         $table->UnsignedBigInteger('receiver');
    //         $table->UnsignedBigInteger('media_id')->nullable();
    //         $table->UnsignedBigInteger('conv_id');
    //         $table->timestamps();
    //         $table->foreign('media_id')->references('id')->on('chat_media')->onDelete('set null')->onUpdate('set null');
    //         $table->foreign('conv_id')->references('id')->on('conversations')->onDelete('cascade')->onUpdate('cascade');
    //         $table->foreign('sender')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    //         $table->foreign('receiver')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
