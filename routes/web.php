<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
/*Route::get('/home', function () {
    return view('home');
})->name('home');*/
Route::resource('/produit', 'parameter\ProdController');
Route::resource('/categorie', 'parameter\CategController');
Route::resource('/analyse', 'parameter\AnalyseController');
Route::resource('/details', 'parameter\DetailsAnalyseController');
Route::resource('/description', 'parameter\DescriptionController');
Route::resource('/valeurDescription', 'parameter\ValeurDescriptionController');
Route::resource('/qualite', 'parameter\QualiteController');
Route::resource('/valeurQualite', 'parameter\ValeurQualiteController');
Route::resource('/groupeFrs', 'parameter\FrsGRPController');
Route::resource('/groupeLocal', 'parameter\LocalGRPController');
Route::resource('/frs', 'parameter\FrsController');
Route::resource('/local', 'parameter\LocalController');


Route::get('/NouveauLot','Operation\IGUController@NouveauLot')->name('app.nouveaulot');

Route::get('/getOperationGUI','Operation\IGUController@getOperationGUI')->name('app.getOperationGUI');
Route::get('/Lot','Operation\IGUController@Lot')->name('app.lot');
Route::get('/instock','Operation\IGUController@InStock')->name('app.instock');
Route::post('/DetailsLot','Operation\IGUController@detailsLot')->name('app.detailsLot');
Route::post('/DetailsLot2','Operation\IGUController@detailsLot2')->name('app.detailsLot2');

Route::post('/GetListLocal','Operation\IGUController@getListLocal')->name('app.getListLocal');
Route::post('/GetListAnalyse','Operation\IGUController@getListAnalyse')->name('app.getListAnalyse');
Route::post('/DeleteAnalyse','Operation\IGUController@deleteAnalyse')->name('app.deleteAnalyse');

Route::post('/RegisterLot','Operation\RegisterController@RegisterNouveauLot')->name('register.nouveaulot');

Route::get('/NouveauArrivage','Operation\IGUController@NouveauArrivage')->name('app.nouveauArrivage');
Route::get('/Livraison','Operation\IGUController@getAllLivraison')->name('app.livraison');
Route::post('/Outstock','Operation\IGUController@gotToOut')->name('app.outstock');
Route::post('/Outstock2','Operation\IGUController@ExecGetToOut2')->name('app.outstock2');
Route::post('/JournalArrivageByLocal','Operation\IGUController@journalArrivageByLocal')->name('app.journalArrivageByLocal');
Route::post('/RegisterNouveauArrivage','Operation\RegisterController@RegisterNouveauArrivage')->name('register.nouveauArrivage');
Route::post('/SaveOutOfStock','Operation\RegisterController@saveOutOfStock')->name('register.saveOutOfStock');


//webservice
Route::post('/GetListProduitByCategorie','Operation\IGUController@getListProduitByCategorie')->name('app.getListProduitByCategorie');
Route::post('/GetListProduitByNumero','Operation\IGUController@getListProduitByNumero')->name('app.getListProduitByNumero');


Route::post('/GetResultatAnalyseArrivage','Operation\IGUController@getResultatAnalyseArrivage')->name('app.getResultatAnalyseArrivage');
Route::post('/GetResultatDescriptionArrivage','Operation\IGUController@getResultatDescriptionArrivage')->name('app.getResultatDescriptionArrivage');


//Route::post('/SaisieResultats','Operation\IGUController@saisieResultats')->name('app.saisieResultats');
Route::get('/SaisieResultats2','Operation\IGUController@saisieResultats2')->name('app.saisieResultats2');
Route::post('/SaisieDetailsResultats','Operation\IGUController@saisieDetailsResultats')->name('app.saisieDetailsResultats');
Route::post('/newAnalyse','Operation\IGUController@newAnalyse')->name('app.newAnalyse');
Route::get('/newAnalyse2','Operation\IGUController@newAnalyse2')->name('app.newAnalyse2');
Route::post('/SaveResultatAnalyse','Operation\RegisterController@SaveResultatAnalyse')->name('register.saveResultatAnalyse');

//Route::post('/SaisieDescription','Operation\IGUController@saisieDescription')->name('app.saisieDescription');
//Route::post('/SaisieValeurDescription','Operation\IGUController@saisieValeurDescription')->name('app.saisieValeurDescription');
Route::post('/GetValeurDescriptionInfos','Operation\IGUController@getValeurDescriptionInfos')->name('app.getValeurDescriptionInfos');
Route::post('/SaveResultatDescription','Operation\RegisterController@saveResultatDescription')->name('register.saveResultatDescription');

Route::post('/ListNumerotationAll','Operation\ReportController@listNumerotationAll')->name('report.listNumerotationAll');
Route::get('/home','Operation\ReportController@getHome')->name('home');
Route::get('/siteReport','Operation\ReportController@getSite')->name('report.site');
Route::get('/reportCreate','Operation\ReportController@create')->name('report.create');
Route::get('/Charge','Operation\ReportController@Charges')->name('report.charges');
Route::get('/Perte','Operation\ReportController@Pertes')->name('report.pertes');
Route::get('/createCharge','Operation\IGUController@createCharge')->name('app.createCharges');

Route::get('/createPerte','Operation\IGUController@createPerte')->name('app.createPerte');
Route::post('/saveNewTypeCharge','Operation\RegisterController@saveNewTypeCharge')->name('register.saveNewTypeCharge');
Route::post('/saveNewTypePerte','Operation\RegisterController@saveNewTypePerte')->name('register.saveNewTypePerte');
Route::post('/saveNewTypeProcess','Operation\RegisterController@saveNewTypeProcess')->name('register.saveNewTypeProcess');
Route::get('/displayProcess','Operation\reportController@displayProcess')->name('report.displayProcess');
Route::get('/createProcess','Operation\IGUController@createProcess')->name('app.createProcess');
Route::get('/getPersoReport','Operation\ReportController@getPerso')->name('report.getPerso');
Route::post('/getResultatData1','Operation\ReportController@getResultatData1')->name('report.getResultatData1');

Route::post('/GetListDetailsAnalyseByAnalyse','Operation\ReportController@getListDetailsAnalyseByAnalyse')->name('report.getListDetailsAnalyseByAnalyse');

Route::post('/InsertionOperation','Operation\IGUController@insertionOperation')->name('app.insertionOperation');
Route::post('/saveInsertOperation','Operation\RegisterController@saveInsertOperation')->name('register.saveInsertOperation');

Route::get('/getSituationStocksSite','Operation\IGUController@getSituationStocksSite')->name('app.getSituationStocksSite');

Route::post('/getSituationStockInSite','Operation\ReportController@getSituationStockInSite')->name('report.getSituationStockInSite');
Route::post('/getAllLocal','Operation\ReportController@getAllLocal')->name('report.getAllLocal');
Route::post('/viewDetailsByQualite','Operation\ReportController@viewDetailsByQualite')->name('report.viewDetailsByQualite');
Route::post('/getRepartitionStock','Operation\ReportController@getRepartitionStock')->name('report.getRepartitionStock');
Route::post('/IGUSelectSiteDestination','Operation\IGUController@IGUSelectSiteDestination')->name('app.IGUSelectSiteDestination');
Route::post('/IGUSelectLocalDestination','Operation\IGUController@IGUSelectLocalDestination')->name('app.IGUSelectLocalDestination');
Route::post('/saveTransfertStock','Operation\RegisterController@saveTransfertStock')->name('register.saveTransfertStock');
Route::post('/getListeTransfertEnCours','Operation\ReportController@getListeTransfertEnCours')->name('report.getListeTransfertEnCours');
Route::get('/IGUInventaire','Operation\IGUController@IGUInventaire')->name('app.IGUInventaire');

Route::get('/getIGUDescription','Operation\IGUController@getIGUDescription')->name('app.getIGUDescription');

Route::post('/newDescription','Operation\IGUController@newDescription')->name('app.newDescription');
Route::post('/getInventoryInfos','Operation\ReportController@getInventoryInfos')->name('report.getInventoryInfos');

Route::post('/getQuantiteByQualite','Operation\ReportController@getQuantiteByQualite')->name('report.getQuantiteByQualite');
Route::post('/mvtLocalQualiteProduitByLot','Operation\ReportController@mvtLocalQualiteProduitByLot')->name('report.mvtLocalQualiteProduitByLot');
Route::post('/saveInventoryAction','Operation\RegisterController@saveInventoryAction')->name('register.saveInventoryAction');
Route::post('/viewDetailsInventory','Operation\ReportController@viewDetailsInventory')->name('report.viewDetailsInventory');
Route::post('/scriptInventaireCarton','Operation\ReportController@scriptInventaireCarton')->name('report.scriptInventaireCarton');
Route::post('/headOfInventory','Operation\ReportController@headOfInventory')->name('report.headOfInventory');
Route::post('/soldeArrProdQlt','Operation\ReportController@soldeArrProdQlt')->name('report.soldeArrProdQlt');
Route::get('nouveauInv/{id}',function($id){
    return view('operation.inventaire.addInventory')->with(['mx'=>$id]);
})->name('app.addNoewInv');
Route::get('nouveauInv1/{id}',function($id){
    return view('operation.inventaire.inventaire')->with(['inv'=>$id]);
})->name('app.addNoewInv1');


Route::post('/descriptionDetailsInv','Operation\ReportController@descriptionDetailsInv')->name('report.descriptionDetailsInv');
Route::post('/descriptionDetailsInv1','Operation\ReportController@descriptionDetailsInv1')->name('report.descriptionDetailsInv1');

Route::post('/saveInventoryDetails','Operation\RegisterController@saveInventoryDetails')->name('register.saveInventoryDetails');
Route::get('/IGUQualiteReport','Operation\IGUController@IGUQualiteReport')->name('app.IGUQualiteReport');
 
Route::post('/allDescriptionByLotQualite','Operation\ReportController@allDescriptionByLotQualite')->name('report.allDescriptionByLotQualite');
Route::post('/viewCompletedetiquette','Operation\ReportController@viewCompletedetiquette')->name('report.viewCompletedetiquette');
Route::post('/infoSite','Operation\ReportController@infoSite')->name('report.infoSite');
Route::post('/infosArrForTrans','Operation\ReportController@infosArrForTrans')->name('report.infosArrForTrans');
Route::post('/transAndinv','Operation\ReportController@transAndinv')->name('report.transAndinv');
Route::get('/getIGUReportCharge','Operation\IGUController@getIGUReportCharge')->name('app.getIGUReportCharge');
Route::post('/reportPerteByLot','Operation\ReportController@reportPerteByLot')->name('report.reportPerteByLot');
Route::post('/reportPrixByLot','Operation\ReportController@reportPrixByLot')->name('report.reportPrixByLot');
Route::post('/reportPrixByArrivage','Operation\ReportController@reportPrixByArrivage')->name('report.reportPrixByArrivage');
Route::post('/reportPerteByArrivage','Operation\ReportController@reportPerteByArrivage')->name('report.reportPerteByArrivage');
Route::post('/reportChargeByArrivage','Operation\ReportController@reportChargeByArrivage')->name('report.reportChargeByArrivage');
Route::post('/convertInvToTrans','Operation\RegisterController@convertInvToTrans')->name('register.convertInvToTrans');
Route::post('/getArrivageQUDetails','Operation\ReportController@getArrivageQUDetails')->name('report.getArrivageQUDetails');


Route::get('/ListingInventaire','Operation\ReportController@ListingInventaire')->name('report.ListingInventaire');
Route::post('/getArrivageInfos','Operation\ReportController@getArrivageInfos')->name('report.getArrivageInfos');
Route::get('/getIGUSuiviQualite','Operation\IGUController@getIGUSuiviQualite')->name('app.getIGUSuiviQualite');
Route::get('/showTraitement','Operation\IGUController@showTraitement')->name('app.showTraitement');
Route::post('/registerProcessData','Operation\RegisterController@registerProcessData')->name('register.registerProcessData');
Route::post('/situationArrivageLocal','Operation\IGUController@situationArrivageLocal')->name('app.situationArrivageLocal');

Route::get('/viewOut','Operation\IGUController@viewOut')->name('app.viewOut');
Route::get('/viewPrix','Operation\IGUController@viewPrix')->name('app.viewPrix');
Route::get('/viewCharge','Operation\IGUController@viewCharge')->name('app.viewCharge');
Route::post('/saveAchat','Operation\RegisterController@saveAchat')->name('register.saveAchat');
Route::post('/saveCharge','Operation\RegisterController@saveCharge')->name('register.saveCharge');
Route::post('/savePerte','Operation\RegisterController@savePerte')->name('register.savePerte');
Route::post('/Arrivage','Operation\ReportController@Arrivage')->name('report.Arrivage');
Route::post('/mxByArrProdQual','Operation\ReportController@mxByArrProdQual')->name('report.mxByArrProdQual');

Route::post('/situationInitLot','Operation\ReportController@situationInitLot')->name('report.situationInitLot');
Route::post('/situationActuelLot','Operation\ReportController@situationActuelLot')->name('report.situationActuelLot');
Route::post('/situationActuelLotBySite','Operation\ReportController@situationActuelLotBySite')->name('report.situationActuelLotBySite');
Route::post('/evoPrix','Operation\ReportController@evoPrix')->name('report.evoPrix');




