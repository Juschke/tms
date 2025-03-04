<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    // Falls dein Tabellennamen nicht von Laravel abgeleitet wird (also 'partners'),
    // kannst du ihn anpassen. Standardmäßig würde Laravel 'partners' erwarten.
    protected $table = 'partners';

    protected $fillable = [
        'name',
        'email',
        'role',
        'telefon',
        'kommentar'
    ];

    /**
     * Beziehung: Viele Aufträge können mit vielen Partnern verknüpft sein.
     */
    public function auftraege()
    {
        // Pivot-Tabelle heißt 'auftrag_partner'
        return $this->belongsToMany(Auftrag::class, 'auftrag_partner', 'partner_id', 'auftrag_id')
                    ->withTimestamps();
    }
}
