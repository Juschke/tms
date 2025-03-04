<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rechnung extends Model
{
    use HasFactory;

    protected $table = 'rechnungen';

    protected $fillable = [
        'auftrag_id',
        'kunde_id',
        'rechnungsnummer',
        'summe_gesamt',
        'steuersumme',
        'endbetrag',
        'pdf_pfad',
        'ausgestellt_am',
        'faellig_am',
        'status',
    ];

    // Relation zum Auftrag
    public function auftrag()
    {
        return $this->belongsTo(Auftrag::class, 'auftrag_id');
    }

    // Relation zum Kunden
    public function kunde()
    {
        return $this->belongsTo(Kunde::class, 'kunde_id');
    }
}
