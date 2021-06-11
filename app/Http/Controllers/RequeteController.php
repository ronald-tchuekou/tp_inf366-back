<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Requete;
use App\Models\Service_accueil;
use App\Models\Utilisateur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequeteController extends Controller
{
    /**
     * Fonction qui permet d'initialiser une nouvelle requete.
     */
    public function store (Request $request) {
        try{
            // Validation.
            $validate = Validator::make($request->all(), Requete::$rules);
            if($validate->fails()){
                return response([
                    'statut' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $student = Etudiant::where('utilisateur_id', $request->etudiant_id)->first();
            if($student == null){
                return response([
                    'statut' => 'OK',
                    'errors' => 'Pas de statut étudiant avec le ID : ' . $request->etudiant_id
                ], 300);
            }
            $sa = Service_accueil::where('departement', $student->departement)->first();
                if($sa == null){
                    return response([
                        'statut' => 'OK',
                        'errors' => 'Pas de statut service accueil avec le ID : ' . $request->etudiant_id
                    ], 300);
                }
            $requete = Requete::create([
                'etudiant_id' => $request->etudiant_id,
                'objet' => $request->objet,
                'destination' => $request->destination,
                'description' => $request->description,
                'service_accueil_id' => $sa->utilisateur_id,
                'date_initial' => now(),
                'statut' => 'Initial',
            ]);
            $requete->piece_jointes()->createMany($this->getPieces($request));
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
        
    }

    public function modifier_piece (int $id, Request $request){
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez modifier la piece n'existe pas."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), [
                'jointe_id' => 'required',
                'libelle' => 'required',
                'source' => 'required'
            ]);
            if($validate->fails()){
                return response ([
                    'statut' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $j = $requete->piece_jointes()->where('id', $request->jointe_id)->first();
            if($j != null)
                $j->update([
                    'libelle' => $request->libelle,
                    'source' => $request->source
                ]);
            else
                return response ([
                    'statut' => 'OK',
                    'errors' => "La piece n'existe pas."
                ], 300);
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    public function ajouter_piece (int $id, Request $request){
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez joindre une nouvelle piece n'existe pas."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), [
                'libelle' => 'required',
                'source' => 'required'
            ]);
            if($validate->fails()){
                return response ([
                    'statut' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $requete->piece_jointes()->create([
                'libelle' => $request->libelle,
                'source' => $request->source
            ]); // Suppression.
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    public function supprimer_piece(int $id, Request $request){
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez supprimer une piece n'existe pas."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), [
                'jointe_id' => 'required',
            ]);
            if($validate->fails()){
                return response ([
                    'statut' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $j = $requete->piece_jointes()->where('id', $request->jointe_id)->first();
            if($j != null)
                $j->delete(); // Suppression.
            else
                return response ([
                    'statut' => 'OK',
                    'errors' => "Cette requete ne possède pas de piéce jointe avec l'id : " . $request->jointe_id
                ], 300);
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    private function getPieces (Request $request){
        if(!isset($request->piece_jointes)) return [];
        // foreach ($request->piece_jointes as $pieces) {
        //     array_push($result, [
        //         'libelle' => $pieces->libelle,
        //         'source' => $pieces->source
        //     ]);
        // }
        return $request->piece_jointes;
    }

    /**
     * Fonction qui permet de faire la mis à jour d'une requete.
     */
    public function update (int $id, Request $request) {
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez faire la modification n'existe pas."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), Requete::$rules);
            if($validate->fails()){
                return response([
                    'statut' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $requete->update([
                'etudiant_id' => $request->etudiant_id,
                'objet' => $request->objet,
                'destination' => $request->destination,
                'date_initial' => now(),
                'statut' => 'Initial',
            ]); // Mis à jour.
            // $this->update_pieceJointe($requete, $request);
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Pour le renvoie d'une requete.
     */
    public function renvoie (int $id, Request $request){
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez renvoyer n'existe pas."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), [
                'motif_renvoie' => 'required'
            ]);
            if($validate->fails()){
                return response ([
                    'statut' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $requete->update([
                'statut' => 'Renvoyé',
                'motif_renvoie' => $request->motif_renvoie,
                'date_renvoie' => now()
            ]); // Suppression.
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Pour le renvoie d'une requete.
     */
    public function rejeter (int $id, Request $request){
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez rejeter n'existe pas."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), [
                'motif_rejet' => 'required'
            ]);
            if($validate->fails()){
                return response ([
                    'statut' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $requete->update([
                'statut' => 'Rejeté',
                'motif_rejet' => $request->motif_rejet,
                'date_rejet' => now()
            ]); // Suppression.
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Pour le renvoie d'une requete.
     */
    public function valider (int $id){
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez renvoyer n'existe pas."
                ], 300);
            }
            $requete->update([
                'statut' => 'Validé',
            ]); // Suppression.
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    public function assigner (int $id, Request $request){
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'status' => 'OK',
                    'errors' => 'La requete dont vous souhaiter assigner n\'existe pas dans la base de données.'
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), [
                'requete_id' => 'required',
                'service_traitant_id' => 'required',
            ]);
            if($validate->fails()){
                return response([
                    'status' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $requete->update([
                'service_traitant_id' => $request->service_traitant_id,
                'statut' => 'Traitement'
            ]);
            $requete->piece_jointes;
            return response ([
                'status' =>  'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'status' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Pour le renvoie d'une requete.
     */
    public function traiter (int $id, Request $request){
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez renvoyer n'existe pas."
                ], 300);
            }
            $requete->update([
                'statut' => 'Traité',
                'reponse' => $request->reponse,
                'data_traite' => now()
            ]); // Suppression.
            $requete->piece_jointes;
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    // private function update_pieceJointe (Requete $requete, Request $request) {
    //     if(isset($request->piece_jointes)){
    //         if($requete->piece_jointes->count() > 0)
    //         foreach ($request->piece_jointes as $pj) {
    //             dd($pj);
    //             $requete->piece_jointes()->updateExistingPivot($pj->id, $pj);
    //         }
    //     };
    // }

    /**
     * Fonction qui permet de supprimer une requete.
     */
    public function delete (int $id) {
        try{
            $requete = Requete::find($id);
            if($requete == null){
                return response ([
                    'statut' => 'OK',
                    'errors' => "La requete donc vous voullez surprimer n'existe pas."
                ], 300);
            }
            $requete->delete(); // Suppression.
            return response ([
                'statut' => 'OK',
                'record' => $requete
            ], 200);
        }catch(Exception $th){
            return response([
                'statut' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }
}
