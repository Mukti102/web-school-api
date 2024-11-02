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
        Schema::create('teachers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('NUPTK')->nullable();
            $table->string('NIP')->nullable();
            $table->string('start')->nullable();
            $table->string('TTL');
            $table->string('religion');
            $table->string('gender');
            $table->string('phone');
            $table->string('status');
            $table->string('email');
            $table->string('position');
            $table->string('address');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
