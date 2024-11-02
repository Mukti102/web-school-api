<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create users table if it doesn't exist
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('username');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('profile')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the users table if it exists
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // If the foreign key exists, drop it
                if (DB::select("SELECT CONSTRAINT_NAME 
                                FROM information_schema.KEY_COLUMN_USAGE 
                                WHERE TABLE_NAME = 'users' 
                                AND CONSTRAINT_NAME = 'users_role_id_foreign'")) {
                    $table->dropForeign(['role_id']);
                }

                // Drop the role_id column if it exists
                if (Schema::hasColumn('users', 'role_id')) {
                    $table->dropColumn('role_id');
                }
            });

            // Finally, drop the users table
            Schema::dropIfExists('users');
        }
    }
};
