<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UtilisateurController extends Controller
{

    public function check (Request $request){
        try{
            $validate = Validator::make($request->all(), [
                'matricule' =>  'required',
                'password' => 'required'
            ]);
            if($validate->fails()){
                return response ([
                    'status' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $user = Utilisateur::where('matricule', $request->matricule)
                ->where('mot_pass', $request->password)
                ->first();
            return response([
                'status' => 'OK',
                'elet' => $request->all(),
                'record' => $user
            ]);
        }catch(Exception $th){
            return response([
                'status' => 'OK',
                'errors' => $th->getMessage()
            ], 300);
        }
    }

    /**
     * Fonction qui permet d'enregistrer un nouveau utilisateur.
     */
    public function store (Request $request) {
        try {
            // Validation.
            $validate = Validator::make(Utilisateur::$rules, $request);
            if($validate->fails()){
                return response([
                    'status' => 'OK',
                    'errors' => $validate->errors()
                ], 300);
            }
            $user = Utilisateur::create($request->all());
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
     * Fonction qui permet de faire la mis à jour d'un utilisateur.
     */
    public function update (int $id, Request $request) {  
        try {
            $user = Utilisateur::find($id);
            if($user == null){
                return response([
                    'status' => 'OK',
                    'errors' => "Cette utilisateur n'existe pas dans notre base de données."
                ], 300);
            }
            $user->update($request->all());
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
}
