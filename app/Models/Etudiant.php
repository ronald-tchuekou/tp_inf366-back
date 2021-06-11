<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'departement',
        'filiere',
        'niveau',
    ];

    public static $rules = [
        'departement' => 'required',
        'filiere' => 'required',
        'niveau' => 'required',
    ];

    public function utilisateur () {
        return $this->hasOne(Utilisateur::class, 'id', 'utilisateur_id');
    }

    public function requetes () {
        return $this->hasMany(Requete::class, 'etudiant_id', 'utilisateur_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y');
    }
}
