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
<div class="container mb-3" id="contenus-1"></div>
<div class="container mb-3" id="contenus"></div>
<div class="container mb-3" id="contenus-2"></div>
<div class="container mb-3" id="contenus-3"></div>
@endsection
@section('scripts')
<script type="text/javascript">

var categorie=@json($categorie);
var lots=@json($lot);
var produit=@json($produit);
var qualite=@json($qualite);
var charge=@json($charge);

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
    var formData=new FormData();
    formData.append('id',lotCode);
    axios.post("{{route('app.detailsLot')}}",formData)
    .then(function(res){
        viewAllCharges(lotId,lotCode);
        var inner='<table class="table table-sm table-striped table-hover bg-info"><tr><th>#</th><th>Date de réception</th><th>Categorie</th><th>Produit</th><th>Quantité à la réception</th><th>Prix unitaire</th><th>Taux taxe</th><th>Montant</th><th>Fournisseurs</th><th></th></tr><tbody id="trb"></tbody></table>';
        document.getElementById('contenus').innerHTML=inner;
        var tmp=res.data;
        for(var i in tmp){
            var data=tmp[i].data;
            var xxlot=tmp[i].lot;
            inner='<tr><td>'+data.id+'</td><td>'+data.date_arrive+'</td><td>'+xxlot.categorie+' '+xxlot.code+'</td><td>'+data.produit+' '+data.qualite+'</td><td>'+data.stock+'</td><td></td><td></td><td></td><td>'+data.fournisseur+'</td></tr>';
            document.getElementById('trb').innerHTML+=inner;

        }

    })
    .catch(function(err){
        alert(err.message);
    });

}
function saveCharge(id,lotCode){
    var formData=new FormData();
    formData.append('arrId',id);
    formData.append('type',$("#charge_id").val());
    formData.append('valeur',$("#valeur_id").val());
    formData.append('obs',$("#comment_id").val());
    axios.post("{{route('register.saveCharge')}}",formData)
    .then(function(res){
        clickLot(id,lotCode,"");
    })
    .catch(function(err){
        alert(err.message);
    });
}
function viewAllCharges(lotId,lotCode){
//report.reportChargeByArrivage
    var formData=new FormData();
    formData.append('arrId',lotId);
    axios.post("{{route('report.reportChargeByArrivage')}}",formData)
    .then(function(res){
        var info=res.data.arr;
        var list=res.data.data;
        var inner='<div class="row"><div class="col-sm-3"><h5><b>Code : </b></h5></div><div class="col-sm-4">'+info.code+'</div></div>';
        inner+='<div class="row"><div class="col-sm-3"><h5><b>Description : </b></h5></div><div class="col-sm-4">'+info.description+'</div></div>';
        inner+='<div class="row"><div class="col-sm-3"><h5><b>Commentaires : </b></h5></div><div class="col-sm-4">'+info.comment+'</div></div>';
         document.getElementById('contenus-1').innerHTML=inner;
         inner='<center><h3>Autres charges</h3></center><table class="table table-sm table-bordered table-striped bg-secondary">';
         var total=0;
         for(var i in list){
             inner+='<tr><td>'+list[i].created_at+'</td><td>'+list[i].chargeAttachable_type+'</td><td>'+list[i].valeur+'</td><td>'+list[i].comment+'</td></tr>';
             total+=list[i].valeur;
         }
         inner+='<tr><th colspan="2">TOTAL</th><th>'+total+'</th></tr></table>';
         document.getElementById('contenus-2').innerHTML=inner;
         inner='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Type charge : </span></div><select class="form-control bg-info" id="charge_id">';
         for(var i in charge){
              inner+='<option id="opt-'+charge[i].id+'">'+charge[i].type+'</option>';
         }
         inner+='</select></div>';
         inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Valeur charge : </span></div><input type="number" class="form-control" id="valeur_id"/></div>';
         inner+='<div class="input-group input-group-sm mb-3"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Commentaires : </span></div><input type="text" class="form-control" id="comment_id"/></div>';
         inner+='<center><button class="btn btn-danger" onclick="saveCharge(\''+lotId+'\',\''+lotCode+'\')">Enregistrer</button></center>';
         document.getElementById('contenus-3').innerHTML=inner;
    })
    .catch(function(err){
        alert(err.message);
    });
}
</script>
@endsection