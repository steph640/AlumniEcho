<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;
    protected $table = 'commentaires';
    protected $primaryKey = 'code_com';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_com',
        'contenu_com',
        'code_user',
        'code_souv',
        'code_tem',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'code_user', 'code_user');
    }

    public function souvenir()
    {
        return $this->belongsTo(Souvenir::class, 'code_souv', 'code_souv');
    }
}
