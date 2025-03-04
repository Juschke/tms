<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuftraegeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('auftraege', function (Blueprint $table) {
            $table->id();
            $table->string('auftragsnummer')->unique();
            $table->foreignId('kunde_id')->constrained('kunden')->onDelete('cascade');
            $table->foreignId('zugewiesen_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('titel');
            $table->enum('status', ['Neu', 'InBearbeitung', 'Abgeschlossen', 'Storniert'])->default('Neu');
            $table->enum('prioritaet', ['Niedrig', 'Normal', 'Hoch', 'Dringend'])->default('Normal');
            $table->unsignedBigInteger('quell_sprache');
            $table->unsignedBigInteger('ziel_sprache');
            $table->foreign('quell_sprache')->references('id')->on('sprachen')->onDelete('restrict');
            $table->foreign('ziel_sprache')->references('id')->on('sprachen')->onDelete('restrict');
            $table->date('erstellungsdatum')->default(now());
            $table->date('faellig_am');
            $table->decimal('preis_gesamt', 10, 2)->default(0.00);
            $table->decimal('anzahlung', 10, 2)->default(0.00);
            $table->decimal('steuersatz', 5, 2)->default(19.00);
            $table->decimal('rabatt_prozent', 5, 2)->default(0.00);
            $table->string('hochgeladene_datei')->nullable();
            $table->string('standort')->nullable();
            $table->boolean('geloescht_markiert')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(){
        Schema::dropIfExists('auftraege');
        Schema::table('auftraege', function (Blueprint $table) {
        $table->dropSoftDeletes(); // Entfernt die Spalte 'deleted_at' wieder
        });
    }
}
