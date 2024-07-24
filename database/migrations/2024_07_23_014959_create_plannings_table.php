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
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('decoration_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('mua_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('catering_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('photographer_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
