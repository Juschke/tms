<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprachen extends Model
{
    use HasFactory;

    protected $table = 'sprachen'; // Verknüpft mit der `sprachen`-Tabelle

    protected $fillable = [
        'bez_lang',
        'bez_kurz'
    ];
}
