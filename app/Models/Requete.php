<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requete extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'objet',
        'destination',
        'date_initial',
        'statut',
        'date_renvoie',
        'motif_renvoie',
        'date_rejet',
        'motif_rejet',
        'date_traite',
        'description',
        'reponse',
        'service_accueil_id',
        'service_traitant_id',
    ];

    public static $rules = [
        'etudiant_id' => 'required',
        'objet' => 'required',
        'destination' => 'required',
        'description' => 'required',
        'service_accueil_id' => 'required'
    ];

    public function piece_jointes () {
        return $this->hasMany(Piece_jointe::class, 'requete_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y');
    }
}
