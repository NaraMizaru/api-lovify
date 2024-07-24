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
        Schema::create('detail_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('wedding_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('packet_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('planning_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transactions');
    }
};
