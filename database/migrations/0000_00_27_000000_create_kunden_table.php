<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKundenTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kunden', function (Blueprint $table) {
            $table->id();
            $table->string('firmenname');
            $table->string('kontakt_email')->unique();
            $table->string('telefon')->nullable();
            $table->text('adresse')->nullable();
            $table->string('unternehmensname')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunden');
    }
}
