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
            <h5 class="modal-title" id="reportTitle">PRIX</h5>
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
var produit=@json($produit);
var qualite=@json($qualite);

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
        var inner='<table class="table table-sm table-striped table-hover bg-info"><tr><th>#</th><th>Date de réception</th><th>Categorie</th><th>Produit</th><th>Quantité à la réception</th><th>Prix unitaire</th><th>Taux taxe</th><th>Montant</th><th>Fournisseurs</th><th></th></tr><tbody id="trb"></tbody></table>';
        document.getElementById('contenus').innerHTML=inner;
        var tmp=res.data;
        for(var i in tmp){
            var data=tmp[i].data;
            var xxlot=tmp[i].lot;
            inner='<tr><td>'+data.id+'</td><td>'+data.date_arrive+'</td><td>'+xxlot.categorie+' '+xxlot.code+'</td><td>'+data.produit+' '+data.qualite+'</td><td>'+data.stock+'</td><td id="puId-'+data.id+'"></td><td id="txId-'+data.id+'"></td><td id="mttId-'+data.id+'"></td><td>'+data.fournisseur+'</td><td><a href="#" class="badge badge-danger btn" onclick="insertPrix(\''+data.id+'\',\''+data.stock+'\')">Définir</a></td></tr>';
            document.getElementById('trb').innerHTML+=inner;
            lecturePrix(data.id);
        }

    })
    .catch(function(err){
        alert(err.message);
    });

}
function insertPrix(id,qte){
    document.getElementById('parent').innerHTML='';
    var formData=new FormData();
    formData.append('arrId',id);
    axios.post("{{route('report.reportPrixByArrivage')}}",formData)
    .then(function(res){
        var integr=0;
        var inner='';
        var data=res.data.data;
        var lastdata=null;
        for(var i in data){
            integr++;
            lastdata=data;
        }
        if(integr>0){
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Date : </span></div><input type="date" class="datepicker" onblur="calculer(\''+id+'\')" id="date_id" value="'+lastdata.daty+'"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Quantité : </span></div><input type="number"id="qte_id" onblur="calculer(\''+id+'\')" value="'+qte+'"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Prix unitaire : </span></div><input type="number" id="pu_id" onblur="calculer(\''+id+'\')" value="'+lastdata.prix+'"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Taux taxe : </span></div><input type="number" id="taxe_id" onblur="calculer(\''+id+'\')" value="'+lastdata.taxe+'"/> % </div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Montant HT : </span></div><input type="number" id="mht_id" onblur="calculer(\''+id+'\')" value="0"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Montant TTC : </span></div><input type="number" id="mttc_id" onblur="calculer(\''+id+'\')" value="0"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Contact commercial : </span></div><input type="text" id="comm_id" onblur="calculer(\''+id+'\')" value="'+lastdata.commercial+'"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Observations : </span></div><input type="text" id="obs_id" onblur="calculer(\''+id+'\')" value="'+lastdata.commentAchat+'"/></div>';
        }else{
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Date : </span></div><input type="date" class="datepicker" onblur="calculer(\''+id+'\')" id="date_id"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Quantité : </span></div><input type="number"id="qte_id" onblur="calculer(\''+id+'\')" value="'+qte+'"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Prix unitaire : </span></div><input type="number" onblur="calculer(\''+id+'\')" id="pu_id" value="0"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Taux taxe : </span></div><input type="number" onblur="calculer(\''+id+'\')" id="taxe_id" value="20"/> % </div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Montant HT : </span></div><input type="number" onblur="calculer(\''+id+'\')" id="mht_id" value="0"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Montant TTC : </span></div><input type="number" onblur="calculer(\''+id+'\')" id="mttc_id" value="0"/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Contact commercial : </span></div><input type="text" id="comm_id" onblur="calculer(\''+id+'\')" value=""/></div>';
            inner+='<div class="input-group input-group-sm mb-2"><div class="input-group-prepend"><span class="input-group-text bg-primary" id="basic-addon1">Observations : </span></div><input type="text" id="obs_id" onblur="calculer(\''+id+'\')" value=""/></div>';
        }
        inner+='<center id="command"></center>';
        document.getElementById('parent').innerHTML=inner;
        $("#myModal").modal('show');
        calculer();
    })
    .catch(function(err){
        alert(err.message);
    });
}
function lecturePrix(id){
    var formData=new FormData();
    formData.append('arrId',id);
    axios.post("{{route('report.reportPrixByArrivage')}}",formData)
    .then(function(res){
       $("#puId-"+id).html(res.data.data.prix);
       $("#txId-"+id).html(res.data.taxe);
       $("#mttId-"+id).html(res.data.ttc);
    })
    .catch(function(err){
        alert(err.message);
    });
}
function calculer(id){
    var qte=parseFloat($("#qte_id").val());
    var pu=parseFloat($("#pu_id").val());
    var tx_taxe=parseFloat($("#taxe_id").val())*qte*pu*0.01;
    var mtt_ht=qte*pu;
    var mtt_ttc=tx_taxe+mtt_ht;
    $("#mht_id").val(mtt_ht);
    $("#mttc_id").val(mtt_ttc);
    setCommand(id);
}
function setCommand(id){
    var mtt_ttc=parseFloat($("#mttc_id").val());
    var date_id=$("#date_id").val();
    if(mtt_ttc>0 && date_id.length>0){
        document.getElementById('command').innerHTML='<button onclick="savePrix(\''+id+'\')">Enregistrer</button>';
    }
}
function savePrix(id){
    var formData=new FormData();
    formData.append('arrId',id);
    formData.append('comm',$("#comm_id").val());
    formData.append('daty',$("#date_id").val());
    formData.append('pu',parseFloat($("#pu_id").val()));
    formData.append('taxe',parseFloat($("#taxe_id").val()));
    formData.append('obs',$("#obs_id").val());
  
    axios.post("{{route('register.saveAchat')}}",formData)
    .then(function(res){
       
    })
    .catch(function(err){
        alert(err.message);
    });
}
</script>
@endsection