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
        // First, alter the ENUM to include both values
        DB::statement("ALTER TABLE user_profiles MODIFY COLUMN division ENUM('akuntansi', 'sekretaria', 'sekretariat', 'anggaran', 'keuangan', 'perbendaharaan') NULL");
        
        // Update data
        DB::table('user_profiles')
            ->where('division', 'sekretaria')
            ->update(['division' => 'sekretariat']);
        
        // Then, remove the old value from ENUM
        DB::statement("ALTER TABLE user_profiles MODIFY COLUMN division ENUM('akuntansi', 'sekretariat', 'anggaran', 'keuangan', 'perbendaharaan') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: update kembali ke 'sekretaria'
        DB::statement("ALTER TABLE user_profiles MODIFY COLUMN division ENUM('akuntansi', 'sekretaria', 'sekretariat', 'anggaran', 'keuangan', 'perbendaharaan') NULL");
        
        DB::table('user_profiles')
            ->where('division', 'sekretariat')
            ->update(['division' => 'sekretaria']);
        
        DB::statement("ALTER TABLE user_profiles MODIFY COLUMN division ENUM('akuntansi', 'sekretaria', 'anggaran', 'keuangan', 'perbendaharaan') NULL");
    }
};

