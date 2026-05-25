<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souvenir extends Model
{
    use HasFactory;
    protected $table = 'souvenirs';
    protected $primaryKey = 'code_souv';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_souv',
        'titre_souv',
        'description_souv',
        'url_photo_souv',
        'code_user',
        'code_promo'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'code_user', 'code_user');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'code_promo', 'code_promo');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'code_souv', 'code_souv');
    }
}
