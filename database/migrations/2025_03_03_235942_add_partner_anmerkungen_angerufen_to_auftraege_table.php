<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('auftraege', function (Blueprint $table) {
            // partner_id -> Falls du es brauchst
            $table->unsignedBigInteger('partner_id')->nullable()->after('zugewiesen_user_id');

            // anmerkungen -> TEXT-Feld
            $table->text('anmerkungen')->nullable()->after('prioritaet');

            // angerufen -> boolean
            // oder tinyint(1); mit default false
            $table->boolean('angerufen')->default(false)->after('anmerkungen');
        });
    }

    public function down()
    {
        Schema::table('auftraege', function (Blueprint $table) {
            $table->dropColumn('partner_id');
            $table->dropColumn('anmerkungen');
            $table->dropColumn('angerufen');
        });
    }
};
