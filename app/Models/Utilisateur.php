<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Model implements AuthenticatableContract
{
    use HasFactory, HasApiTokens, Authenticatable;

    protected $table = "utilisateurs";
    protected $primaryKey = "code_user";
    public $incrementing = false;
    protected $keyType = "string";
    public $timestamps = true;

    protected $fillable = [
        "code_user",
        "nom_user",
        "prenom_user",
        "login_user",
        "password_user",
        "tel_user",
        "sexe_user",
        "role_user",
        "etat_user",
        "code_promo",
        "code_fil"
    ];

    protected $hidden = ['password_user'];

    /**
     * CORRECTION CRITIQUE : override getAuthPassword pour pointer vers password_user
     */
    public function getAuthPassword()
    {
        return $this->password_user;
    }

    public function getRememberTokenName()
    {
        return null;
    }

    public function souvenirs()
    {
        return $this->hasMany(\App\Models\Souvenir::class, 'code_user', 'code_user');
    }

    public function temoignages()
    {
        return $this->hasMany(\App\Models\Temoignage::class, 'code_user', 'code_user');
    }

    public function commentaires()
    {
        return $this->hasMany(\App\Models\Commentaire::class, 'code_user', 'code_user');
    }

    public function promotion()
    {
        return $this->belongsTo(\App\Models\Promotion::class, 'code_promo', 'code_promo');
    }

    public function filiere()
    {
        return $this->belongsTo(\App\Models\Filiere::class, 'code_fil', 'code_fil');
    }
}
