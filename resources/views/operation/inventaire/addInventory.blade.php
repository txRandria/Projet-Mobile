@extends('layout.app3')
@section('sub-menu')
    <ul class="nav flex-column" id="lot-list">
    
    </ul>
@endsection
@section('contents')
<div class="container mb-3" id="co1"></div>
<div class="container mb-3" id="co"></div>
@endsection
@section('scripts')
<script type="text/javascript">
    var categorie=[];
    var lots=[];
    var produit=[];
    var qualite1=[];
    var qualite=[];
    var local=[];

function loading(id){
    var formData=new FormData();
    formData.append('mx',id);
    axios.post("{{route('report.headOfInventory')}}",formData)
    .then(function(res){
    var data=res.data;
    document.getElementById('co').innerHTML='<table class="table table-sm table-bordered table-hover" id="table-1"><tbodyid="tbody-1"></tbody></table>';
    for(var i in data){
        document.getElementById('tbody-1').innerHTML+='<tr><th>'+data[i].daty+'</th></tr>';
    }
    }).catch(function(err){
    alert(err.message);
    });
}
function loadingData(id){
    var formData=new FormData();
    formData.append('mx',id);
    axios.post("{{route('report.descriptionDetailsInv')}}",formData)
    .then(function(res){
        categorie=res.data.categorie;
        lots=res.data.lot;
        produit=res.data.produit;
        qualite1=res.data.qualite1;
        qualite=res.data.qualite;
        local=res.data.local;
        loadingContents(id);
    }).catch(function(err){
    alert(err.message);
    });
}
function loadingContents(id){
    var formData=new FormData();
    formData.append('mx',id);
    axios.post("{{route('report.descriptionDetailsInv1')}}",formData)
    .then(function(res){
        //alert(JSON.stringify(qualite));
        var inner='<table class="table table-sm bg-info table-bordered table-striped"><tbody id="contents-1"></tbody></table><div id="contents-2"></div>'
        document.getElementById('co1').innerHTML=inner;
        var mvt=res.data.mvt;
        
        inner='<tr><th>Categorie</th><td>'+categorie[mvt.categorieId].name+'</td></tr>';
        inner+='<tr><th>Produit</th><td>'+produit[mvt.produitId].name+'</td></tr>';
        inner+='<tr><th>Qualite</th><td>'+qualite[mvt.qualiteId].name+'</td></tr>';
        inner+='<tr><th>Localisation</th><td>'+local[mvt.localId].name+'-'+local[mvt.localId].groupe+'</td></tr>';
        inner+='<tr><th>Stock déclaré </th><td id="stockId"></td></tr>';
        document.getElementById('contents-1').innerHTML=inner;
        inner='<hr><div class="row mb-3">';
        inner+='<div class="col-sm-3">Date<input type="date" class="datepicker form-control" id="daty"/></div>';
        inner+='<div class="col-sm-3">Quantite total inventoré<input type="number" class="form-control" id="qte"/></div>';
        inner+='<div class="col-sm-6"><span class="label ">Observations</span><input type="text" class="form-control" id="comment"/></div>';
        inner+='</div>';
        inner+='<div class="row"><div class="col-sm-4"></div><div class="col-sm-4"><a href="#" class="btn btn-danger btn-block" onclick="saveInv(\''+id+'\',\''+mvt.localId+'\')">Enregistrer</a></div><div class="col-sm-4"></div></div>';
        document.getElementById('contents-2').innerHTML=inner;
        getSolde(mvt.arrId,mvt.produitId,mvt.qualiteId,mvt.localId,"stockId");
    }).catch(function(err){
    alert(err.message);
    });
}
function saveInv(arrId,localId){
        var daty=$("#daty").val();
        var qte=parseFloat($("#qte").val());
    if(qte>0 && daty.length==10){
        var formData=new FormData();
        formData.append('arr',arrId);
        formData.append('local',localId);
        formData.append('qte',qte);
        formData.append('comment',$("#comment").val());
        formData.append('daty',daty);
        axios.post("{{route('register.saveInventoryAction')}}",formData)
        .then(function(res){
            var data=res.data;
            launchAddNew(data.id+'-'+data.local+'-'+data.arrivage);
        })
        .catch(function(err){        
            alert(err.message);
        });
    }        
}
function launchAddNew(id){
        window.location.href ="{{ route('app.addNoewInv1',[':id'])}}".replace(':id', id);
}

function getSolde(arrId,produitId,qualiteId,localId,place){
        var formData=new FormData();
        formData.append('arrId',arrId);
        formData.append('produitId',produitId);
        formData.append('qualiteId',qualiteId);
        formData.append('localId',localId);
        axios.post("{{route('report.soldeArrProdQlt')}}",formData)
        .then(function(res){
            var solde=res.data.qte;
            document.getElementById(place).innerHTML=solde;
            document.getElementById("qte").value=solde;
        })
        .catch(function(err){        
            alert(err.message);
        });
}
$(document).ready(function(){
        var id=@json($mx);
        loading(id);
        loadingData(id);
});
</script>
@endsection