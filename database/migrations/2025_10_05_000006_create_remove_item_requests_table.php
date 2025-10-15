<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remove_item_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->noActionOnDelete();
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items')->noActionOnDelete();
            $table->enum('status', ['STORED', 'AUCTIONED'])->default('STORED');
            $table->boolean('unit_confirmed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remove_item_requests');
    }
};
