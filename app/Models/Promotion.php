<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $table = 'promotions';
    protected $primaryKey = 'code_promo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nom_promo',
        'annee_promo',
        'code_fil'
    ];

    // Relations
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_fil', 'code_fil');
    }
    public function souvenirs()
    {
        return $this->hasMany(Souvenir::class, 'code_promo', 'code_promo');
    }
    public function temoignages()
    {
        return $this->hasMany(Temoignage::class, 'code_promo', 'code_promo');
    }
}
