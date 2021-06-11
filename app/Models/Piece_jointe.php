<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piece_jointe extends Model
{
    use HasFactory;

    protected $fillable = [
        'requete_id',
        'libelle',
        'source',
    ];

    public static $rules = [
        'libelle' => 'required',
        'source' => 'required',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y');
    }
}
