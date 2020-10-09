@extends('layout.app3')
@section('sub-menu')
    <div class="row">
        <div class="col-md-3">
            <center><img width="25" src="{{ asset('icon/icons8-ungroup-objects-96.png') }}"/></center>
        </div>
        <div class="col-md-8">
            <select class="form-control" id="category" onchange="selectCateg()">
                
            </select>
        </div>
   </div>
   <hr>
    <ul class="nav flex-column" id="lot-list">
    
    </ul>
@endsection
    @section('contents')
    <div class="row" id="contenus">
    
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    var categorie=@json($categorie);
    var lots=@json($lot);
    var produit=@json($produit);
    var qualite=@json($qualite);
    var qualite1=@json($qualite1);
    var groupeAnalyse=@json($groupe);
    var detailsAnalyse=@json($analyse);
    var localx=@json($local);
    var cumul=[];
    var indexCumul=[];
    var indexLastCumul=[];

function selectCateg(){
        var categ=$("#category").val();
        var inner='';
        document.getElementById('lot-list').innerHTML=inner;
        var lotsList=lots[categ];
        for(var i in lotsList){
            inner+='<li class="list-group-item badge d-flex justify-content-between align-items-center bg-success form-control" href="#" onclick="clickLot(\''+lotsList[i].id+'\',\''+lotsList[i].code+'\',\''+categ+'\')">'+lotsList[i].code+'</li>';
        }
        document.getElementById('lot-list').innerHTML=inner;
}

$(document).ready(function(){
    loading();
});
function loading(){
    if(groupeAnalyse){
        //alert(JSON.stringify(groupeAnalyse))
        if(groupeAnalyse.length){
            var inner='<option>SELECTIONNER ICI POUR COMMENCER</option>';
            document.getElementById('category').innerHTML=inner;
            for(var i in categorie){
                inner='<option>'+categorie[i].name+'</option>';
                document.getElementById('category').innerHTML+=inner;
            }
        }else{
            alert("Il faut indiquer les elements à analyser dans les bases de données");    
        }
    }else{
        alert("Pas de lab dans les bases de données");
        parametreAnalyse();
    }
    
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
         var qte=res.data.qte;
         var analyse=res.data.analyse;
         for(var z in localx){
                var data_z=data[localx[z].name];
                for(var x in produit){
                    var nbligne=0;
                    var inner='<center><h4><b>Local :: '+localx[z].groupe+'-'+localx[z].name+'  '+produit[x].categorie+'-'+produit[x].name+'</b></h4></center><table class="table table-sm table-bordered table-striped table-hover table-info bg-info mb-3"><tr><th>Decsriptions des produits</th><th>Disponibles</th><th>Historiques des actions</th><th></th><th>Input</th><th>Output</th><th>ACTIONS</th></tr>';
                    var sq=qualite1[produit[x].categorie][produit[x].class];
                    var data_x=data_z[produit[x].name];
                    for(var y in sq){
                        var indexQ=produit[x].id+'-'+sq[y].id+'-'+localx[z].id;
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
        var inner='<a class="badge badge-danger btn" onclick="insertDataAnalyse(\''+indexLastCumul[i]+'\')"> + ajouter un résultat analyse </a>';
        document.getElementById('act-'+indexLastCumul[i]).innerHTML=inner;
    }
}
function getListAnalyse(mvtId){
    var formData=new FormData();
    formData.append('id',mvtId);
    axios.post("{{route('report.getListDetailsAnalyseByAnalyse')}}",formData)
    .then(function(res){
        //alert(JSON.stringify(res.data));
    })
    .catch(function(err){
        alert(err.message);
    });
}
function insertDataAnalyse(id){
    document.getElementById('contenus').innerHTML='<div class="col-md-7 justify-content-center" id="contenus2"></div><div class="container col-md-5 table-primary" id="side" ></div>';
    var formData=new FormData();
    formData.append('id',id);
    axios.post("{{route('app.newAnalyse')}}",formData)
    .then(function(res){
        var mvt=res.data.mvt;
        var analyse=res.data.analyse;
        
        var inner='<center><table class="table table-sm table-warning table-responsive table-hover table-striped">';
        inner+='<tr hidden><th>Index : </th><td id="id-val">'+id+'</td></tr>';
        inner+='<tr><th>CATEGORIE : </th><td>'+categorie[mvt.categorieId].name+'</td></tr>';
        inner+='<tr><th>PRODUIT : </th><td>'+produit[mvt.produitId].name+'</td></tr>';
        inner+='<tr><th>QUALITE : </th><td>'+qualite[mvt.qualiteId].name+'</td></tr>';
        inner+='<tr><th>EN STOCK : </th><td>'+(mvt.input-mvt.output)+'</td></tr>';
        inner+='<tr><th>LOCAL : </th><td>'+localx[mvt.localId].name+' de '+localx[mvt.siteId].groupe+'</td></tr>';
        inner+='</table></center>';
        if(analyse){
            inner+='<hr><table class="table table-sm table-success table-bordered table-striped table-bordered"><tr><th>Date</th><th>Groupe/Labo</th><th>Type analyse</th><th>Résultats</th><th>Produit</th></tr>'
            for(var i in analyse){
                inner+='<tr><td>'+analyse[i].date_analyse+'</td><td>'+analyse[i].type_analyse+'</td><td>'+analyse[i].details_analyse+'</td><td>'+analyse[i].valeur_analyse+'</td><td>'+analyse[i].type_prod+'-'+analyse[i].type_qualite+'</td></tr>';
            }
            inner+='</table>';
        }else{
            inner+="<center><hr></center>";
        }
        
        inner+='<hr><div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">DATE DE L\'ANALYSE : </span></div><input type="date" id="date_op" class="md-form md-outline input-with-post-icon datepicker" aria-label="Small" aria-describedby="inputGroup-sizing-sm"></div>';
        inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">GROUPE OU LABO : </span></div><select onchange="selectGP()" class="form-control btn btn-success" id="gp-analyse">';
        for(var i in groupeAnalyse){
            inner+='<option id="gp-'+groupeAnalyse[i].id+'">'+groupeAnalyse[i].name+'</option>';
        }
        inner+='</select></div>';
        inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">Type analyse : </span></div><select class="form-control btn btn-success" id="sl-analyse"></select></div>';
        inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text">Valeur / résultat : </span></div><input id="valeur" type="number" class="form-control"></div>';
        inner+='<center class="mb-3"><button class="btn btn-danger" onclick="saveAnalyse()">ENREGISTRER</button></center>';
        document.getElementById("contenus2").innerHTML=inner;
        selectGP();
    })
    .catch(function(err){
        alert(err.message);
    });
}
function saveAnalyse(){
    var formData=new FormData();
    formData.append('date_analyse',$('#date_op').val());
    formData.append('type_analyse',$('#gp-analyse').val());
    formData.append('details_analyse',$('#sl-analyse').val());
    formData.append('valeur_analyse',$('#valeur').val());
    //formData.append('type_prod',$('#echantillon-area').val());
    formData.append('id_arrivage',$('#id-val').html());
    //formData.append('type_qualite',$('#arrive_lot').html());

    axios.post("{{route('register.saveResultatAnalyse')}}",formData)
    .then(function(res){
        var id=$('#id-val').html();
        insertDataAnalyse(id);
    })
    .catch(function(err){
        alert(err.message);
    });
}
function selectGP(){
    document.getElementById("sl-analyse").innerHTML='';
    var src=document.getElementById("gp-analyse");
    var option=src.options;
    if(option.length>0){
        var index=option[option.selectedIndex].id;
        var tab=index.split('-');
        tab=detailsAnalyse[tab[1]];
        var inner='';
        for(var i in tab){
              inner+='<option id="an-'+tab[i].id+'">'+tab[i].name+'</option>';
        }
        document.getElementById("sl-analyse").innerHTML=inner;
    }
}
</script>
@endsection