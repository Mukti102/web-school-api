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
        Schema::create('profile_schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('adress_of_school');
            $table->string('lead_of_school');
            $table->string('nip_of_lead_of_school');
            $table->string('phone');
            $table->string('email');
            $table->string('ketua_panitia');
            $table->string('nip_ketua_panitia');
            $table->string('ttd_lead_of_school')->nullable();
            $table->string('ttd_ketua_panitia')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_schools');
    }
};
