<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('rechnungen');
        Schema::create('rechnungen', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('auftrag_id')->comment('Verweis auf Tabelle auftraege');
            $table->foreign('auftrag_id')->references('id')->on('auftraege')->onDelete('cascade');

            $table->unsignedBigInteger('kunde_id')->comment('Verweis auf Tabelle kunden');
            $table->foreign('kunde_id')->references('id')->on('kunden')->onDelete('cascade');

            $table->string('rechnungsnummer', 50)->unique()->comment('Eindeutige Rechnungsnummer');

            $table->decimal('summe_gesamt', 10, 2)->default(0.00)->comment('Summe ohne Steuern und Rabatte');
            $table->decimal('steuersumme', 10, 2)->default(0.00)->comment('Absoluter Steuerbetrag');
            $table->decimal('endbetrag', 10, 2)->default(0.00)->comment('Endbetrag inkl. Steuern und abzüglich Rabatt');

            $table->string('pdf_pfad')->nullable()->comment('Pfad/URL zur PDF-Datei der Rechnung');

            $table->date('ausgestellt_am')->comment('Datum, an dem die Rechnung ausgestellt wurde');
            $table->date('faellig_am')->comment('Fälligkeitsdatum der Rechnung');

            $table->enum('status', ['Offen', 'Bezahlt', 'Ueberfaellig'])
                  ->default('Offen')
                  ->comment('Status der Rechnung');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rechnungen');
    }
};
