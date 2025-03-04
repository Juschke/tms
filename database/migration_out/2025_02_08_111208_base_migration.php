<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Führe die Migration aus.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 1) Standard: users (Laravel Auth)
         *    - Nutzt Standard-Spalten für Laravel-Authentifizierung
         */
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Übliche Felder für Laravel-Auth:
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->timestamps();
        });

        /**
         * 2) Tabelle: kunden
         *    - Deutsches Pendant für Client-Daten
         */
        Schema::dropIfExists('kunden');
        Schema::create('kunden', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firmenname')->comment('Name/Firma des Kunden');
            $table->string('kontakt_email')->unique()->comment('E-Mail-Adresse des Kunden');
            $table->string('telefon', 20)->nullable()->comment('Telefonnummer');
            $table->text('adresse')->nullable()->comment('Anschrift');
            $table->string('unternehmensname')->nullable()->comment('Zusätzliche Firmeninformation');
            $table->timestamps();
        });

        /**
         * 3) Tabelle: auftraege
         *    - Enthält Informationen zu Übersetzungsaufträgen
         *    - Verknüpft mit users (z.B. für den zugewiesenen Übersetzer/PM)
         *    - Verknüpft mit kunden (Auftrag gehört zu einem Kunden)
         */
        Schema::dropIfExists('auftraege');
        Schema::create('auftraege', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('auftragsnummer', 50)->unique()->comment('Eindeutige Auftragsnummer');

            // Verknüpfung zum Kunden
            $table->unsignedBigInteger('kunde_id')->comment('Verweis auf Tabelle kunden');
            $table->foreign('kunde_id')->references('id')->on('kunden')->onDelete('cascade');

            // Verknüpfung zu einem Benutzer (z.B. Übersetzer oder Projektmanager)
            $table->unsignedBigInteger('zugewiesen_user_id')->nullable()
                  ->comment('Verweis auf Tabelle users, falls ein Übersetzer / PM zugewiesen ist');
            $table->foreign('zugewiesen_user_id')->references('id')->on('users')->onDelete('set null');

            $table->string('titel')->comment('Kurze Beschreibung oder Titel des Auftrags');
            $table->enum('status', ['Neu', 'InBearbeitung', 'Abgeschlossen', 'Storniert'])
                  ->default('Neu')
                  ->comment('Status des Auftrags');
            $table->string('quell_sprache', 10)->comment('Ausgangssprache');
            $table->string('ziel_sprache', 10)->comment('Zielsprache');

            $table->date('erstellungsdatum')->comment('Datum der Auftragserstellung');
            $table->date('faellig_am')->comment('Fälligkeitsdatum / Deadline');

            $table->decimal('preis_gesamt', 10, 2)->default(0.00)->comment('Gesamtpreis des Auftrags');
            $table->decimal('anzahlung', 10, 2)->default(0.00)->comment('Bereits geleistete Vorauszahlung');
            $table->decimal('steuersatz', 5, 2)->default(19.00)->comment('Steuersatz in Prozent');
            $table->decimal('rabatt_prozent', 5, 2)->default(0.00)->comment('Rabatt in Prozent');

            $table->enum('prioritaet', ['Niedrig', 'Normal', 'Hoch', 'Dringend'])
                  ->default('Normal')
                  ->comment('Dringlichkeit des Auftrags');

            $table->string('hochgeladene_datei')->nullable()->comment('Pfad zur hochgeladenen Datei');
            $table->string('standort')->nullable()->comment('Optionaler Standort (z.B. für Dolmetscher-Einsatz)');

            $table->boolean('geloescht_markiert')->default(false)->comment('Flag für Soft-Delete');
            $table->timestamps();
        });

        /**
         * 4) Tabelle: rechnungen
         *    - Enthält die Daten für Rechnungen
         *    - Verknüpft mit auftraege (Auftrag)
         *    - Verknüpft mit kunden (Kunde)
         */
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

    /**
     * Rolle rückgängig machen (Tabellen löschen).
     *
     * @return void
     */
    public function down()
    {
        // Reihenfolge beachten (Relations zuerst entfernen)
        Schema::dropIfExists('rechnungen');
        Schema::dropIfExists('auftraege');
        Schema::dropIfExists('kunden');
        Schema::dropIfExists('users');
    }
};
