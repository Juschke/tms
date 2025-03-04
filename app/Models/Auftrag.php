<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auftrag extends Model
{
    use HasFactory;
    use SoftDeletes; 

    protected $table = 'auftraege';

    protected $fillable = [
        'auftragsnummer',
        'kunde_id',
        'zugewiesen_user_id',
        'partner_id',
        'titel',
        'status',
        'quell_sprache',
        'ziel_sprache',
        'erstellungsdatum',
        'faellig_am',
        'preis_gesamt',
        'anzahlung',
        'steuersatz',
        'rabatt_prozent',
        'prioritaet',
        'hochgeladene_datei',
        'standort',
        'geloescht_markiert',
        'anmerkungen',       
        'angerufen',
    ];

    /**
     * Casts - Automatische Umwandlung von Datentypen
     */
    protected $casts = [
        'erstellungsdatum' => 'datetime',
        'faellig_am' => 'datetime',
        'preis_gesamt' => 'decimal:2',
        'anzahlung' => 'decimal:2',
        'steuersatz' => 'decimal:2',
        'rabatt_prozent' => 'decimal:2',
        'geloescht_markiert' => 'boolean',
        'angerufen'          => 'boolean',
    ];

    /**
     * Default-Werte für Felder, falls sie nicht gesetzt sind.
     */
    protected $attributes = [
        'status' => 'Neu', // Standardstatus
        'prioritaet' => 'Normal', // Standardpriorität
        'geloescht_markiert' => false, // Standardmäßig nicht gelöscht
    ];

    /**
     * Eager Loading für Performance-Optimierung
     */
    protected $with = ['kunde', 'benutzer'];

    /**
     * Relation zum Kunden
     */
    public function kunde()
    {
        return $this->belongsTo(Kunde::class, 'kunde_id');
    }

    /**
     * Relation zum zugewiesenen Benutzer
     */
    public function benutzer()
    {
        return $this->belongsTo(User::class, 'zugewiesen_user_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    /**
     * Scope für Status-Filterung
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope für offene Aufträge
     */
    public function scopeOffeneAuftraege($query)
    {
        return $query->where('status', 'Neu')->orWhere('status', 'InBearbeitung');
    }

    /**
     * Scope für überfällige Aufträge
     */
    public function scopeFaelligeAuftraege($query)
    {
        return $query->where('faellig_am', '<', now())->whereNotIn('status', ['Abgeschlossen', 'Storniert']);
    }

    /**
     * Accessor für formatierte Preise (Optional)
     */
    public function getPreisGesamtFormattedAttribute()
    {
        return number_format($this->preis_gesamt, 2, ',', '.') . ' €';
    }

    /**
     * Mutator: Setze immer den ersten Buchstaben des Titels groß
     */
    public function setTitelAttribute($value)
    {
        $this->attributes['titel'] = ucfirst($value);
    }
}
