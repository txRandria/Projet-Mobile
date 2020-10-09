<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Numerotatiom;
use App\Arrivage;
use App\MX;
use App\CumulVar;
use App\Local;
use App\LocalGRP;
use App\Fournisseur;
use App\FrsGRP;
use App\Qualite;
use App\Category;
use App\Produit;
use App\Achat;
use App\DeclCharge;
use App\Charge;
use App\Perte;
use App\Etape;
use App\Transfert;
use App\Iventory;
use App\DetailsIventory;
use App\colisTrans;

class RegisterController extends Controller
{
    public function RegisterNouveauLot(Request $request){
        $Numerotatiom=new Numerotatiom;
        $Numerotatiom->code=$request->code;
        $Numerotatiom->categorie=$request->categorie;
        $Numerotatiom->description=$request->description;
        $Numerotatiom->comment=$request->comment;
        $Numerotatiom->user="no-user";
        $Numerotatiom->save();
    }
    private function getLotByCode($lot){
        return Numerotatiom::where('code',$lot)->first();
    }
    private function getLocalByName($local){
        return Local::where('name',$local)->first();
    }
    private function getGrpLocalByGroupe($grp){
        return LocalGRP::where('groupe',$grp)->first();
    }
    private function getQualiteByName($q,$calls){
        return Qualite::where('name',$q)->where('class',$calls)->first();
    }
    private function getFrsByName($n){
        return Fournisseur::where('name',$n)->first();
    }
    private function getGrpFrsByGroupe($groupe){
        return FrsGRP::where('groupe',$groupe)->first();
    }
     private function getGrpFrsByFrs($frs){
        $xfrs=Fournisseur::where('name',$frs)->first();
        return $this->getGrpFrsByGroupe($xfrs->groupe);
    }
    private function getProduitByName($n){
        return Produit::where('name',$n)->first();
    }
    private function getCategoryByName($n){
        return Category::where('name',$n)->first();
    }
    public function RegisterNouveauArrivage(Request $request){
        $Arrivage=new Arrivage;
        $Arrivage->date_arrive=$request->date_arrive;
        $Arrivage->responsable="no-user";
        $Arrivage->lot= $request->lot;
        $Arrivage->produit=$request->produit;
        $Arrivage->qualite=$request->qualite;
        $Arrivage->stock=$request->stock;
        $Arrivage->fournisseur=$request->fournisseur;
        $Arrivage->description="#In STOCK";
        $Arrivage->observation=$request->observation;
        $Arrivage->save();
        
        $lot=$this->getLotByCode($request->lot);
        $prod=$this->getProduitByName($request->produit);
        $fx=$this->getFrsByName($request->fournisseur);

        $this->addMVTArr("RECEPTION",
        $request->date_arrive,
        $this->getCategoryByName($lot->categorie)->id,
        $Arrivage,
        $lot->id,
        $this->getQualiteByName($request->qualite,$prod->class)->id,
        $prod->id,
        $request->stock,
        false,
        $this->getLocalByName($request->local)->id,
        $this->getGrpLocalByGroupe($request->site)->id,
        $this->getGrpFrsByGroupe($fx->groupe)->id
    );
    //return $this->getQualiteByName($request->qualite,$prod->class);
//return $prod;
    }
    private function getIdLot($x){
        $Numerotatiom=Numerotatiom::where('code',$x)->first();
        return $Numerotatiom;
    }

    public function SaveResultatAnalyse(Request $request){
        $mvt=MX::find($request->id_arrivage);
        $resultat=$mvt->resultatsAnalyse()->create();
        $resultat->date_analyse=$request->date_analyse;
        $resultat->type_analyse=$request->type_analyse;
        $resultat->details_analyse=$request->details_analyse;
        $resultat->valeur_analyse=$request->valeur_analyse;
        $resultat->responsable="no-user";
        $resultat->type_prod=(Produit::find($mvt->produitId))->name;
        $resultat->type_qualite=(Qualite::find($mvt->qualiteId))->name;
        $resultat->save();
    }
    public function SaveResultatDescription(Request $request){
        $mvt=MX::find($request->id_arrivage);
        $description=$mvt->resultatsDescription()->create();
        $description->type_description=$request->type_description;
        $description->valeur_description=$request->valeur_description;
        $description->responsable="no-user";
        $description->type_prod=(Produit::find($mvt->produitId))->name;
        $description->type_qualite=(Qualite::find($mvt->qualiteId))->name;
        $description->save();
    }
    private function getLastInfoStockInLocal($arr,$local){
        $mvt=$arr->mvtStocks()->where('localAttachable_id',$local)->orderBy('id','desc')->first();
        return $mvt;
    }
   public function saveOutOfStock(Request $request){
        $Arrivage=Arrivage::find($request->arrId);
        $lastInfo=$this->getLastInfoStockInLocal($Arrivage,$request->localId);
        $mvt=$Arrivage->mvtStocks()->create();
        $mvt->lotAttachable_id=$lastInfo->lotAttachable_id;
        $mvt->localAttachable_id=$request->localId;
        $mvt->siteAttachable_id=$lastInfo->siteAttachable_id;
        $mvt->frsAttachable_id=$lastInfo->frsAttachable_id;
        $mvt->grpFrsAttachable_id=$lastInfo->grpFrsAttachable_id;
        $mvt->produitAttachable_id=$lastInfo->produitAttachable_id;
        $mvt->categorieAttachable_id=$lastInfo->categorieAttachable_id;
        $mvt->description_mvt=$request->description;
        $mvt->input=0;
        $mvt->output=$request->quantite;
        $mvt->solde=0;
        $mvt->responsable='no user';
        //$mvt->$date_op='';
        $mvt->comment=$request->comment;
        $mvt->save();

        return  $mvt;
    }
    public function saveInsertOperation(Request $request){
      
        switch($request->typeOp){
            case 'prix':
               $Achat=$this->saveAchat($request,Arrivage::find($request->arrId));
                return $Achat;
            break;
            case 'perte':
                $pert=$this->savePerte($request);
                return $pert;
            break;
            case 'charge':
                $charge=$this->saveCharge($request);
                return $charge;
            break;
            default:
            break;
        }
    }
    public function saveAchat(Request $request){
        $arr=Arrivage::find($request->arrId);
        $table=new Achat;
        $table->achatAttachable_id=$request->arrId;
        $table->achatAttachable_type="comm";
        $table->daty=$request->daty;
        $table->commercial=$request->comm;
        $table->fournisseur=$arr->fournisseur;
        $table->groupeFrs=$this->getGrpFrsByFrs($arr->fournisseur)->groupe;
        $table->prix=$request->pu;
        $table->taxe=$request->taxe;
        $table->commentAchat="";
        $table->commentLivraison=$request->obs;
        $table->save();
        return $table;
    }
    public function saveCharge(Request $request){
        $c=$this->getChargeByname($request->type);
        $table=new DeclCharge;
        $table->chargeAttachable_id=$request->arrId;
        $table->chargeAttachable_type=$c->type;
        $table->charge=$c->id;
        $table->valeur=$request->valeur;
        $table->comment=$request->obs;
        $table->save();
        return $table;
    }
    private function getChargeByname($charge){
        return Charge::where('type',$charge)->first();
    }
   private function getPerteByName($perte){
    return Perte::where('type',$perte)->first();
   }
   private function setCummul($daty,$lotId,$arrId,$detailProd,$in,$out,$siteId,$motifsId,$commentText){
    $table=new CumulVar;
    $table->date_op=$daty;
    $table->lotId=$lotId;
    $table->ArrId=$arrId;
    $table->descript=$detailProd;
    $table->in=$in;
    $table->out=$out;
    $table->siteId=$siteId;
    $table->motif=$motifsId;
    $table->comment=$commentText;
    $table->save();
   }
    public function savePerte(Request $request){
        $index=explode("-",$request->arrId);
        $p=$this->getPerteByName($request->motif);
        $last=MX::find($request->last);
        $table=new MX;
        $table->date_op=$request->daty;
        $table->arrId=$index[0];
        $table->lotId=$last->lotId;
        $table->localId=$last->localId;
        $table->siteId=$last->siteId;
        $table->qualiteId=$last->qualiteId;
        $table->assocId=$last->assocId;
        $table->produitId=$last->produitId;
        $table->categorieId=$last->categorieId;
        $table->description_mvt=$request->motif;
        $table->input=0;
        $table->output=$request->qte;
        $table->responsable="no user";
        $table->save();
        $this->setCummul(
            $request->daty,
            $last->lotId,
            $index[0],
            $request->arrId,
            0,
            $request->qte,
            $last->siteId,
            $p->id,
            "");
        return  $request;
    }
    public function saveNewTypeCharge(Request $request){
        $Charge=new Charge;
        $Charge->type=$request->type;
        $Charge->cause="";
        $Charge->comment=$request->comment;
        $Charge->save();
        return $Charge;
    }
    public function saveNewTypePerte(Request $request){
        $perte=new Perte;
        $perte->type=$request->type;
        $perte->cause="";
        $perte->comment=$request->comment;
        $perte->save();
        return $perte;
    }
    public function saveNewTypeProcess(Request $request){
        $table=new Etape;
        $table->categorie=$request->categ;
        $table->ref=$request->code;
        $table->name=$request->name;
        $table->comment=$request->comment;
        $table->save();
        return $table;
    }
    public function saveTransfertStock(Request $request){
        $arrivage=Arrivage::find($request->arrId);
        $localOrigine=Local::find($request->idlocal);
        $lastInfo=[];
        {
            $lastInfo=$arrivage->mvtStocks()->where('id',$request->ref)->first();    
        }
        $qte=$request->qte;
        $new_mvt=$arrivage->mvtStocks()->create();
        $this->sortirStock($new_mvt,$lastInfo,$qte);
        
        if($request->select=='site'){
            $siteDest=LocalGRP::where('groupe',$request->destSite)->first();
            $this->transfertSiteStock($arrivage,$lastInfo,$qte,$siteDest);
        }else{
            $localdest=Local::where('name',$request->destLocal)->first();
            $this->transfertLocalStock($arrivage,$lastInfo,$qte,$localdest);
        }
        return $request;
    }
    private function sortirStock($new_mvt,$last,$qte){
        $new_mvt->lotAttachable_id=$last->lotAttachable_id;
        $new_mvt->localAttachable_id=$last->localAttachable_id;
        $new_mvt->siteAttachable_id=$last->siteAttachable_id;
        $new_mvt->frsAttachable_id=$last->frsAttachable_id;
        $new_mvt->grpFrsAttachable_id=$last->grpFrsAttachable_id;
        $new_mvt->produitAttachable_id=$last->produitAttachable_id;
        $new_mvt->categorieAttachable_id=$last->categorieAttachable_id;
        $new_mvt->description_mvt="TRANSFERT";
        $new_mvt->input=0;
        $new_mvt->output=$qte;
        $new_mvt->solde=0;
        $new_mvt->responsable="no-user";
        //$new_mvt->date_op="";
        $new_mvt->comment="transfert local";
        $new_mvt->save();
    }
    private function transfertSiteStock($arrivage,$last,$qte,$newSite){
        $transfert=new Transfert;
        $transfert->arrivage=$arrivage->id;
        $transfert->origineLocal=$last->localAttachable_id;
        $transfert->origineSite=$last->siteAttachable_id;
        $transfert->qte=$qte;
        $transfert->siteDest=$newSite->id;
        $transfert->comment="no-user";
        $transfert->save();
       return $transfert;
    }
    private function transfertLocalStock($arrivage,$last,$qte,$newLocal){
        $new_mvt=$arrivage->mvtStocks()->create();
        $new_mvt->lotAttachable_id=$last->lotAttachable_id;
        $new_mvt->localAttachable_id=$newLocal->id;
        $new_mvt->siteAttachable_id=$last->siteAttachable_id;
        $new_mvt->frsAttachable_id=$last->frsAttachable_id;
        $new_mvt->grpFrsAttachable_id=$last->grpFrsAttachable_id;
        $new_mvt->produitAttachable_id=$last->produitAttachable_id;
        $new_mvt->categorieAttachable_id=$last->categorieAttachable_id;
        $new_mvt->description_mvt="TRANSFERT";
        $new_mvt->input=$qte;
        $new_mvt->output=0;
        $new_mvt->solde=0;
        $new_mvt->responsable="no-user";
        //$new_mvt->date_op="";
        $new_mvt->comment="transfert local";
        $new_mvt->save();
    }
    public function saveInventoryAction(Request $request){
        $iventorie=new Iventory;
        $iventorie->daty=$request->daty;
        $iventorie->resp="no-user";
        $iventorie->local=$request->local;
        $iventorie->arrivage=$request->arr;
        $iventorie->qte=$request->qte;
        $iventorie->comment=$request->comment;
        $iventorie->save();
        return $iventorie;
    }
    public function saveInventoryDetails(Request $request){
        $arr=MX::find($request->arrId);
        $lot=Numerotatiom::find($arr->lotId);
        $categ=Category::find($arr->categorieId);
        $produit=Produit::find($arr->produitId);
        $qualite=Qualite::find($arr->qualiteId);
        $details=new DetailsIventory;
        $details->inventaire=$request->invId;
        $details->arrivage=$request->arrId;
        $details->condition=0;
        $details->qte=$request->qte;
        $details->ref=$categ->id."-".$lot->id."-".$produit->id.$qualite->id."-".$arr->id."-".$request->invId;
        $details->comment=$request->comment;
        $details->save();
        return $details;
    }
    private function createTransByElment($transId,$elmt){
        foreach($elmt as $e){
            $colis=new colisTrans;
            $colis->reference=$e->ref;
            $colis->transId=$transId;
            $colis->invDetails=$e->id;
            $colis->poids=$e->qte;
            $colis->arrId=$e->arrivage;
            $colis->comment=$e->inventaire;
            $colis->save();
        }
        
    }
    private function supprInventaireGroupe($grpId){
        $grp=Iventory::find($grpId);
        $grp->count=2;
        $grp->save();
        return $grp;
    }
    private function createNewInventaire($old,$elmt){
        if(count($elmt)>0){
            $poids=0;
            foreach($elmt as $e){
                $poids+=$e->qte;
            }
            $grp=new Iventory;
            $grp->daty=$old->daty;
            $grp->resp=$old->resp;
            $grp->local=$old->local;
            $grp->arrivage=$old->arrivage;
            $grp->qte=$poids;
            $grp->comment='Copy-transfert';
            $grp->save();
        foreach($elmt as $e){
            $carton=new DetailsIventory;
            $carton->inventaire=$grp->id;
            $carton->arrivage=$e->arrivage;
            $carton->condition=$e->condition;
            $carton->qte=$e->qte;
            $carton->ref=$e->ref;
            $carton->comment=$e->comment;
            $carton->save();
        }

        }
    }
    
    public function convertInvToTrans(Request $request){
        $arrivage=Arrivage::find($request->arrId);
        
        $tabColis=explode("|",$request->transId);
        $tabRestant=explode("|",$request->elseScript);
        $poids=$request->qteTotal;
        
        $inv=$this->supprInventaireGroupe($request->inv);
        $ret=[];
        if(count($tabRestant)>0){
            $elmt=[];
            $qteTotal=0;
            foreach($tabRestant as $e){
                if($e!='' && $e!=NULL){
                    $ret=DetailsIventory::find($e);
                    array_push($elmt,$ret);
                }
            }
        
            $ret=$this->createNewInventaire($inv,$elmt);
        }
        if(count($tabColis)>0){
            $lastInfo=[];
            {
                $lastInfo=$arrivage->mvtStocks()->where('localAttachable_id',$inv->local)->orderBy('id','desc')->first();    
            }

            $new_mvt=$arrivage->mvtStocks()->create();
            $this->sortirStock($new_mvt,$lastInfo,$poids);
            $trans=NULL;
            {
                $siteDest=LocalGRP::where('groupe',$request->destSite)->first();
                $trans=$this->transfertSiteStock($arrivage,$lastInfo,$poids,$siteDest);
            }
            if($trans!=NULL){
                $elmt=[];
                foreach($tabColis as $e){
                    if($e!='' && $e!=NULL){
                        $ret=DetailsIventory::find($e);
                        array_push($elmt,$ret);
                    }
                }
                $ret=$elmt;
                $this->createTransByElment($trans->id,$elmt);
            }
        }
        return $ret;
    }
    public function registerProcessData(Request $request){
        $oldIndex=$request->OldIndex;
        $old=MX::find($request->id);
        $tabOld=explode("-",$oldIndex);
        $newIndex=$tabOld[0].'-'.$request->produit.'-'.$request->qualite.'-'.$request->localId;
       if($newIndex!=$oldIndex){
            $this->addMVTArr(
            'To-'.$request->process,
            $request->daty,
            $old->categorieId,
            Arrivage::find($old->arrId),
            $old->lotId,
            $old->qualiteId,
            $old->produitId,
            $request->quantite,
            true,
            $old->localId,
            $old->siteId,
            $old->assocId);
            $this->addMVTArr($request->process,$request->daty,$old->categorieId,Arrivage::find($old->arrId),$old->lotId,$request->qualite,$request->produit,$request->quantite,false,$request->localId,$old->siteId,$old->assocId);
        }
        return $request;
    }
    private function addMVTArr($type,$datee,$categ,$arr,$lotId,$qualiteId,$produitId,$qte,$isOut,$localId,$siteId,$assoc){
            $table=new MX;    
            $table->date_op=$datee;
            $table->arrId=$arr->id;
            $table->lotId=$lotId;
            $table->localId=$localId;
            $table->siteId=$siteId;
            $table->qualiteId=$qualiteId;
            $table->assocId=$assoc;
            $table->produitId=$produitId;
            $table->categorieId=$categ;
            $table->description_mvt=$type;
            if($isOut){
                $table->input=0;
                $table->output=$qte;
            }else{
                $table->input=$qte;
                $table->output=0;
            }
            $table->responsable='no user';
            $table->save();
    }
}
