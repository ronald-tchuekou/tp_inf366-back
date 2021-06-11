<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    use HasFactory;


    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'login',
        'statut',
        'mot_pass',
    ];

    public static $rules = [
        'matricule' => 'required',
        'nom' => 'required',
        'prenom' => 'required'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y');
    }
}
