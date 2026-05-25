<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temoignage extends Model
{
    use HasFactory;
    protected $table = 'temoignages';
    protected $primaryKey = 'code_tem';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'code_tem',
        'contenu_tem',
        'valide_tem',
        'code_user',
        'code_promo'
    ];

    protected $casts = [
        'valide_tem' => 'boolean',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'code_user', 'code_user');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'code_promo', 'code_promo');
    }
}
