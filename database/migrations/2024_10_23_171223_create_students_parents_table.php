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
        Schema::dropIfExists('students_parents'); // Drop the existing table if it exists
        if (!Schema::hasTable('students_parents')) {
            Schema::create('students_parents', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('student_id')->unique();
                $table->string('no_kk');
                $table->string('lead_family');
                $table->string('father_name');
                $table->string('father_nik');
                $table->string('father_birth');
                $table->string('father_job');
                $table->string('father_education');
                $table->string('mom_name');
                $table->string('mom_nik');
                $table->string('mom_birth');
                $table->string('mom_job');
                $table->string('mom_education');
                $table->timestamps();
                // Definisikan foreign key
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_parents');
    }
};
