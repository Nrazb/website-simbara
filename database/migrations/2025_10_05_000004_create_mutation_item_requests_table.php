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
            $table->foreignId('maintenance_unit_id')->constrained('users')->onDelete('cascade');
            $table->string('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
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
