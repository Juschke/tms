<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunde extends Model
{
    use HasFactory;

    protected $table = 'kunden';

    protected $fillable = [
        'firmenname',
        'kontakt_email',
        'telefon',
        'adresse',
        'unternehmensname',
    ];

    // Relationen (Beispiel):
    // Ein Kunde kann viele Aufträge haben
    public function auftraege()
    {
        return $this->hasMany(Auftrag::class, 'kunde_id');
    }
}
