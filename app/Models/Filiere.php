<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;
    protected $table = 'filieres';
    protected $primaryKey ='code_fil';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable =[
        'nom_fil',
        'description_fil'
    ];
    public function promotions()
    {
        return $this->hasMany(Promotion::class,'code_fil','code_fil');
    }
}
