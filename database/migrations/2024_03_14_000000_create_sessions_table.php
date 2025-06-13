<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE TABLE sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id INTEGER NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload TEXT NOT NULL,
                last_activity INT NOT NULL,
                INDEX last_activity_index (last_activity),
                CONSTRAINT sessions_user_id_foreign 
                    FOREIGN KEY (user_id) 
                    REFERENCES usuario (matricula) 
                    ON DELETE CASCADE
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
}; 