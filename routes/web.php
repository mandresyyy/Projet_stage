<?php

use App\Http\Controllers\Contr_general;
use App\Http\Controllers\File_Contr;
use App\Http\Controllers\Import_csv;
use App\Http\Controllers\Infra_Contr;
use App\Http\Controllers\Logs_Contr;
use App\Http\Controllers\Operateur_Contr;
use App\Http\Controllers\Techno_Contr;
use App\Http\Controllers\Type_site_Contr;
use App\Http\Controllers\UtilisateurContr;
use App\Models\Type_site;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contr_releve_signal;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('Admin/map_liste');
// });

Route::get('/',[Contr_general::class,"to_login"])->name('login');

Route::get('/rr',[Import_csv::class,"importCSV"])->name('imp');
Route::post('/authentification',[UtilisateurContr::class,"se_Connecter"])->name('se_connecter');
Route::get('/admin/map',[Contr_general::class,"to_acceuil_admin"])->name('admin.acceuil');
Route::get('/admin/download',[File_Contr::class,"get_model"])->name('getmodel');
Route::POST('/admin/upload',[File_Contr::class,"import_csv"])->name('upload');
Route::get('/admin/utilisateur',[Contr_general::class,"create_user"])->name('utilisateur.form');
Route::POST('/admin/nouveau_utilisateur',[UtilisateurContr::class,"create_user"])->name('utilisateur.save');
Route::get('/admin/utilisateurs',[UtilisateurContr::class,"liste_user"])->name('utilisateur.liste');
Route::get('/admin/utilisateur/{idUpdate}',[UtilisateurContr::class,"to_modif"])->name('utilisateur.info');
Route::POST('/admin/utilisateur/modification',[UtilisateurContr::class,"update_user"])->name('utilisateur.update');
Route::get('/admin/deconnecte',[UtilisateurContr::class,"log_out"])->name('user.log_out');
Route::get('/admin/operateurs',[Operateur_Contr::class,"liste"])->name('operateur.liste');
Route::get('/admin/operateur',[Operateur_Contr::class,"form"])->name('operateur.form');
Route::POST('/admin/nouveau_operateur',[Operateur_Contr::class,"save"])->name('operateur.save');
Route::get('/admin/operateur/{idUpdate}',[Operateur_Contr::class,"modif"])->name('operateur.info');
Route::POST('/admin/operateur/modification',[Operateur_Contr::class,"update"])->name('operateur.update');

Route::get('/admin/technologies',[Techno_Contr::class,"liste"])->name('techno.liste');
Route::get('/admin/technologie',[Techno_Contr::class,"form"])->name('techno.form');
Route::POST('/admin/nouveau_technologie',[Techno_Contr::class,"save"])->name('techno.save');
Route::get('/admin/technologie/{idUpdate}',[Techno_Contr::class,"modif"])->name('techno.info');
Route::post('/admin/technologie/modification',[Techno_Contr::class,"update"])->name('techno.update');

Route::get('/admin/type_sites',[Type_site_Contr::class,"liste"])->name('type.liste');
Route::get('/admin/type_site',[Type_site_Contr::class,"form"])->name('type.form');
Route::POST('/admin/nouveau_type_site',[Type_site_Contr::class,"save"])->name('type.save');
Route::get('/admin/type_site/{idUpdate}',[Type_site_Contr::class,"modif"])->name('type.info');
Route::post('/admin/type_site/modification',[Type_site_Contr::class,"update"])->name('type.update');

Route::get('/admin/profile',[Contr_general::class,"profile"])->name('admin.profile');
Route::get('/utilisateur/profile',[Contr_general::class,"user_profile"])->name('user.profile');
Route::POST('/mise_a_jour_profile',[UtilisateurContr::class,"update_profile"])->name('user.profil.update');
Route::POST('/mise_a_jour/mot_de_passe',[UtilisateurContr::class,"change_password"])->name('user.mdp.update');

Route::get('/admin/infrastructures',[Infra_Contr::class,"liste"])->name('infra.liste');
Route::get('/admin/infrastructure',[Infra_Contr::class,"form"])->name('infra.form');
Route::get('/admin/infrastructure/{idUpdate}',[Infra_Contr::class,"info"])->name('infra.info');
Route::get('/admin/auto',[Infra_Contr::class,"auto"])->name('auto');
Route::get('/admin/auto/proprio',[Infra_Contr::class,"suggest_proprio"])->name('auto_proprio');
Route::get('/admin/commune/region',[Infra_Contr::class,"get_commune"])->name('comm_region');
Route::post('/infra/display',[Infra_Contr::class,"display"])->name('display');
Route::post('/infra/nouvelle_infra',[Infra_Contr::class,"save"])->name('infra.save');
Route::post('/infra/mise_a_jour/information',[Infra_Contr::class,"update"])->name('infra.update');
Route::post('/infra/mise_a_jour/technique',[Infra_Contr::class,"update_technique"])->name('infra.update2');
Route::get('/admin/mise_a_jour/etat/{id}',[Infra_Contr::class,"changer_etat"])->name('infra.etat');

Route::get('/admin/search/infra',[Infra_Contr::class,"search"])->name('infra.search');
Route::get('/admin/search/utilisateur',[UtilisateurContr::class,"search"])->name('user.search');

Route::get('/admin/infra/upload',[Infra_Contr::class,"formupload"])->name('infra.upload_form');

Route::get('/user/load',[Contr_general::class,"loadData"]);

Route::get('/utilisateur/map',[Contr_general::class,"to_acceuil_user"])->name('user.acceuil');

Route::get('/logs',[Logs_Contr::class,"liste"])->name('logs');
Route::post('/log/search',[Logs_Contr::class,"search"])->name('logs.search');

Route::get('/admin/map/releve_signal',[Contr_releve_signal::class,"MapReleve"])->name('admin.releve_signal');
Route::get('/utilisateur/map/releve_signal',[Contr_releve_signal::class,"MapReleveUser"])->name('utilisateur.releve_signal');
Route::get('/new_releve_signal',[Contr_releve_signal::class,"FormReleve"])->name('new.releve_signal');
Route::post('/new_releve_signal/save',[Contr_releve_signal::class,"upload"])->name('releve_signal.save');
// Route::get('/teste',[Contr_general::class,"test"])->name('test');
