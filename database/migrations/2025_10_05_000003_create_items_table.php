<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->constrained('users')->noActionOnDelete();
            $table->foreignId('type_id')->constrained('types')->noActionOnDelete();
            $table->string('code');
            $table->integer('order_number');
            $table->string('name');
            $table->integer('cost');
            $table->date('acquisition_date');
            $table->integer('acquisition_year');
            $table->enum('status', ['AVAILABLE', 'BORROWED'])->default('AVAILABLE');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['code', 'order_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
