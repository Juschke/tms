<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('auftrag_positionen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auftrag_id');
            $table->string('beschreibung')->nullable();
            $table->integer('menge')->default(1);
            $table->decimal('preis', 10, 2)->default(0);
            $table->decimal('mwst', 5, 2)->default(19);
            $table->string('einheit')->nullable();
            $table->timestamps();

            $table->foreign('auftrag_id')
                  ->references('id')
                  ->on('auftraege')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('auftrag_positionen');
    }
};
