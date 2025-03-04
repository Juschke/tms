<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Einstellungen extends Model
{
    use HasFactory;
    protected $fillable = ['firmenname', 'adresse', 'iban', 'bic', 'umsatzsteuer_id'];
}
