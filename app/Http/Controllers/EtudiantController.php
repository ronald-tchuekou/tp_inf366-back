<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EtudiantController extends Controller
{

    public function index () {
        $students = Etudiant::all();
        foreach ($students as $student) {
            $student->utilisateur;
        }
        return response([
            'status' => 'OK',
            'record' => $students
        ], 200);
    }

    public function show (int $id) {
        $student = Etudiant::where('utilisateur_id', $id)->first();
        if($student != null){
            $student->utilisateur;
        }
        return response ([
            'status' => 'OK',
            'record' => $student
        ], 200);
    }

    /**
     * Fonction qui permet d'enregistrer un nouveau utilisateur.
     */
    public function store (Request $request) {
        try {
            // Validation.
            $validate = Validator::make($request->all(), array_merge(Utilisateur::$rules, Etudiant::$rules));
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
                'mot_pass' => '237',
                'statut' => 'Student'
            ]);
            $student = Etudiant::create([
                'utilisateur_id' => $user->id,
                'departement' => $request->departement,
                'filiere' => $request->filiere,
                'niveau' => $request->niveau,
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
            $student = Etudiant::where('utilisateur_id', $id)->first();
            if($student == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette étudiant n'existe pas dans notre base de données."
                ], 300);
            }
            // Validation.
            $validate = Validator::make($request->all(), array_merge(Utilisateur::$rules, Etudiant::$rules));
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
                'niveau' => $request->niveau,
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
                ], 301);
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
            ], 301);
        }
    }

    public function requetes (int $id) {
        try{
            $user = Etudiant::find($id);
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cet utilisateur n'existe pas dans notre base de données."
                ], 300);
            }
            $requ = $user->requetes()->orderBy('created_at', 'desc')->get();
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
