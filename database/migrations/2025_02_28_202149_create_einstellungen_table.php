<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEinstellungenTable extends Migration
{
    public function up()
    {
        Schema::create('einstellungen', function (Blueprint $table) {
            $table->id();
            $table->string('firmenname')->nullable();
            $table->string('adresse')->nullable();
            $table->string('iban')->nullable();
            $table->string('bic')->nullable();
            $table->string('umsatzsteuer_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('einstellungen');
    }
}
