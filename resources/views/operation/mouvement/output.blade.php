@extends('layout.app3')
@section('sub-menu')
    <div class="row">
        <div class="col-md-12">
        <select class="form-control btn btn-primary mb-3" id="category" onchange="selectAnCateg()"></select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-8">
            <ul class="list-group mb-3" id="lot-list">
                
            </ul>
        </div>
   </div>
@endsection
@section('contents')
<div class="container mb-3" id="contenus"></div>

<div  id="modalDialog">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportTitle">OUT STOCK</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container mb-3" id="parent"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">

var categorie=@json($categorie);
var lots=@json($lot);
var etape=@json($etape);
var produit=@json($produit);
var qualite=@json($qualite);
var qualite1=@json($qualite1);
var local=@json($local);
var selectedStock='';
var cumul=[];
var indexCumul=[];
var indexLastCumul=[];

function loading(){
    var inner='<option>SELECTIONNER ICI POUR COMMENCER</option>';
    document.getElementById('category').innerHTML=inner;
    for(var i in categorie){
        inner='<option>'+categorie[i].name+'</option>';
        document.getElementById('category').innerHTML+=inner;
    }
}
function selectAnCateg(){
        var categ=$("#category").val();
        var inner='';
        document.getElementById('lot-list').innerHTML=inner;
        var lotsList=lots[categ];
        for(var i in lotsList){
            inner+='<li class="list-group-item d-flex justify-content-between align-items-center btn" onclick="clickLot(\''+lotsList[i].id+'\',\''+lotsList[i].code+'\',\''+categ+'\')">'+lotsList[i].code+'</li>';
        }
        document.getElementById('lot-list').innerHTML=inner;
}
$(document).ready(function(){
        loading();
});
function clickLot(lotId,lotCode,categ){
    document.getElementById('contenus').innerHTML='';
    cumul=[];
    indexCumul=[];
    indexLastCumul=[];
    var formData=new FormData();
    formData.append('id',lotCode);
    axios.post("{{route('report.mvtLocalQualiteProduitByLot')}}",formData)
    .then(function(res){
         var data=res.data.data;
         //alert(JSON.stringify(data));
         var qte=res.data.qte;
         var analyse=res.data.analyse;
         for(var z in local){
                var data_z=data[local[z].name];
                for(var x in produit){
                    var nbligne=0;
                    var inner='<center><h4><b>Local :: '+local[z].groupe+'-'+local[z].name+'  '+produit[x].categorie+'-'+produit[x].name+'</b></h4></center><table class="table table-sm table-bordered table-striped table-hover table-secondary bg-primary mb-3"><tr><th>Decsriptions des produits</th><th>Disponibles</th><th>Historiques des actions</th><th></th><th>Input</th><th>Output</th><th>Soldes rest.</th><th>ACTIONS</th></tr>';
                    var sq=qualite[produit[x].categorie][produit[x].class];
                    var data_x=data_z[produit[x].name];
                    for(var y in sq){
                        var indexQ=produit[x].id+'-'+sq[y].id+'-'+local[z].id;
                        if(qte[indexQ]>0){
                            nbligne++;
                            var mvt=data_x[sq[y].name];
                            inner+='<tr><th rowspan="'+mvt.length+'">'+produit[x].categorie+' '+produit[x].name+' '+sq[y].name+'</th><td rowspan="'+mvt.length+'">'+qte[indexQ]+'</td>';
                            var ii=0;
                            for(var k in mvt){
                                indexLastCumul[mvt[k].arrId+'-'+indexQ]=mvt[k].id;
                                if(indexCumul.indexOf(mvt[k].arrId+'-'+indexQ)==-1){
                                    indexCumul.push(mvt[k].arrId+'-'+indexQ);
                                    cumul[mvt[k].arrId+'-'+indexQ]=mvt[k].input-mvt[k].output;
                                }else{
                                    cumul[mvt[k].arrId+'-'+indexQ]+=mvt[k].input-mvt[k].output;
                                }
                                if(ii==0){
                                    inner+='<td><li>Origine : <a class="badge">Date : <b id="dt-'+mvt[k].id+'"></b></a><a class="badge badge-success">Produit : <b id="px-'+mvt[k].id+'"></b></a><a class="badge">Frs : <b id="frs-'+mvt[k].id+'"></b></a></li><li>Action : '+mvt[k].description_mvt+'</li></td><td>'+produit[mvt[k].produitId].name+'</td><td>'+mvt[k].input+'</td><td>'+mvt[k].output+'</td><td>'+cumul[mvt[k].arrId+'-'+indexQ]+'</td><td id="act-'+mvt[k].id+'"></td></tr>';
                                }else{
                                    inner+='<tr><td><li>Origine : <a class="badge">Date : <b id="dt-'+mvt[k].id+'"></b></a><a class="badge badge-success">Produit : <b id="px-'+mvt[k].id+'"></b></a><a class="badge">Frs : <b id="frs-'+mvt[k].id+'"></b></a></li><li>Action : '+mvt[k].description_mvt+'</li></td><td>'+produit[mvt[k].produitId].name+'</td><td>'+mvt[k].input+'</td><td>'+mvt[k].output+'</td><td>'+cumul[mvt[k].arrId+'-'+indexQ]+'</td><td id="act-'+mvt[k].id+'"></td></tr>';
                                }
                                ii++;
                            }
                            
                        }
                    }
                inner+='</table>';
                if(nbligne>0){
                    document.getElementById('contenus').innerHTML+=inner;    
                }
                }
        }    
        placeAction();
    })
    .catch(function(err){
        alert(err.message);
    });

}
function origine(idArr,id){
    var formData=new FormData();
    formData.append('id',idArr);
    axios.post("{{route('report.Arrivage')}}",formData)
    .then(function(res){
         var data=res.data;
         document.getElementById("dt-"+id).innerHTML=data.date_arrive;
         document.getElementById("px-"+id).innerHTML=data.produit+'-'+data.qualite;
         document.getElementById("frs-"+id).innerHTML=data.fournisseur;
    })
    .catch(function(err){
        alert(err.message);
    });
}
function placeAction(){
    for(var i in indexLastCumul){
        var inner='<select id="act-select" class="form-control" onblur="onSelectAction(this,\''+i+'\')">';
            inner+=optionForAction();
            inner+='</select><center><a href="#" class="badge badge-danger btn" onclick="processAdd(\''+i+'\')"> + ajouter </a></center>';
        document.getElementById('act-'+indexLastCumul[i]).innerHTML=inner;
        var tab=i.split("-");
        origine(tab[0],indexLastCumul[i]);
    }
}
function processAdd(id){
    document.getElementById('parent').innerHTML='<div id="div-1"></div><div id="div-2"></div><div id="div-3"></div>';
    var formData=new FormData();
    formData.append('id',id);
    axios.post("{{route('report.mxByArrProdQual')}}",formData)
    .then(function(res){
        var data=res.data;
        var inner='<table class="table table-sm table-bordered table-striped table-hover bg-success"><tr><th></th><th></th><th></th><th></th><th>Entrée</th><th>Sortie</th><th>En stock</th></tr>';
        var solde=0;
        var lastIndex=0;
        for(var i in data){
            solde+=data[i].input-data[i].output;
            lastIndex=data[i].id;
            inner+='<tr><th>'+data[i].date_op+'</th><th>'+produit[data[i].produitId].categorie+' - '+produit[data[i].produitId].name+' '+qualite1[data[i].qualiteId].name+'</th><td>'+local[data[i].localId].groupe+' '+local[data[i].localId].name+'</td><td>'+data[i].description_mvt+'</td><td>'+data[i].input+'</td><td>'+data[i].output+'</td><td>'+solde+'</td></tr>';
        }
         inner+='</table>';
        document.getElementById('div-1').innerHTML+=inner;
        var action=$("#act-select").val();
        inner='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">MOTIF / CAUSE : </span></div><input type="text" class="form-control" id="act_id" value="'+action+'"/></div>';
        inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">QUANTITE : </span></div><input type="number"  id="qte_id" value="0"/></div>';
        inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">DATE DU MESURE : </span></div><input type="date" class="datepicker"  id="date_id" /></div>';
        inner+='<center class="mb-3"><button class="btn btn-danger" onclick="savePerte(\''+id+'\',\''+lastIndex+'\')">Enregistrer</button></center>';
        $("#myModal").modal('show');
        document.getElementById('div-3').innerHTML+=inner;
    })
    .catch(function(err){
        alert(err.message);
    });
}
function savePerte(id,last){
    var formData=new FormData(); 
    formData.append('arrId',id);
    formData.append('last',last);
    formData.append('qte',$("#qte_id").val());
    formData.append('daty',$("#date_id").val());
    formData.append('motif',$("#act_id").val());
     axios.post("{{route('register.savePerte')}}",formData)
    .then(function(res){
        alert(JSON.stringify(res.data));
    })
    .catch(function(err){
        alert(err.message);
    });
}
function optionForAction(){
    var inner='';
    for(var i in etape){
        inner+='<option>'+etape[i].type+'</option>';
    }
    return inner;
}
</script>
@endsection