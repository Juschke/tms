<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sprachen', function (Blueprint $table) {
            $table->id();
            $table->string('bez_lang')->unique();
            $table->string('bez_kurz')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sprachen');
    }
};
