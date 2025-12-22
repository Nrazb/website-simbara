<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_item_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->noActionOnDelete();
            $table->foreignId('maintenance_user_id')->constrained('users')->noActionOnDelete();
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items')->noActionOnDelete();
            $table->enum('item_status', ['PENDING', 'GOOD', 'DAMAGED', 'REPAIRED'])->default('PENDING');
            $table->string('information')->nullable();
            $table->enum('maintenance_status', [
                'PENDING',
                'APPROVED',
                'BEING_SENT',
                'PROCESSING',
                'COMPLETED',
                'REJECTED',
                'REMOVED',
                'BEING_SENT_BACK',
            ])->default('PENDING');
            $table->boolean('unit_confirmed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_item_requests');
    }
};
