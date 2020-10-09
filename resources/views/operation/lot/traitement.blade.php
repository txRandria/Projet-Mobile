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
   <hr>
    <div  id="modalDialog">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container mb-3" id="stock-place"></div>
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
@section('contents')
<div class="container mb-3" id="contenus"></div>
@endsection
@section('scripts')
<script type="text/javascript">

var categorie=@json($categorie);
var lots=@json($lot);
var etape=@json($etape);
var produit=@json($produit);
var qualite=@json($qualite);
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
function optionForAction(categ){
    var inner='';
    var tmp=etape[categ];
    for(var i in tmp){
        inner+='<option>'+tmp[i].name+'</option>';
    }
    return inner;
}
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
                    var inner='<center><h4><b>Local :: '+local[z].groupe+'-'+local[z].name+'  '+produit[x].categorie+'-'+produit[x].name+'</b></h4></center><table class="table table-sm table-bordered table-striped table-hover table-secondary bg-primary mb-3"><tr><th>Decsriptions des produits</th><th>Disponibles</th><th>Historiques des actions</th><th></th><th>Input</th><th>Output</th><th>Soldes rest.</th><th>ACTIONS</th><th>Coût de X°</th></tr>';
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
                                    inner+='<td><li>Origine : Origine</li><li>Action : '+mvt[k].description_mvt+'</li></td><td>'+produit[mvt[k].produitId].name+'</td><td>'+mvt[k].input+'</td><td>'+mvt[k].output+'</td><td>'+cumul[mvt[k].arrId+'-'+indexQ]+'</td><td id="act-'+mvt[k].id+'"></td></tr>';
                                }else{
                                    inner+='<tr><td><li>Origine : Origine</li><li>Action : '+mvt[k].description_mvt+'</li></td><td>'+produit[mvt[k].produitId].name+'</td><td>'+mvt[k].input+'</td><td>'+mvt[k].output+'</td><td>'+cumul[mvt[k].arrId+'-'+indexQ]+'</td><td id="act-'+mvt[k].id+'"></td></tr>';
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
        placeAction(categ);
    })
    .catch(function(err){
        alert(err.message);
    });

}
function placeAction(categ){
    for(var i in indexLastCumul){
        var inner='<select class="form-control" onblur="onSelectAction(this,\''+i+'\',\''+categ+'\')">';
            inner+=optionForAction(categ);
            inner+='</select>';
        document.getElementById('act-'+indexLastCumul[i]).innerHTML=inner;
    }
}
function onSelectAction(src,index,categ){
    var tab=index.split('-');
    var inner='<div class="card bg-primary text-white"><div class="card-header">Situation actuelle</div><div class="card-body">';
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">Produit : </span></div><input type="text" class="form-control" value="'+produit[tab[1]].name+'"/></div>';
    var qt='';
    var tmp=qualite[produit[tab[1]].categorie][produit[tab[1]].class]
    for(var i in tmp){
        if(tmp[i].id==tab[2]){
            qt=tmp[i];
        }    
    }
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">Qualite : </span></div><input type="text" class="form-control" value="'+qt.name+'"/></div>';
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">En stock : </span></div><input type="number" min="0" max="'+cumul[index]+'" class="form-control" value="'+cumul[index]+'"/></div>';
    inner+='</div></div>';
    document.getElementById('stock-place').innerHTML=inner;
    var option=src.options;
    var traitement=option[option.selectedIndex].text;
    inner='<div class="card bg-success text-white"><div class="card-header">Nouveau statut</div><div class="card-body">';
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">DATE : </span></div><input type="date" id="datyy" class="md-form md-outline input-with-post-icon datepicker" aria-label="Small" aria-describedby="inputGroup-sizing-sm"></div>';
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">ETAPE DE PRODUCTION : </span></div><input type="text" class="form-control" id="traitement" value="'+traitement+'"/></div>';
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">QUANTITE CONCERNEE: </span></div><input type="number" min="0" max="'+cumul[index]+'" class="form-control" id="qte" value="'+cumul[index]+'"/></div>';
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">'+categ+' : </span></div><select id="newProduit" class="form-control" onchange="selectNewProduit()">';
    for(var i in produit){
        if(produit[i].categorie==categ){
            inner+='<option id="'+produit[i].id+'">'+produit[i].name+'</option>';
        }
    }
    inner+='</select></div>';
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">QUALITE: </span></div><select id="newQualite" class="form-control">';
    
    inner+='</select></div>';
    inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">LOCAL: </span></div><select id="newLocal" class="form-control">';
    var prevLocal=local[tab[3]];
    for(var i in local){
        if(local[i].groupe==prevLocal.groupe){
            if(local[i].id==tab[3]){
                inner+='<option id="'+local[i].id+'" selected>'+local[i].name+'</option>';
            }else{
                inner+='<option id="'+local[i].id+'">'+local[i].name+'</option>';
            }
            
        }
    }
    inner+='</select></div></div></div>';
    inner+='<hr><center class="mb-3"><button class="btn btn-danger" onclick="saveTrait(\''+index+'\')">Enregistrer</button></center>';
    document.getElementById('parent').innerHTML=inner;
    $("#myModal").modal('show');
    selectNewProduit();
}
function selectNewProduit(){
     var src=document.getElementById("newProduit");
     var option=src.options;
     var idProd=option[option.selectedIndex].id;
     var qt=qualite[produit[idProd].categorie][produit[idProd].class];
     var inner='';
     for(var i in qt){
         inner+='<option id="'+qt[i].id+'">'+qt[i].name+'</option>';
     }
     document.getElementById('newQualite').innerHTML=inner;
}
function getIdSelected(id){
    var src=document.getElementById(id);
    var option=src.options;
    return option[option.selectedIndex].id;
}
function saveTrait(index){
    var qte=parseFloat($("#qte").val());
    var max=parseFloat(cumul[index]);
    if(qte<=max && qte>0){
        var formData=new FormData();
        formData.append('id',indexLastCumul[index]);
        formData.append('produit',getIdSelected('newProduit'));
        formData.append('qualite',getIdSelected('newQualite'));
        formData.append('daty',$("#datyy").val());
        formData.append('quantite',qte);
        formData.append('process',$("#traitement").val());
        formData.append('localId',getIdSelected('newLocal'));
        formData.append('OldIndex',index);
        axios.post("{{route('register.registerProcessData')}}",formData)
        .then(function(res){
            alert(JSON.stringify(res.data));
        })
        .catch(function(err){
            alert(err.message);
        })
    }
}
</script>
@endsection