<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Numerotatiom;
use App\Arrivage;
use App\MX;
use App\Category;
use App\Produit;
use App\Qualite;
use App\LocalGRP;
use App\Local;
use App\Analyse;
use App\DetailsAnalyse;
use App\ResultatAnalyse;
use Carbon\Carbon;
use App\Charge;
use App\Perte;
use App\Etape;
use App\Transfert;
use App\Iventory;
use App\DetailsIventory;
use App\Description;
use App\colisTrans;
use App\Achat;
use App\DeclCharge;
use App\ValeurDescription;
use App\CumulVar;
class ReportController extends Controller
{
    private function ListNumerotationAll(){
    	$Numerotatiom=Numerotatiom::orderBy('id','desc')->orderBy('categorie','asc')->get();
    	return $Numerotatiom;
    }
/* public function ListOfTransfert(){
        $transfert=Transfert::where
    }*/
    public function ListArrivageAll(Request $request){

    }
    public function Arrivage(Request $request){
        return Arrivage::find($request->id);
    }
    public function getListDetailsAnalyseByAnalyse(Request $request){
        $mvt=MX::find($request->id);
        $analyse=$mvt->resultatsAnalyse()->get();
        return $analyse;
    }
    public function transAndinv(Request $request){
        $arr=Arrivage::find($request->arrId);
        $trans=Transfert::find($request->trans);
        $colis=colisTrans::where('transId',$request->trans);
        $locals=Local::all();
        $local=[];
        foreach($locals as $l){
            $local[$l->id]=$l;
        }
        $inventaire=Iventory::where('arrivage',$arr->id)->where('local',$trans->origineLocal)->where('count',NULL)->orderBy('daty','desc')->get();
        $detailsInv=[];
        foreach($inventaire as $inv){
            $detailsInv[$inv->id]=[];
            $tmp=DetailsIventory::where('inventaire',$inv->id)->get();
            foreach($tmp as $i){
                array_push($detailsInv[$inv->id],$i);
            }
        }
        $ret=[];
        $ret['arr']=$arr;
        $ret['local']=$local;
        $ret['trans']=$trans;
        $ret['colis']=$colis;
        $ret['inv']=$inventaire;
        $ret['detailsInv']=$detailsInv;
        return $ret;
    }
    public function infosArrForTrans(Request $request){
        $arr=Arrivage::find($request->arrId);
        $trans=Transfert::find($request->trans);
        $colis=colisTrans::where('transId',$request->trans);
       
        $qte=$this->getSoldeArrLocal($arr,Local::find($trans->origineLocal));
        $ret=[];
        $ret['arr']=$arr;
        $ret['colis']=$colis;
        $ret['qte']=$qte;
        $ret['transqte']=$trans->qte;
        return $ret;
    }
   
    private function getAllCategorie(){
    	$Category=Category::orderBy('name','asc')->get();
    	return $Category;
    }
    private function getProduitByCateg($cat){
        return Produit::where('categorie',$cat)->get();
    }
    private function getQualiteAll(){
        return Qualite::orderBy('name','asc')->get();
    }
    private function categRefName($ref){
        $d=Category::where('ref',$ref)->first();
        return $d->name;
    }
    //private function getQteArrivage()
    private function getDataMvtByCateg($lot,$xpot,$xq){
        $data=Arrivage::where('lot',$lot)->where('produit',$xpot)->where('qualite',$xq)->get();
        $tab[$xq][$xpot][$lot]=0;
        foreach ($data as $index) {
            $stocks=$index->mvtStocks()->get();
            foreach ($stocks as $value) {
                $tab[$xq][$xpot][$lot]+=$value->input-$value->output;    
            }
        }
        return $tab[$xq][$xpot][$lot]; 
    }
    private function getSiteByName($name){
        return LocalGRP::where('name',$name)->fisrt();
    }

    private function getDataMvtBySite($site,$xpot,$xq){
        $data=Arrivage::where('produit',$xpot)->where('qualite',$xq)->get();
        $return=0;
        foreach ($data as $index) {
            $stocks=$index->mvtStocks()->where('siteAttachable_id',$site)->get();

            foreach ($stocks as $value) {
                $return+=$value->input-$value->output;    
            }
        }
        return $return;    
    }
    public function getQuantiteByQualite(Request $request){
        $lot=explode("||",$request->lot);
        $produit=Produit::find($request->produit);
        $qualite=Qualite::where('categorie',$produit->categorie)->where('class',$produit->class)->get();
        $data=[];
        foreach($lot as $l){
            $data[$l]=[];
            $lotId=$this->getLotByCode($l);
            $qval=[];
            foreach($qualite as $qi){
                $qval[$qi->id]=$this->checkQteLotQualite($lotId->id,$produit->id,$qi->id);
            }
            $data[$l]=$qval;
        }
        $ret=[];
        $ret['produit']=$produit;
        $ret['qualite']=$qualite;
        $ret['data']=$data;
        return $ret;
    }

    private function checkQteLotQualite($lotId,$prodId,$qualiteId){
        $qte=0;
        $mvt=MX::where('lotId',$lotId)->where('qualiteId',$qualiteId)->where('produitId',$prodId)->get();
        foreach($mvt as $st){
            $qte+=$st->input-$st->output;
        }
        return $qte;
    }
    private function getLotByCode($lot){
        return Numerotatiom::where('code',$lot)->first();
    }
    public function getHome(){
    	/*$categ=$this->getAllCategorie();
        $prod=[];
        $lot=[];
        $qteLot=[];
        $qualite=[];
    	foreach($categ as $c){
           $lot[$c->name]=Numerotatiom::where('categorie',$c->name)->get();
           $tmp=$this->getProduitByCateg($c->name);
           $prod[$c->name]=$tmp;
           foreach($tmp as $pr){
                $qualite[$c->name][$pr->class]=Qualite::where('categorie',$c->name)->where('class',$pr->class)->get();     
           }
           $tmp=$lot[$c->name];
           foreach($tmp as $l){
                $qteLot[$l->code]=$this->qteByLot($l->id);
           }
        }
    	return view('home')->with(['QTELOT'=>$qteLot,'categorie'=>$categ,'produit'=>$prod,'qualite'=>$qualite,'lot'=>$lot]);
    */
        $categorie=Category::all();
        $lots=[];
        $type=[];
        $label=[];
        $data=[];
        foreach($categorie as $cat){
            $label[$cat->id]=[];
            $type[$cat->id]=[];
            $lots[$cat->id]=Numerotatiom::where('categorie',$cat->name)->get();
            $xprod=Produit::where('categorie',$cat->name)->get();
            foreach($lots[$cat->id] as $lot){
                $qteLot[$lot->id]=0;
                foreach($xprod as $prod){
                    $xqlt=Qualite::where('categorie',$cat->name)->where('class',$prod->class)->get();
                    foreach($xqlt as $q){
                        $tmp=$prod->id.'-'.$q->id;
                        $tb=$label[$cat->id];
                        if (!in_array($tmp,$tb)) {
                            array_push($label[$cat->id],$tmp);
                            array_push($type[$cat->id],$prod->name.'-'.$q->name);
                        }
                        $data[$lot->id.'-'.$prod->id.'-'.$q->id]=$this->getSoldeMX($lot->id,$prod->id,$q->id);
                    }
                }
            }
            
        }
        
        return view('home')->with(['categorie'=>$categorie,'label'=>$label,'lots'=>$lots,'data'=>$data,'type'=>$type]);
    }
    private function getSoldeMX($lotId,$prodId,$qltId){
        $solde=0;
        $tmp=MX::where('lotId',$lotId)->where('produitId',$prodId)->where('qualiteId',$qltId)->get();
        foreach($tmp as $item){
            $solde+=$item->input-$item->output;
        }
        return $solde;
    }
    public function getListeTransfertEnCours(){
        $transfert=Transfert::where('validate',NULL)->get();
        $data=[];
        foreach($transfert as $trans){
            $data[$trans->id]=colisTrans::where('transId',$trans->id)->get();
        }
        $ret=[];
        $ret['transfert']=$transfert;
        $ret['data']=$data;
        return $ret;
    }
    public function infoSite(Request $request){
        $site=LocalGRP::orderBy('groupe','asc')->get();
        $local=Local::all();
        $ret=[];
        $sit=[];
        foreach($site as $s){
            $sit[$s->id]=$s;
        }
        $ret['site']=$sit;

        $loc=[];
        foreach($local as $l){
            $loc[$l->id]=$l;
        }
        $ret['local']=$loc;
        return $ret;
    }

    public function getSite(){
        $site=[];
        $qualite=[];
        $produit=[];
        $categorie=[];
        $data=[];
        
            $site=LocalGRP::orderBy('groupe','asc')->get();
            $categorie=Category::all();
            $qualite=Qualite::all();
            foreach($categorie as $c){
                $produit[$c->name]=Produit::where('categorie',$c->name)->get();
            }
        
        foreach($site as $s){
            foreach($categorie as $categ){
                $tmp=$produit[$categ->name];
                foreach($tmp as $p){
                    foreach($qualite as $q){
                        $data[$s->groupe][$categ->ref][$p->name][$q->name]=$this->getDataMvtBySite($s->id,$p->name,$q->name);
                    }
                }
            }       
              
        }
        return view('reporting.site')->with(['site'=>$site,'produit'=>$produit,'categorie'=>$categorie,'produit'=>$produit,'data'=>$data,'qualite'=>$qualite]);
    }
    public function mvtLocalQualiteProduitByLot(Request $request){
        $local=Local::all();
        $lot=Numerotatiom::where('code',$request->id)->first();
        $produit=Produit::where('categorie',$lot->categorie)->get();
        $qualite=[];
        $data=[];
        $qte=[];
        $analyse=[];
        foreach($local as $l){
            $data[$l->name]=[];
            foreach($produit as $prod){
                $qualite=Qualite::where('categorie',$lot->categorie)->where('class',$prod->class)->get();
                $data[$l->name][$prod->name]=[];
                foreach($qualite as $q){
                    $tmp=MX::where('produitId',$prod->id)->where('qualiteId',$q->id)->where('localId',$l->id)->orderBy('arrId','asc')->orderBy('id','asc')->get();
                    $data[$l->name][$prod->name][$q->name]=$tmp;
                    $qte[$prod->id.'-'.$q->id.'-'.$l->id]=0;
                    foreach($tmp as $i){
                        $qte[$prod->id.'-'.$q->id.'-'.$l->id]+=$i->input-$i->output;
                        $analyse[$prod->id.'-'.$q->id.'-'.$l->id]=$i->resultatsAnalyse()->get();
                    }
                }
            }
        }

        return['data'=>$data,'qte'=>$qte,'analyse'=>$analyse];
    }

    public function getSituationStockInSite(Request $request){
        $site=LocalGRP::find($request->siteID);
        $categorie=Category::all();
        $lot=[];
        $local=Local::where('groupe',$site->groupe)->get();
        $produit=[];
        foreach($categorie as $cat){
            $lot[$cat->name]=Numerotatiom::where('categorie',$cat->name)->get();
            $produit[$cat->name]=Produit::where('categorie',$cat->name)->get();
        }
        $qualite=[];
        $data=[];
        $qte=[];
        $label=[];
        foreach($local as $l){
           // $data[$l->name]=[];
            foreach($categorie as $cat){
                $tmpLot= $lot[$cat->name];
                $tmpProd=$produit[$cat->name];
                foreach($tmpLot as $tmp){
                    foreach($tmpProd as $prod){
                        $qualite=Qualite::where('categorie',$cat->name)->where('class',$prod->class)->get();
                        //$data[$l->name][$prod->name]=[];
                        foreach($qualite as $q){
                            $tmpMX=MX::where('produitId',$prod->id)->where('qualiteId',$q->id)->where('localId',$l->id)->get();
                            $data[$l->id.'-'.$prod->id.'-'.$q->id]=$tmpMX;
                            $qte[$prod->id.'-'.$q->id.'-'.$l->id]=0;
                            array_push($label,$prod->id.'-'.$q->id.'-'.$l->id);
                            foreach($tmpMX as $i){
                                $qte[$prod->id.'-'.$q->id.'-'.$l->id]+=$i->input-$i->output;
                            } 
                         }
                    }
                }
            
             }
        }
        return['data'=>$data,'qte'=>$qte,'label'=>$label];
    }
    public function getFournisseur(){
        $association=[];
        $membre=[];
        $qualite=[];
        $produit=[];
        $categorie=[];
        $data=[];
        
    }
    public function getPerso(){
        $analyseType=Analyse::all();
        $categ=Category::all(); 
        $analyse=[];        
        $tab=[];
        $prod=[];
        $arrayData=[];
        $qualite=$this->getQualiteAll();
        foreach($categ as $c){
            $tab[$c->name]=[];
            $prod[$c->name]=$this->getProduitByCateg($c->name);
        }
    
        $num=$this->ListNumerotationAll();
        foreach($num as $n){
            $x=[];
            $x['num_id']=$n->id;
            $x['num_code']=$n->code;
            $x['num_qte']=$this->getQteLotAll($n->id);
            if($x['num_qte']>0){
                array_push($tab[$n->categorie],$x);
            }
        }

        return view('reporting.personalise')->with(['analyse'=>$analyse,'type'=>$analyseType,'category'=>$categ,'QTELOT'=>$tab,'qualite'=>$qualite,'produit'=>$prod,'lot'=>$num]);       
    }
    public function getResultatData1(Request $request){
         $Analyse=Arrivage::where('lot',$request->lot)->get();
         $data=[];
         $type='';
         $date=[];
         foreach ($Analyse as $a) {
             $resultats=$a->resultatsAnalyse()->get();
             $data[$a->id]=[];
             foreach ($resultats as $r) {
                //$dateCreate= new Carbon(new DateTime($r->created_at);
                //$ref=$dateCreate->year.'-'.$dateCreate->month.'-'.$dateCreate->day;
                if(!in_array($r->date_analyse, $date)){
                    array_push($date,$r->date_analyse);
                }

                $type=$r->type_analyse.":".$r->details_analyse;
                $data[$a->id][$r->details_analyse.$r->date_analyse]=$r->valeur_analyse;
             }
         }
         return ['AnalyseData'=>$data,'ArrivageData'=>$Analyse,'type'=>$type,'line'=>$date];
    }
    
    public function create(){
        $Analyse=Analyse::all();
        $categ=Category::all(); 
        $tab=[];
        $prod=[];
        $arrayData=[];
        $qualite=$this->getQualiteAll();
        foreach($categ as $c){
            $tab[$c->name]=[];
            $prod[$c->name]=$this->getProduitByCateg($c->name);
        }
    
        $num=$this->ListNumerotationAll();
        foreach($num as $n){
            $x=[];
            $x['num_id']=$n->id;
            $x['num_code']=$n->code;
            $x['num_qte']=$this->getQteLotAll($n->id);
            if($x['num_qte']>0){
                array_push($tab[$n->categorie],$x);
            }
        }

        return view('reporting.create')->with(['analyse'=>$Analyse,'category'=>$categ,'QTELOT'=>$tab,'qualite'=>$qualite,'produit'=>$prod]);       
    }
    public function Charges(){
        $charge=Charge::orderBy('type','asc')->get();
        return view('parameters.operation.charge')->with(['charges'=>$charge]);
    }
    
    public function Pertes(){
        $perte=Perte::orderBy('type','asc')->get();
        return view('parameters.operation.perte')->with(['pertes'=>$perte]);
    }
    public function displayProcess(){
        $process=Etape::all();
        $categ=Category::all();
        return view('parameters.operation.traitement')->with(['process'=>$process,'categ'=>$categ]);
    }
    public function getAllLocal(Request $request){
        $grp=LocalGRP::all();
        $data=[];
        foreach ($grp as $site) {
            $data[$site->id]=Local::where('groupe',$site->groupe)->get();
        }
        return $data;
    }
    public function viewDetailsByQualite(Request $request){
        $indexLot=[];
        $indexArr=[];
        $Lots=Numerotatiom::where('categorie',$request->categ)->get();
        $data=[];
        foreach($Lots as $lot){
            $arrivage=Arrivage::where('lot',$lot->code)->where('produit',$request->prod)->where('qualite',$request->qualite)->get();
            foreach ($arrivage as $arr) {
                $result=$this->soldeArrivage($request->siteId,$arr);
                if($result['solde']>0){
                    array_push($indexArr,$arr);
                    $data[$arr->id]=$result;
                }
            }
        }
        $ret=[];
        $ret['data']=$data;
        $ret['arr']=$indexArr;
        return $ret;
    }
    public function getRepartitionStock(Request $request){
        $arrivage=Arrivage::find($request->arrId);
        $locals=Local::where('groupe',$request->site)->get();
        $listLocal=[];
        $data=[];
        foreach ($locals as $local) {
            $info=$this->getSoldeArrLocal($arrivage,$local);
            if($info['solde']>0){
                array_push($listLocal,$local);
                $data[$local->id]=$info;
            }
        }
        $ret=[];
        $ret['local']=$listLocal;
        $ret['data']=$data;
        $ret['arr']=$arrivage;
        return $ret;
    }
    private function getSoldeArrLocal($arr,$local){
        $info=[];
        $info['solde']=0;
        $info['count']=0;
        $info['ref']=-1;
        $allMvt=$arr->mvtStocks()->where('localAttachable_id',$local->id)->orderBy('id','asc')->get();
        foreach ($allMvt as $mvt) {
            $info['solde']+=$mvt->input-$mvt->output;
            $info['count']++;
             $info['ref']=$mvt->id;
        }
        return $info;
    }
   
    public function getInventoryInfos(Request $request){
        $data=[];
        $inventaire=Iventory::where('local',$request->local)->where('arrivage',$request->id)->get();
        foreach($inventaire as $inv){
            $data[$inv->id]=DetailsIventory::where('inventaire',$inv->id)->get();
        }
        $ret=[];
        $ret['inv']=$inventaire;
        $ret['details']=$data;
        return $ret;
    }
    private function soldeArrivage($site,$arrivage){
    
        $info['solde']=0;
        $info['count']=0;
        $mvt=$arrivage->mvtStocks()->where('siteAttachable_id',$site)->orderBy('id','asc')->get();
        foreach ($mvt as $item) {
            $info['solde']+=$item->input-$item->output;
            $info['count']++;
        }
        return $info;
    }
    public function viewDetailsInventory(Request $request){
        $ret=[];
        $arr=MX::find($request->arrId);
        $inv=Iventory::find($request->inv);
        $local=Local::find($request->localId);
        $xdetails=DetailsIventory::where('inventaire',$request->inv)->get();
        $details=[];
        foreach($xdetails as $d){
            $details[$d->id]=$d;
        }
        $ret['arrivage']=$arr;
        $ret['arr']=Arrivage::find($arr->arrId);
        $ret['inventaire']=$inv;
        $ret['local']=$local;
        $ret['details']=$details;
        $ret['count']=count($details);
        return $ret;
    }
    public function scriptInventaireCarton(Request $request){
        $mx=MX::find($request->arrId);
        $arr=Arrivage::find($mx->arrId);
        $inv=Iventory::find($request->invId);
        $lot=Numerotatiom::where('code',$arr->lot)->first();
        $ret='';
        $ret.='Prod : SAHANALA '.$lot->categorie.'-'.$arr->produit.'-'.$arr->qualite;
        $ret.='Ref : Not defined';
        $ret.='Poids : '.$request->pd;
        return $ret;
    }
    public function headOfInventory(Request $request){
        $inventaire=Iventory::where('arrivage',$request->mx)->get();
        return $inventaire;
    }
    public function viewCompletedetiquette(Request $request){
        $ret=[];
        $arr=MX::find($request->arrId);
        $inv=Iventory::find($request->inv);
        $local=Local::find($arr->localId);
        $details=DetailsIventory::where('inventaire',$request->inv)->get();
        $lot=Numerotatiom::find($arr->lotId);

        foreach($details as $d){
            $tmp='Prod : SAHANALA '.$lot->categorie.'-'.Produit::find($arr->produitId)->name.'-'.Qualite::find($arr->qualiteId)->name;
            $tmp.='|Poids : '.$d->qte;
            $tmp.='|Ref : §'.$d->id.'§'.$d->ref;
            array_push($ret,$tmp);
        }
        return $ret;
    }
    private function qteByLot($lotId){
        $qte=0;
        $mvt=MX::where('lotId',$lotId)->get();
        foreach($mvt as $m){
            $qte+=$m->input-$m->output;
        }
        return $qte;
    }
    private function getInvByLocalArr($local,$arriv){
        $inv=Iventory::find($request->inv);
        return [];
    }
    public function allDescriptionByLotQualite(Request $request){
        $arrivage=Arrivage::where("lot",$request->lot)->where("qualite",$request->qualite)->get();
        $descript=[];
        $resultat=[];
        foreach($arrivage as $arr){
            $mvt=$arr->resultatsDescription()->get();
            $descript[$arr->id]=[];
            foreach($mvt as $m){
                array_push($descript[$arr->id],$m);
            }
            
            $mvt=$arr->resultatsAnalyse()->get();
            $resultat[$arr->id]=[];
            foreach($mvt as $m){
                array_push($resultat[$arr->id],$m);
            }
        }
        $ret=[];
        $ret['resultat']=$resultat;
        $ret['descript']=$descript;
        $ret['arrivage']=$arrivage;
        return $ret;
    }
    public function reportPerteByLot(Request $request){
        $lot=Numerotatiom::find($request->lotId);
        //$arr=Arrivage::where('lot',$lot->code)->get();
        $perts=Perte::all();
        $dataPerte=[];
        $tmp=CumulVar::where('lotId',$request->lotId)->get();
        foreach($perts as $p){
            $dataPerte[$p->id]=[];
        }
        
        foreach($perts as $p){
            foreach($tmp as $index){
                array_push($dataPerte[$p->id],$index);
            }
        }    
        $ret=[];
       // $ret['arr']=$arr;
        $ret['perte']=$perts;
        $ret['data']=$dataPerte;
        return $ret;
    }
    public function reportPerteByArrivage(Request $request){
        $arr=Arrivage::find($request->arrId);
        $data=[];
        $data=$arr->mvtStocks()->where('solde','>',0)->get();
        $ret=[];
        $ret['arr']=$arr;
        $ret['data']=$data;
        return $ret;
    }
    public function reportPrixByArrivage(Request $request){
        $arr=Arrivage::find($request->arrId);
        $data=[];
        $tmp=Achat::where('achatAttachable_id',$request->arrId)->get();
        $count=0;
        foreach($tmp as $d){
            $data=$d;
            $count++;
        }

        $ret=[];
        $ret['arr']=$arr;
        $ret['nb']=$count;
        $ret['data']=$data;
        $ret['taxe']=0;
        $ret['ht']=0;
        $ret['ttc']=0;
        if($count>0){
            $ret['taxe']=$data->taxe*$arr->stock*$data->prix*0.01;
            $ret['ht']=$arr->stock*$data->prix;
            $ret['ttc']=$ret['taxe']+$ret['ht'];
        }
        return $ret;
    }
    public function reportChargeByArrivage(Request $request){
        $arr=Numerotatiom::find($request->arrId);
        $data=[];
        $data=DeclCharge::where('chargeAttachable_id',$request->arrId)->get(); 
        $ret=[];
        $ret['arr']=$arr;
        $ret['data']=$data;
        return $ret;
    }
    public function reportPrixByLot(Request $request){
        $lot=Numerotatiom::find($request->lotId);
        $arr=Arrivage::where('lot',$lot->code)->get();
        $data=[];
        $arrivage=[];
        foreach($arr as $item){
            $arrivage[$item->id]=$item;
            $tmp=Achat::where('achatAttachable_id',$item->id)->get();         
            foreach($tmp as $index){
                array_push($data,$index);
            }
        }
        $ret=[];
        $ret['arr']=$arrivage;
        $ret['data']=$data;
        return $ret;
    }
    
    public function ListingInventaire(){
        $categ=Category::all();
        $categorie=[];
        $lots=[];
        foreach($categ as $c){  
            $categorie[$c->id]=$c;
            $lots[$c->name]=[];
        }
        foreach($categ as $c){
            $lots[$c->name]=Numerotatiom::where('categorie',$c->name)->get();
        }
        //$inventaire=Iventory::where('count',NULL)->get();
        $locals=Local::all();
        $salle=[];
        $inv=[];
        $site=[];
        foreach($locals as $l){
            $inv[$l->id]=[];
            $salle[$l->id]=$l;
            $site[$l->groupe]=[];
        }
        foreach($locals as $l){
            array_push($site[$l->groupe],$l);
            $inv[$l->id]=Iventory::where('count',NULL)->where('local',$l->id)->get();
        }
        return view('reporting.inventaire')->with(['categorie'=>$categorie,'lots'=>$lots,'salle'=>$salle,'inventaire'=>$inv,'site'=>$site]);
    }
    public function getArrivageInfos(Request $request){
        $arr=Arrivage::find($request->arrId);
        $ret=[];
        $ret['soldes']=$this->getSoldeArrLocal($arr,Local::find($request->local));
        $ret['arr']=$arr;
        return $ret;
    }
    public function getArrivageQUDetails(Request $request){
        $ret=[];
        $data1=[];
        $data2=[];
        $arr=Arrivage::find($request->id);
        $label=DetailsAnalyse::all();
        foreach($label as $l){
            $data1[$l->name]=[];
        }
        $analyse=$arr->resultatsAnalyse()->get();
        foreach($analyse as $a){
            array_push($data1[$a->details_analyse],$a);
        }

        $label=ValeurDescription::all();
        foreach($label as $l){
            $data2[$l->description]=[];
        }        
        $description=$arr->resultatsDescription()->get();
        foreach($description as $d){
            array_push($data2[$d->type_description],$d);
        }
        $ret['arrivage']=$arr;
        $ret['analyse']=$data1;
        $ret['descript']=$data2;
        return $ret;
    }
    private function getMVTArrId($arrId){
        $data=MX::where('arrId',$arrId);
        return $data;
    }
    public function descriptionDetailsInv1(Request $request){
        $mx=MX::find($request->mx);
        $arr=Arrivage::find($mx->arrId);
        return ['mvt'=>$mx,'arr'=>$arr,'description'=>$mx->resultatsDescription()->get(),'analyse'=>$mx->resultatsAnalyse()->get()];
    }
    public function soldeArrProdQlt(Request $request){
        $mx=MX::where('arrId',$request->arrId)->where('produitId',$request->produitId)->where('qualiteId',$request->qualiteId)->where('localId',$request->localId)->get();
        $qte=0;
        foreach($mx as $item){
            $qte+=$item->input-$item->output;
        }
        return ['data'=>$mx,'qte'=>$qte];
    }
    public function descriptionDetailsInv(){
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
        return ['qualite1'=>$qualite1,'local'=>$Local,'categorie'=>$categ,'lot'=>$lot,'produit'=>$produit,'qualite'=>$qualite,'descriptions'=>$descriptions,'valeur'=>$valeur];
    }
    public function mxByArrProdQual(Request $request){
        $tab=explode("-",$request->id);
        $mx=MX::where("arrId",$tab[0])->where("produitId",$tab[1])->where("qualiteId",$tab[2])->where("localId",$tab[3])->orderBy('id','asc')->get();
        return $mx;
    }
    public function situationInitLot(Request $request){
        $lot=Numerotatiom::where('code',$request->code)->first();
        $produitList=Produit::where('categorie',$lot->categorie)->get();
        $label=[];
        $type_prod=[];
        foreach($produitList as $prod){
            $qualiteList=Qualite::where('categorie',$prod->categorie)->where('class',$prod->class)->get();
            foreach($qualiteList as $qlt){
                array_push($label,$prod->id.'-'.$qlt->id);
                $type_prod[$prod->id.'-'.$qlt->id]=0;
            }
        }
        
        foreach($label as $lab){
            $tab=explode('-',$lab);
            $mx=MX::where('lotId',$lot->id)->where('description_mvt','RECEPTION')->where('produitId',$tab[0])->where('qualiteId',$tab[1])->get();
            //$type_prod[$lab]=0;
            foreach($mx as $item){
                $type_prod[$lab]+=$item->input-$item->output;
            }
        }
        return ['type_prod'=>$type_prod,'label'=>$label];
    }
    public function situationActuelLot(Request $request){
        $lot=Numerotatiom::where('code',$request->code)->first();
        $produitList=Produit::where('categorie',$lot->categorie)->get();
        $label=[];
        $type_prod=[];
        
        foreach($produitList as $prod){
            $qualiteList=Qualite::where('categorie',$prod->categorie)->where('class',$prod->class)->get();
            foreach($qualiteList as $qlt){
                array_push($label,$prod->id.'-'.$qlt->id);
                $type_prod[$prod->id.'-'.$qlt->id]=0;
            }
        }
        foreach($label as $lab){
            $tab=explode('-',$lab);
            $mx=MX::where('lotId',$lot->id)->where('produitId',$tab[0])->where('qualiteId',$tab[1])->get();
            $type_prod[$lab]=0;
            foreach($mx as $item){
                $type_prod[$lab]+=$item->input-$item->output;
            }
        }
        return ['type_prod'=>$type_prod,'label'=>$label];
    }
    public function situationActuelLotBySite(Request $request){
        $lot=Numerotatiom::where('code',$request->code)->first();
        $produitList=Produit::where('categorie',$lot->categorie)->get();
        $label=[];
        $type_prod=[];
        $site=LocalGRP::all();
        foreach($site as $s){
            foreach($produitList as $prod){
                $qualiteList=Qualite::where('categorie',$prod->categorie)->where('class',$prod->class)->get();
                foreach($qualiteList as $qlt){
                    array_push($label,$s->id.'-'.$prod->id.'-'.$qlt->id);
                    $type_prod[$s->id.'-'.$prod->id.'-'.$qlt->id]=0;
                }
            }
        }
            
        foreach($label as $lab){
            $tab=explode('-',$lab);
            $mx=MX::where('lotId',$lot->id)->where('produitId',$tab[1])->where('qualiteId',$tab[2])->where('siteId',$tab[0])->get();
            $type_prod[$lab]=0;
            foreach($mx as $item){
                $type_prod[$lab]+=$item->input-$item->output;
            }
        }
        return ['type_prod'=>$type_prod,'label'=>$label,'site'=>$site];
    }
    public function evoPrix(Request $request){
        $acht=Achat::all();
        $date=[];
        $valeur=[];
        $label=[];
        $index=[];
        foreach($acht as $item){
            if(!in_array($item->daty,$date)){
                array_push($date,$item->$daty);
            }
            $arr=Arrivage::find($item->achatAttachable_id);
            if(!in_array($arr->produit.' '.$arr->qualite,$label)){
                $valeur[$item->daty.' '.$arr->produit.' '.$arr->qualite]=[];
                array_push($valeur[$item->daty.' '.$arr->produit.' '.$arr->qualite],$item);
                array_push($label,$item->daty.' '.$arr->produit.' '.$arr->qualite);
            }else{
                array_push($valeur[$item->daty.' '.$arr->produit.' '.$arr->qualite],$item);
            }
        }
        return ['label'=>$label,'valeur'=>$valeur,"daty"=>$date];
    }
}