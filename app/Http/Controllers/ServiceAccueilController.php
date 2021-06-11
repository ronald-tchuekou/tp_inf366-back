<?php

namespace App\Http\Controllers;

use App\Models\Requete;
use App\Models\Service_accueil;
use App\Models\Utilisateur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceAccueilController extends Controller
{
    
    public function index () {
        $home_service = Service_accueil::all();
        foreach ($home_service as $home_s) {
            $home_s->utilisateur;
        }
        return response([
            'status' => 'OK',
            'record' => $home_service
        ], 200);
    }

    public function show (int $id) {
        $home_service = Service_accueil::where('utilisateur_id', $id)->first();
        if($home_service != null){
            $home_service->utilisateur;
        }
        return response ([
            'status' => 'OK',
            'record' => $home_service
        ], 200);
    }

    /**
     * Fonction qui permet d'enregistrer un nouveau utilisateur.
     */
    public function store (Request $request) {
        try {
            // Validation.
            $validate = Validator::make($request->all(), array_merge(Utilisateur::$rules, Service_accueil::$rules));
            if($validate->fails()){
                return response([
                    'status' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            // Check matricule.
            if(Utilisateur::where('matricule', $request->matricule)->first() != null){
                return response([
                    'status' => 'OK',
                    'errors' => 'Un utilisateur possède déjà ce matricule'
                ], 300);
            }
            $user = Utilisateur::create([
                'matricule' => $request->matricule,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'login' => $request->matricule,
                'statut' => 'Service_Accueil',
                'mot_pass' => '237',
            ]);
            $student = Service_accueil::create([
                'utilisateur_id' => $user->id,
                'departement' => $request->departement,
                'filiere' => $request->filiere,
                'localisation' => $request->localisation,
            ]);
            $student->utilisateur;
            return response([
                'status' => 'OK',
                'record' => $student
            ], 200);
        } catch (\Exception $th) {
            return response([
                'status' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Fonction qui permet de faire la mis à jour d'un utilisateur.
     */
    public function update (int $id, Request $request) {
        try {
            $student = Service_accueil::where('utilisateur_id', $id)->first();
            if($student == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette étudiant n'existe pas dans notre base de données."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), array_merge(Utilisateur::$rules, Service_accueil::$rules));
            if($validate->fails()){
                return response([
                    'status' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            // Check matricule.
            if($student->utilisateur->matricule != $request->matricule 
            && Utilisateur::where('matricule', $request->matricule)->first() != null){
                return response([
                    'status' => 'OK',
                    'errors' => 'Un utilisateur possède déjà ce matricule'
                ], 300);
            }
            $student->update([
                'departement' => $request->departement,
                'filiere' => $request->filiere,
                'localisation' => $request->localisation,
            ]);
            $student->utilisateur->update([
                'matricule' => $request->matricule,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'login' => $request->matricule,
            ]);
            return response([
                'status' => 'OK',
                'record' => $student
            ], 200);
        } catch (\Exception $th) {
            return response([
                'status' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Fonction qui permet de supprimer un utilisateur.
     */
    public function delete (int $id) {
        try{
            $user = Utilisateur::find($id);
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette utilisateur n'existe pas dans notre base de données."
                ], 300);
            }
            $user->delete();
            return response([
                'status' => 'OK',
                'record' => $user
            ], 200);
        } catch (\Exception $th) {
            return response([
                'status' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Récupération de tous les requetes qui sont à l'état initial.
     */
    public function init_requetes (int $id) {
        try{
            $user = Service_accueil::where('utilisateur_id', $id)->first();
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette utilisateur n'existe pas dans notre base de données id : " . $id
                ], 300);
            }
            $requ = $user->requetes()
                ->where('statut', 'Initial')
                ->orwhere('statut', 'Validé')
                ->orderBy('created_at', 'desc')->get();
            foreach($requ as $req){
                $req->piece_jointes;
            }
            return response([
                'status' => 'OK',
                'record' => $requ
            ], 200);
        } catch (\Exception $th) {
            return response([
                'status' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Récupération de tous les requetes qui sont à l'état Renvoyé.
     */
    public function reject_requetes (int $id) {
        try{
            $user = Service_accueil::where('utilisateur_id', $id)->first();
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette utilisateur n'existe pas dans notre base de données id : " . $id
                ], 300);
            }
            $requ = $user->requetes()->where('statut', 'Renvoyé')->orderBy('updated_at', 'desc')->get();
            foreach($requ as $req){
                $req->piece_jointes;
            }
            return response([
                'status' => 'OK',
                'record' => $requ
            ], 200);
        } catch (\Exception $th) {
            return response([
                'status' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Récupération de tous les requetes qui sont à l'état Traitement.
     */
    public function assign_requetes (int $id) {
        try{
            $user = Service_accueil::where('utilisateur_id', $id)->first();
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette utilisateur n'existe pas dans notre base de données id : " . $id
                ], 300);
            }
            $requ = $user->requetes()->where('statut', 'Traitement')->orderBy('updated_at', 'desc')->get();
            foreach($requ as $req){
                $req->piece_jointes;
            }
            return response([
                'status' => 'OK',
                'record' => $requ
            ], 200);
        } catch (\Exception $th) {
            return response([
                'status' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Fonction qui permet d'assigner les requetes aux services traitants.
     */
    public function assigne (Request $request) {
        try {
            // Validation.
            $validate = Validator::make($request->all(), [
                'service_traitant_id' => 'required',
                'requete_id' => 'required'
            ]);
            if($validate->fails()){
                return response ([
                    'status' => 'OK',
                    'errors' => $validate->errors()
                ]);
            }
            // Recupération de la requete.
            $requete = Requete::find($request->requete_id);
            $requete->update([
                'service_traitant_id' => $request->service_traitant_id,
                'status' => 'Assigné'
            ]);
            return response(
                ['status' => 'OK',
                'record' => $requete]
            );
        } catch (Exception $th) {
            return response ([
                'status' => 'OK',
                'errors' =>  $th->getMessage()
            ]);
        }
    }
}
