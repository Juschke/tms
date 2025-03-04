<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auftrag_partner', function (Blueprint $table) {
            $table->id();

            // partner_id
            $table->unsignedBigInteger('partner_id');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');

            // auftrag_id
            $table->unsignedBigInteger('auftrag_id');
            $table->foreign('auftrag_id')->references('id')->on('auftraege')->onDelete('cascade');

            // Zeitstempel für withTimestamps()
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auftrag_partner');
    }
};
