<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpracheSeeder extends Seeder
{
    public function run()
    {
        DB::table('sprachen')->insert([
            ['bez_lang' => 'Deutsch', 'bez_kurz' => 'DE'],
            ['bez_lang' => 'Englisch', 'bez_kurz' => 'EN'],
            ['bez_lang' => 'Französisch', 'bez_kurz' => 'FR'],
            ['bez_lang' => 'Spanisch', 'bez_kurz' => 'ES'],
            ['bez_lang' => 'Italienisch', 'bez_kurz' => 'IT'],
            ['bez_lang' => 'Russisch', 'bez_kurz' => 'RU'],
        ]);
    }
}
