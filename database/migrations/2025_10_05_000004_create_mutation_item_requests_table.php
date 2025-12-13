<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutation_item_requests', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items')->noActionOnDelete();
            $table->foreignId('from_user_id')->references('id')->on('users')->noActionOnDelete();
            $table->foreignId('to_user_id')->references('id')->on('users')->noActionOnDelete();
            $table->boolean('unit_confirmed')->default(false);
            $table->boolean('recipient_confirmed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutation_item_requests');
    }
};
