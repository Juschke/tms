<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitarbeiter extends Model
{
    use HasFactory;

    // Tabelle (falls du einen anderen Tabellennamen möchtest, als Laravel automatisch generiert)
    protected $table = 'mitarbeiter';

    // Massen­zuweisbare Felder
    protected $fillable = [
        'vorname',
        'nachname',
        'email',
        'telefon',
        'position',
    ];
}
