<?php

use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\RequeteController;
use App\Http\Controllers\ServiceAccueilController;
use App\Http\Controllers\ServiceTraitantController;
use App\Http\Controllers\UtilisateurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/check-user', [UtilisateurController::class, 'check']);

// Routes pour la gesion des etudiants.
Route::get('/etudiants', [EtudiantController::class, 'index']);
Route::post('/etudiants', [EtudiantController::class, 'store']);
Route::get('/etudiants/{id}', [EtudiantController::class, 'show']);
Route::post('/etudiants/{id}', [EtudiantController::class, 'update']);
Route::get('/etudiants/delete/{id}', [EtudiantController::class, 'delete']);
Route::get('/etudiants/requetes/{id}', [EtudiantController::class, 'requetes']);

// Routes pour la gestion des services d'accueils.
Route::get('/service-accueil', [ServiceAccueilController::class, 'index']);
Route::post('/service-accueil', [ServiceAccueilController::class, 'store']);
Route::get('/service-accueil/{id}', [ServiceAccueilController::class, 'show']);
Route::post('/service-accueil/{id}', [ServiceAccueilController::class, 'update']);
Route::get('/service-accueil/delete/{id}', [ServiceAccueilController::class, 'delete']);
Route::get('/service-accueil/requetes/{id}', [ServiceAccueilController::class, 'init_requetes']);
Route::get('/service-accueil/requetes/assign/{id}', [ServiceAccueilController::class, 'assign_requetes']);
Route::get('/service-accueil/requetes/renvoie/{id}', [ServiceAccueilController::class, 'reject_requetes']);
Route::post('/service-accueil/requete/assigner', [ServiceAccueilController::class, 'assigne']);

// Routes pour la gestion des services traitants.
Route::get('/service-traitant', [ServiceTraitantController::class, 'index']);
Route::post('/service-traitant', [ServiceTraitantController::class, 'store']);
Route::get('/service-traitant/{id}', [ServiceTraitantController::class, 'show']);
Route::post('/service-traitant/{id}', [ServiceTraitantController::class, 'update']);
Route::get('/service-traitant/delete/{id}', [ServiceTraitantController::class, 'delete']);
Route::get('/service-traitant/requetes/{id}', [ServiceTraitantController::class, 'requetes']);
Route::get('/service-traitant/requetes/finish/{id}', [ServiceTraitantController::class, 'finish_requetes']);
Route::get('/service-traitant/requetes/reject/{id}', [ServiceTraitantController::class, 'reject_requetes']);

// Routes pour la gestion des requêtes.
Route::post('/requete', [RequeteController::class, 'store']);
Route::get('/requete/{id}', [RequeteController::class, 'show']);
Route::post('/requete/{id}', [RequeteController::class, 'update']);
Route::post('/requete/renvoyer/{id}', [RequeteController::class, 'renvoie']);
Route::get('/requete/valider/{id}', [RequeteController::class, 'valider']);
Route::post('/requete/traiter/{id}', [RequeteController::class, 'traiter']);
Route::post('/requete/rejeter/{id}', [RequeteController::class, 'rejeter']);
Route::post('/requete/assign/{id}', [RequeteController::class, 'assigner']);
Route::get('/requete/delete/{id}', [RequeteController::class, 'delete']);
Route::post('/requete/piece-jointe/{id}', [RequeteController::class, 'ajouter_piece']);
Route::post('/requete/piece-jointe/update/{id}', [RequeteController::class, 'modifier_piece']);
Route::post('/requete/piece-jointe/delete/{id}', [RequeteController::class, 'supprimer_piece']);

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
