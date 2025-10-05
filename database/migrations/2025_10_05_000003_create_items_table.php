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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_id')->nullable()->constrained('types')->nullOnDelete();
            $table->foreignId('maintenance_unit_id')->nullable()->constrained('users');
            $table->string('code')->unique();
            $table->integer('order_number')->unique();
            $table->string('name');
            $table->integer('cost');
            $table->date('acquisition_date');
            $table->integer('acquisition_year');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
