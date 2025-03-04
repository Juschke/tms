<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('auftraege', function (Blueprint $table) {
            $table->softDeletes(); // Fügt die Spalte 'deleted_at' hinzu
        });
    }

    public function down(): void
    {
        Schema::table('auftraege', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Entfernt die Spalte 'deleted_at' wieder
        });
    }
};
