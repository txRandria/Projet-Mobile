<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Numerotatiom;
use App\Arrivage;
use App\Qualite;
use App\Produit;
use App\Analyse;
use App\DetailsAnalyse;
use App\Description;
use App\ValeurDescription;
use App\Fournisseur;
use App\Local;
use App\LocalGRP;
use App\Perte;
use App\Charge;
use App\Transfert;
use App\DeclCharge;
use App\DeclEtape;
use App\Etape;
use App\MX;

class IGUController extends Controller
{
    public function NouveauLot(){
        $Category=Category::all();
        return view('operation.lot.nouveau')->with(['category'=>$Category]);
    }
    public function deleteAnalyse(Request $request){
        Analyse::destroy($request->id);
    }
    public function Lot(){
        $Numerotatiom=Numerotatiom::all();
        return view('operation.lot.listLot')->with(['lots'=>$Numerotatiom]);
    }
    
    public function detailsLot(Request $request){
        $ret=[];
        $lot=Numerotatiom::where('code',$request->id)->first();
        if($lot){
            $Arrivage=Arrivage::where('lot',$request->id)->get();
            foreach($Arrivage as $s){
                $ttmp=[];
                $ttmp['data']=$s;
                $ttmp['mvt']=$this->getMVTArrId($s->id)->get();
                $ttmp['lot']=$lot;
                $ret[$s->id]=$ttmp;
            }
        }
        return $ret;
    }

     public function getAllLivraison(){
        $Category=Category::orderBy('name','asc')->get();
        $lots=[];
        $qualite=[];
        $tmp=Qualite::all();
        foreach($tmp as $item){
            $qualite[$item->id]=$item;
        }
        $produit=[];
        $type=[];
        $tmp=Produit::all();
        foreach($tmp as $item){
            $produit[$item->id]=$item;
            $q=Qualite::where('categorie',$item->categorie)->where('class',$item->class)->get();
            foreach($q as $qu){
                $type[$item->id.'-'.$qu->id]=['prod'=>$item->name,'qlt'=>$qu->name];
            }
        }
        foreach($Category as $c){
            $lots[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get();
        }        
        return view('operation.livraison.livraison')->with(['lots'=>$lots,'categories'=>$Category,'qualite'=>$qualite,'produit'=>$produit,'type'=>$type]);
    }
    public function ExecGetToOut2(Request $request){
       $site=LocalGRP::find($request->site);
       $arrivage=Arrivage::find($request->arr);
       $inner='<h5><b>Répartition dans '.$site->groupe.'</b></h5>
       <table class="table table-sm table-bordered">';
       $local=Local::where('groupe',$site->groupe)->get();
       foreach ($local as $l) {
            $info=$this->soldeArrivage2($l,$arrivage);
            $inner.='<tr onclick="journalArrivageByLocal(\''.$l->id.'\',\''.$request->arr.'\',\''.$info['solde'].'\')"><th>'.$l->name.'</th><td>'.$info['solde'].'</td><td><span class="badge badge-primary">'.$info['count'].' mouvement(s)</span></td></tr>';
       }
       $inner.='</table>';
       return $inner;
    }
    public function gotToOut(Request $request){
        $inner='';
        $counter=$request->count;
        $site=LocalGRP::all();

        for($i=0;$i<$counter;$i++){
            $id=$request['list_'.$i];
            $arrivage=Arrivage::find($id);
           // $site=LocalGRP::all();
           // $mvt=$arrivage->mvtStocks()->orderBy('siteAttachable_id','asc')->get();
            $inner.='<div id="c_'.$id.'">'.$arrivage->lot.' => '.$arrivage->produit.' '.$arrivage->qualite.'<hr>
            <div class="row">
            <div class="col-md-6">
            <table class="table table-sm table-bordered">';
            foreach ($site as $value) {
                $info=$this->soldeArrivage($value,$arrivage);
                $inner.='<tr><td><a href="#" class="nav-item" onclick="GoToOut2(\''.$id.'\',\''.$value->id.'\')">'.$value->groupe.'</a></td><td>'.$info['solde'].'</td><td><span class="badge badge-danger">'.$info['count'].' mouvement(s)</span></td></tr>';
            }
            $inner.='</table>
            </div>
            <div class="col-md-6" id="details-site_'.$id.'">
                <center>
                </center>
            </div>
            </div>
            <div id="saisi_'.$id.'">
            </div>
            ';
            $inner.='</div><hr><hr>';
        }
        
        return $inner;
    }
    ///a supprimer 
    public function insertionOperation(Request $request){
        $inner='';
        $counter=$request->count;
        $raison=[];
        if($request->type=='prix'){
            for($i=0;$i<$counter;$i++){
            $id=$request['list_'.$i];
            $arrivage=Arrivage::find($id);
            $inner.='<div id="jk-'.$id.'">
                <div class="row">
                <div class="col-sm-3">Date : <input type="date" class="form-control" id="daty-'.$id.'" value="'.$arrivage->date_arrive.'"/></div>
                <div class="col-sm-4"></div>
                <div class="col-sm-4"></div>
                </div>
                <div class="row" id="insert_'.$id.'">';
            $inner.='<div class="col-sm-3">Quantité :';
            $inner.='<input type="number" class="form-control" step="any" min="0" id="qte-'.$id.'" value="'.$arrivage->stock.'" onblur="faitCalcul('.$id.')"/>
            </div>';
            
            $inner.='<div class="col-sm-3">Prix unitaire : <input type="text" class="form-control" id="pu-'.$id.'" value="0" onblur="faitCalcul('.$id.')"/></div>';
            $inner.='<div class="col-sm-3">Taxe : <input type="text" class="form-control" id="tx-'.$id.'" value="0"/></div>';
            $inner.='<div class="col-sm-3">Montant total : <input type="text" class="form-control" id="mtt-'.$id.'"/></div>';
            $inner.='</div>
                <div class="row">
                <div class="col-sm-8">Contact commercial : <input type="text" class="form-control" id="comm-'.$id.'"/></div>
                <div class="col-sm-4"></div>
                <div class="col-sm-4"></div>
                </div>
                <div class="row">
                <div class="col-sm-8">Observations : <input type="text" class="form-control" id="obs-'.$id.'" value="'.$arrivage->comment.'"/></div>
                <div class="col-sm-4"></div>
                <div class="col-sm-4"></div>
                </div>
                <br>
                <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4"><button class="btn btn-block btn-primary" onclick="saveInsertOperation(\''.$request->type.'\',\''.$id.'\')">Enregistrer</button></div>
                <div class="col-sm-4"></div>
                </div><hr><br></div>';
          
        }
        return $inner;
    }

        if($request->type=='charge'){
            $raison=Charge::all();
        
            for($i=0;$i<$counter;$i++){
                $id=$request['list_'.$i];
                $arrivage=Arrivage::find($id);
                $inner.='<div class="row" id="insert_'.$id.'">';
                $inner.='<div class="col-sm-4">Type :
                    <select class="form-control" id="raison_'.$id.'">';
                    foreach ($raison as $value) {
                        $inner.='<option>'.$value->type.'</option>';
                    }

                $inner.='</select>
                </div>';
                
                $inner.='<div class="col-sm-3">Valeur : <input type="text" class="form-control" id="value-'.$id.'"/></div>';
                $inner.='<div class="col-sm-5">Comment : <input type="text" class="form-control" id="comment-'.$id.'"/></div>';
                $inner.='</div>
                    <br>
                    <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4"><button class="btn btn-block btn-primary" onclick="saveInsertOperation(\''.$request->type.'\',\''.$id.'\')">Enregistrer</button></div>
                    <div class="col-sm-4"></div>
                    </div><hr><br>
                ';
            }
        }else{
                $raison=Perte::all();
                for($i=0;$i<$counter;$i++){
                    $id=$request['list_'.$i];
                    $arrivage=Arrivage::find($id);
                    $local=Local::all();
                    foreach ($local as $l) {
                        $stock=$this->soldeArrivage2($l,$arrivage);
                        if($stock['solde']>0){
                            $inner.='<div id="insert_'.$id.'-'.$l->id.'">';
                            $inner.='<div class="row"><div class="col-sm-5">Localisation : <input type="text" class="form-control" id="local-'.$id.'-'.$l->id.'" value="'.$l->name.'" readonly/></div></div>';
                            $inner.='<div class="row"><div class="col-sm-4">Type :
                                <select class="form-control" id="raison_'.$id.'-'.$l->id.'">';
                                foreach ($raison as $value) {
                                    $inner.='<option>'.$value->type.'</option>';
                                }

                            $inner.='</select>
                            </div></div>';
                            
                            $inner.='<div class="row">
                            <div class="col-sm-5">Quantité du stock avant constat : <input type="number" class="form-control" id="maxvalue-'.$id.'-'.$l->id.'" value="'.$stock['solde'].'"/></div></div>';
                            $inner.='<div class="row"><div class="col-sm-3">Quantité perdue : <input type="number" class="form-control" id="pvalue-'.$id.'-'.$l->id.'"/></div></div>';
                            $inner.='<div class="row"><div class="col-sm-5">Comment : <input type="text" class="form-control" id="comment-'.$id.'-'.$l->id.'"/></div></div>';
                            $inner.='</div>
                                <br>
                                <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4"><button class="btn btn-block btn-primary" onclick="saveInsertOperation(\''.$request->type.'\',\''.$id.'-'.$l->id.'\')">Enregistrer</button></div>
                                <div class="col-sm-4"></div>
                                </div><hr><br></div>
                            ';                           
                        }

                    }
                    
                }
            }
            return $inner;
        }
    private function soldeArrivage($site,$arrivage){
        //$solde=0;
        $info['solde']=0;
        $info['count']=0;
        $mvt=$arrivage->mvtStocks()->where('siteAttachable_id',$site->id)->orderBy('id','asc')->get();
        foreach ($mvt as $item) {
            $info['solde']+=$item->input-$item->output;
            $info['count']++;
        }
        return $info;
    }
    public function journalArrivageByLocal(Request $request){
        $inner='
        <table class="table table-sm table-bordered table-primary table-striped">
        <tr><th>Date</th><th>IN</th><th>OUT</th><th>Soldes</th><th>Description</th><th>Comment</th></tr>';
        $arrivage=Arrivage::find($request->arrId);

        $mvt=$arrivage->mvtStocks()->where('localAttachable_id',$request->localID)->orderBy('id','asc')->get();
        $soldes=0;
        foreach ($mvt as $item) {
            $soldes+=$item->input-$item->output;
            $inner.='<tr><th>'.$item->created_at.'</th><td>'.$item->input.'</td><td>'.$item->output.'</td><td>'.$soldes.'</td><td>'.$item->description_mvt.'</td></tr>';
        }
        $inner.='</table>';
        return $inner;
    }
    private function soldeArrivage2($local,$arrivage){
        $info['solde']=0;
        $info['count']=0;

        $mvt=$arrivage->mvtStocks()->where('localAttachable_id',$local->id)->orderBy('id','asc')->get();
        foreach ($mvt as $item) {
            $info['solde']+=$item->input-$item->output;
            $info['count']++;
        }
        return $info;
    }
    public function getListLocal(Request $request){
        $Local=Local::where('groupe',$request->groupe)->orderBy('name','asc')->get();
        return $Local;
    }

    public function saisieDetailsResultats(Request $request){
        $DetailsAnalyse=DetailsAnalyse::where('analyse',$request->analyse)->orderBy('name','asc')->get();
        $ret='<div class="row mb-3">
        <div class="col-sm-3 my-auto"><span class="form-control bg-warning">Objet d\'analyse</span></div>
        <div class="col-sm-8"><select class="form-control" id="objet" onblur="writeResultat()"><option>selectionner un objet</option>';
        foreach($DetailsAnalyse as $det){
            $ret.='<option>'.$det->name.'</option>';
        }
        $ret.='</select>
        </div></div>
        <div class="row mb-3">
        <div class="col-sm-3  "><span class="form-control bg-warning">Valeur : </span></div>
        <div class="col-sm-5" id="valeur-espace"></div>
        <div class="col-sm-3 my-auto" id="unite"></div>
        </div>
        <div class="row mb-3">
        <div class="col-sm-3  "><span class="form-control bg-warning">Date : </span></div>
        <div class="col-sm-5" id="date-espace"></div>
        </div>
        <div class="row mb-3">
        <div class="col-sm-3  "><span class="form-control bg-warning">Quantite echantillon : </span></div>
        <div class="col-sm-5" id="echantillon-espace"></div>
        </div>
        <center id="confirm"></center>
        <br><br>';
        return $ret;
    }
   
    public function NouveauArrivage(){
        $categ=Category::all();
        $produit=[];
        $qualite=[];
        $lots=[];
        foreach($categ as $c){
            $produit[$c->name]=Produit::where('categorie',$c->name)->orderBy('name','asc')->get();
            $lots[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get();
            $qualite[$c->name]['Brute']=Qualite::where('categorie',$c->name)->where('class','Brute')->orderBy('name','asc')->get();
            $qualite[$c->name]['intermédiaire']=Qualite::where('categorie',$c->name)->where('class','intermédiaire')->orderBy('name','asc')->get();
            $qualite[$c->name]['Final']=Qualite::where('categorie',$c->name)->where('class','Final')->orderBy('name','asc')->get();
        }
/*
        $lots=Numerotatiom::orderBy('id','desc')->get();
        $qualite=Qualite::orderBy('name','asc')->get();*/
        $LocalGRP=LocalGRP::orderBy('groupe','asc')->get();

        $Fournisseur=Fournisseur::orderBy('name','asc')->get();
        return view('operation.livraison.nouveau')->with(['categ'=>$categ,'lots'=>$lots,'produit'=>$produit,'qualite'=>$qualite,'frs'=>$Fournisseur,'grpLocal'=>$LocalGRP]);
    }
    public function getListProduitByCategorie(Request $request){
        $categorie=$request->categorie;
        $produits=Produit::where('categorie',$categorie)->orderBy('name','asc')->get();
        return  $produits;
    }
    public function getListProduitByNumero(Request $request){
        $lot=Numerotatiom::where('code',$request->numero)->first();
        $produits=Produit::where('categorie',$lot->categorie)->get();
        return  $produits;
    }
    public function getListQualite(){
        $qualite=Qualite::all();
        return $qualite;
    }
    public function getResultatAnalyseArrivage(Request $request){
        $id=$request->id;
        $Arrivage=Arrivage::find($id);
        return $Arrivage->resultatsAnalyse()->get();
    }

    public function getResultatDescriptionArrivage(Request $request){
        $id=$request->id;
        $Arrivage=Arrivage::find($id);
        return $Arrivage->resultatsDescription()->get();
    }


    public function getValeurDescriptionInfos(Request $request){
        return ValeurDescription::where('valeur',$request->id)->get();
    }
    public function InStock(){
        $Numerotatiom=Numerotatiom::orderBy('id','desc')->get();
        return view('operation.mouvement.input')->with(['lots'=>$Numerotatiom]);
    }
    public function newDescription(Request $request){
        $mvt=MX::find($request->id);
        $analyse=$mvt->resultatsDescription()->get();

        $arrId=$mvt->arrId;
        $arr=Arrivage::find($arrId);
        $allMvt=$this->getMVTArrId($arrId);
        $allResults=[];
        foreach($allMvt as $m){
            $tmpRes=$m->resultatsDescription()->get();
            foreach($tmpRes as $tmp){
                array_push($allResults,$tmp);
            }    
        }
        $ret=[];
        $ret['mvt']=$mvt;
        $ret['arr']=$arr;
        $ret['all']=$allMvt;
        $ret['description']=$analyse;
        $ret['alldescription']=$allResults;
        return $ret;
    }
    public function newAnalyse(Request $request){
        $mvt=MX::find($request->id);
        $analyse=$mvt->resultatsAnalyse()->get();

        $arrId=$mvt->arrId;
        $arr=Arrivage::find($arrId);
        $allMvt=$this->getMVTArrId($arrId);
        $allResults=[];
        foreach($allMvt as $m){
            $tmpRes=$m->resultatsAnalyse()->get();
            foreach($tmpRes as $tmp){
                array_push($allResults,$tmp);
            }    
        }
        $ret=[];
        $ret['mvt']=$mvt;
        $ret['arr']=$arr;
        $ret['all']=$allMvt;
        $ret['analyse']=$analyse;
        $ret['allres']=$allResults;
        return $ret;
    }
    public function newAnalyse2($id){
        return view('operation.resultat.newAnalyseByMx');
    }
    public function saisieResultats2(){
        $categorie=Category::all();
        $categ=[];
        $lot=[];
        $produit=[];
        $qualite=[];
        $groupeAnalyse=Analyse::all();
        $detailsAnalyse=[];

        $descriptions=Description::all();
        $critereDescription=[];
        $tmp=Produit::all();
        $local=Local::all();
        $Local=[];
        $qualite1=[];
        foreach($local as $l){
            $Local[$l->id]=$l;
        }
        foreach($tmp as $p){
            $produit[$p->id]=$p;
            $qualite1[$p->categorie][$p->class]=Qualite::where('categorie',$p->categorie)->where('class',$p->class)->get();
        }
        $tmp=Qualite::all();
        foreach($tmp as $p){
            $qualite[$p->id]=$p;
        }

        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get();
            $categ[$c->id]=$c; 
        }
        foreach($groupeAnalyse as $grp){
            $detailsAnalyse[$grp->id]=DetailsAnalyse::where("analyse",$grp->name)->get();
        }
        return view('operation.resultat.nouveauSaisi')->with(['qualite1'=>$qualite1,'local'=>$Local,'categorie'=>$categ,'lot'=>$lot,'produit'=>$produit,'qualite'=>$qualite,'groupe'=>$groupeAnalyse,'analyse'=>$detailsAnalyse]); 
    }
    

    public function getOperationGUI(){
        return view('parameters.operation.operation');
    }
    public function createCharge(){
        return view('parameters.operation.new_charge');
    }
    public function createPerte(){
        return view('parameters.operation.new_perte');
    }
    public function createProcess(){
        $categ=Category::all();
        return view('parameters.operation.new_traitement')->with(['categ'=>$categ]);
    }
    public function getSituationStocksSite(){
        $site=LocalGRP::all();
        $local=[];
        foreach($site as $s){
            $local[$s->id]=Local::where('groupe',$s->groupe)->get();
        }
        $categ=Category::all();
        $produit=[];
        $tmp=Produit::all();
        foreach($tmp as $item){
            $produit[$item->id]=$item;
        }
        $qualite=[];
        $tmp=Qualite::all();
        foreach($tmp as $item){
            $qualite[$item->id]=$item;
        }
        /*foreach($categ as $c){
            $produit[$c->name]=Produit::where('categorie',$c->name)->get();
        }*/
        return view('operation.livraison.stocks')->with(['site'=>$site,'categorie'=>$categ,'produit'=>$produit,'qualite'=>$qualite,'local'=>$local]);
    }
    public function IGUSelectSiteDestination(Request $request){
        $site=LocalGRP::all();
        $inner='<select id="select_site" class="btn-primary form-control">';
        foreach ($site as $value) {
            if($value->groupe!=$request->site){
                $inner.='<option>'.$value->groupe.'</option>';    
            }
        }
        $inner.='</select>';
        return $inner;
    }
    public function IGUSelectLocalDestination(Request $request){
        $site=Local::where('groupe',$request->site)->get();
        $inner='<select id="select_local" class="btn-success form-control">';
        foreach ($site as $value) {

            $inner.='<option>'.$value->name.'</option>';
        }
        $inner.='</select>';
        return $inner;
    }
    public function IGUInventaire(){
        $categorie=Category::all();
        $categ=[];
        $lot=[];
        $produit=[];
        $qualite=[];
        $descriptions=Description::all();
        $tmp=Produit::all();
        $local=Local::all();
        $Local=[];
        $qualite1=[];
        foreach($local as $l){
            $Local[$l->id]=$l;
        }
        foreach($tmp as $p){
            $produit[$p->id]=$p;
            $qualite1[$p->categorie][$p->class]=Qualite::where('categorie',$p->categorie)->where('class',$p->class)->get();
        }
        $tmp=Qualite::all();
        foreach($tmp as $p){
            $qualite[$p->id]=$p;
        }
        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get();
            $categ[$c->id]=$c; 
        }
       return view('operation.inventaire.nouveau')->with(['qualite1'=>$qualite1,'local'=>$Local,'categorie'=>$categ,'lot'=>$lot,'produit'=>$produit,'qualite'=>$qualite]);
    }
    public function IGUQualiteReport(Request $request){
        $categorie=Category::all();
        $qualite=Qualite::all();
        $lots=[];
        $arr=[];
        foreach($categorie as $categ){
            $lots[$categ->name]=Numerotatiom::where('categorie',$categ->name)->get();
            foreach($lots[$categ->name] as $lot){
                foreach($qualite as $q){
                    $arr[$lot->code][$q->name]=Arrivage::where('lot',$lot->code)->where('qualite',$q->name)->get();
                }
            }
        }
        return view('reporting.qualite')->with(['categ'=>$categorie,'qualite'=>$qualite,'lots'=>$lots,'arrivage'=>$arr]);
    }
    public function insertInventaire(Request $request){
       
    }
    public function getIGUReportCharge(){
        $categorie=Category::all();
        $lot=[];
        $arr=[];
        $charge=[];
        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get(); 
            foreach( $lot[$c->name] as $l){
                //$arr[$l->id]=Arrivage::where('lot',$l->code)->orderBy('id','asc')->get();
                $charge[$l->id]=DeclCharge::where('chargeAttachable_id',$l->id)->orderBy('id','asc')->get();;
                //foreach($arr[$l->id] as $a){
                   // $vCharge=
                    /*foreach($vCharge as $index){
                        array_push($charge[$l->id],$index);
                    }*/
               // }
            }
        }
        $produit=[];
        $tmp=Produit::all();
        foreach($tmp as $item){
            $produit[$item->id]=$item;
        }
        $qualite=[];
        $tmp=Qualite::all();
        foreach($tmp as $item){
            $qualite[$item->id]=$item;
        }
        return view('reporting.charge1')->with(['categorie'=>$categorie,'lot'=>$lot,'charge'=>$charge,'produit'=>$produit,'qualite'=>$qualite]);
    }
    public function getIGUSuiviQualite(){
        $categorie=Category::all();
        $lot=[];
        $produit=[];
        $qualite=[];
        $groupeAnalyse=Analyse::all();
        $detailsAnalyse=[];
        $local=[];

        $descriptions=Description::all();
        $critereDescription=[];
        $tmp=Local::all();
        foreach($tmp as $l){
            $local[$l->id]=$l;
        }
        $tmp=Produit::all();
        foreach($tmp as $p){
            $produit[$p->id]=$p;
        }
        $tmp=Qualite::all();
        foreach($tmp as $q){
            $qualite[$q->id]=$q;
        }
        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get();
        }
        return view('reporting.suiviQualite')->with(['local'=>$local,'categorie'=>$categorie,'lot'=>$lot,'qualite'=>$qualite,'produit'=>$produit]);
    }
    public function showTraitement(){
        $categorie=Category::all();
        $lot=[];
        $etape=[];
        $produit=[];
        $tmp=Produit::all();
        foreach($tmp as $p){
            $produit[$p->id]=$p;
        }
       /* $tmp=Qualite::all();
        foreach($tmp as $p){
            $qualite[$p->id]=$p;
        }*/
        $local=[];
        $tmp=Local::all();
        foreach($tmp as $p){
            $local[$p->id]=$p;
        }
        //$qualite=[];
        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get(); 
            $etape[$c->name]=Etape::where('categorie',$c->name)->get();
            $tmp=Produit::where('categorie',$c->name)->get();
            $qualite[$c->name]=[];
            foreach($tmp as $p){
                $qualite[$c->name][$p->class]=Qualite::where('categorie',$c->name)->where('class',$p->class)->get();
            }
        }
        return view('operation.lot.traitement')->with(['local'=>$local,'categorie'=>$categorie,'lot'=>$lot,'etape'=>$etape,'produit'=>$produit,'qualite'=>$qualite]);
    }
    
    public function detailsLot2(Request $request){
        $Arrivage=Arrivage::where('lot',$request->id)->orderBy('produit','asc')->orderBy('qualite','asc')->get();
        $ret=[];
        foreach($Arrivage as $s){
            $data=[];
            $data['data']=$s;
            $data['states']=DeclEtape::where('arrivage',$s->id)->get();
            //$data['qte']=
            array_push($ret,$data);
        }
        return $ret;
    }
    public function getListAnalyse(Request $request){
        return Analyse::orderBy('name','asc')->get();
    }
    public function situationArrivageLocal(Request $request){
        $locals=Local::all();
        $stock=[];
        $arrivage=Arrivage::find($request->id);
        foreach($locals as $local){
            $stock[$local->id]=$this->soldeArrivage2($local,$arrivage);
        }
        $ret=[];
        $ret['local']=$locals;
        $ret['stock']=$stock;
        return $ret;
    }
    private function getMVTArrId($arrId){
        $data=MX::where('arrId',$arrId);
        return $data;
    }
    public function getIGUDescription(){
        $categorie=Category::all();
        $categ=[];
        $lot=[];
        $produit=[];
        $qualite=[];
        $qualite1=[];
        $groupeAnalyse=Analyse::all();
        $detailsAnalyse=[];

        $descriptions=Description::all();
        $valeur=[];
        $tmp=Produit::all();
        $local=Local::all();
        $Local=[];
        foreach($local as $l){
            $Local[$l->id]=$l;
        }
        foreach($tmp as $p){
            $produit[$p->id]=$p;
            $qualite1[$p->categorie][$p->class]=Qualite::where('categorie',$p->categorie)->where('class',$p->class)->get();
        }
        $tmp=Qualite::all();
        foreach($tmp as $p){
            $qualite[$p->id]=$p;
        }

        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get();
            $categ[$c->id]=$c; 
        }
        foreach($descriptions as $grp){
            $valeur[$grp->id]=ValeurDescription::where("description",$grp->name)->get();
        }
        return view('operation.resultat.newDescription')->with(['qualite1'=>$qualite1,'local'=>$Local,'categorie'=>$categ,'lot'=>$lot,'produit'=>$produit,'qualite'=>$qualite,'descriptions'=>$descriptions,'valeur'=>$valeur]); ;
    }
    public function viewOut(){
        $categorie=Category::all();
        $lot=[];
        $etape=Perte::all();
        $produit=[];
        $tmp=Produit::all();
        foreach($tmp as $p){
            $produit[$p->id]=$p;
        }
      
        $local=[];
        $tmp=Local::all();
        foreach($tmp as $p){
            $local[$p->id]=$p;
        }
        //$qualite=[];
        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get(); 
            //$etape[$c->name]=Etape::where('categorie',$c->name)->get();
            $tmp=Produit::where('categorie',$c->name)->get();
            $qualite[$c->name]=[];
            foreach($tmp as $p){
                $qualite[$c->name][$p->class]=Qualite::where('categorie',$c->name)->where('class',$p->class)->get();
            }
        }
        $qualite1=Qualite::all();
        return view('operation.mouvement.output')->with(['local'=>$local,'categorie'=>$categorie,'lot'=>$lot,'etape'=>$etape,'produit'=>$produit,'qualite'=>$qualite,'qualite1'=>$qualite1]);
    }
    public function viewPrix(){
        $categorie=Category::all();
        $lot=[];
        $produit=[];
        $tmp=Produit::all();
        foreach($tmp as $p){
            $produit[$p->id]=$p;
        }
        $local=[];
        $tmp=Local::all();
        foreach($tmp as $p){
            $local[$p->id]=$p;
        }
        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get(); 
            $tmp=Produit::where('categorie',$c->name)->get();
            $qualite[$c->name]=[];
            foreach($tmp as $p){
                $qualite[$c->name][$p->class]=Qualite::where('categorie',$c->name)->where('class',$p->class)->get();
            }
        }
        return view('operation.lot.prix')->with(['local'=>$local,'categorie'=>$categorie,'lot'=>$lot,'produit'=>$produit,'qualite'=>$qualite]);
    }
    public function viewCharge(){
        $categorie=Category::all();
        $lot=[];
        $produit=[];
        $charge=Charge::all();
        $tmp=Produit::all();
        foreach($tmp as $p){
            $produit[$p->id]=$p;
        }
        
        foreach($categorie as $c){
            $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->orderBy('id','desc')->get(); 
            $tmp=Produit::where('categorie',$c->name)->get();
            $qualite[$c->name]=[];
            foreach($tmp as $p){
                $qualite[$c->name][$p->class]=Qualite::where('categorie',$c->name)->where('class',$p->class)->get();
            }
        }
        return view('operation.lot.charge')->with(['charge'=>$charge,'categorie'=>$categorie,'lot'=>$lot,'produit'=>$produit,'qualite'=>$qualite]);
    }

}
