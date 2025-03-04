<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuftragPosition extends Model
{
    use HasFactory;

    protected $table = 'auftrag_positionen';

    protected $fillable = [
        'auftrag_id',
        'beschreibung',
        'menge',
        'preis',
        'mwst',
        'einheit',
    ];
}
