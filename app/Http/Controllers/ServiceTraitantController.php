<?php

namespace App\Http\Controllers;

use App\Models\Service_traitant;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceTraitantController extends Controller
{
    
    public function index () {
        $done_service = Service_traitant::all();
        foreach ($done_service as $home_s) {
            $home_s->utilisateur;
        }
        return response([
            'status' => 'OK',
            'record' => $done_service
        ], 200);
    }

    public function show (int $id) {
        $done_service = Service_traitant::where('utilisateur_id', $id)->first();
        if($done_service != null){
            $done_service->utilisateur;
        }
        return response ([
            'status' => 'OK',
            'record' => $done_service
        ], 200);
    }

    /**
     * Fonction qui permet d'enregistrer un nouveau utilisateur.
     */
    public function store (Request $request) {
        try {
            // Validation.
            $validate = Validator::make($request->all(), array_merge(Utilisateur::$rules, Service_traitant::$rules));
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
                'statut' => 'Service_Traitant',
                'mot_pass' => '237',
            ]);
            $student = Service_traitant::create([
                'utilisateur_id' => $user->id,
                'departement' => $request->departement,
                'filiere' => $request->filiere,
                'role' => $request->role,
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
            $student = Service_traitant::where('utilisateur_id', $id)->first();
            if($student == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette étudiant n'existe pas dans notre base de données."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), array_merge(Utilisateur::$rules, Service_traitant::$rules));
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
                'role' => $request->role,
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

    public function requetes (int $id) {
        try{
            $user = Service_traitant::where('utilisateur_id', $id)->first();
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette utilisateur n'existe pas dans notre base de données."
                ], 300);
            }
            $requ = $user->requetes()
                ->where('statut', 'Traitement')
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

    public function finish_requetes (int $id) {
        try{
            $user = Service_traitant::where('utilisateur_id', $id)->first();
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette utilisateur n'existe pas dans notre base de données."
                ], 300);
            }
            $requ = $user->requetes()
                ->where('statut', 'Traité')
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

    public function reject_requetes (int $id) {
        try{
            $user = Service_traitant::where('utilisateur_id', $id)->first();
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette utilisateur n'existe pas dans notre base de données."
                ], 300);
            }
            $requ = $user->requetes()
                ->where('statut', 'Rejeté')
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
}
