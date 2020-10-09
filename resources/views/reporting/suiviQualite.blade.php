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
    
@endsection
@section('contents')
<div class="container mb-3" id="contenus"></div>
@endsection
@section('scripts')
<script type="text/javascript">
var categorie=@json($categorie);
var lots=@json($lot);
var produit=@json($produit);
var qualite=@json($qualite);
var local=@json($local);

var listOfArr=[];
$(document).ready(function(){
    loading();
});
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
            inner+='<li class="list-group-item badge d-flex justify-content-between align-items-center bg-success form-control btn" href="#" onclick="showLot(\''+lotsList[i].id+'\',\''+lotsList[i].code+'\',\''+categ+'\')">'+lotsList[i].code+'</li>';
        }
        document.getElementById('lot-list').innerHTML=inner;
}
function showLot(lotId,lotCode,categ){
    document.getElementById('contenus').innerHTML='';
    var formData=new FormData();
    formData.append('id',lotCode);
    axios.post("{{route('report.mvtLocalQualiteProduitByLot')}}",formData)
    .then(function(res){
        var data=res.data.data;
        var qte=res.data.qte;
        var analyse=res.data.analyse;
        var inner='<table class="table table-sm table-bordered table-striped table-hover table-danger mb-3">'
        for(var i in qte){
            if(qte[i]>0){
                var label=i.split('-');
                inner+='<tr><th>'+produit[label[0]].name+' '+qualite[label[1]].name+'</th><td>'+local[label[2]].name+' - '+local[label[2]].groupe+'</td><td>'+qte[i]+'</td>';
                var tmp_res=analyse[i];
                for(var y in tmp_res){
                    inner+='<td><li><span class="badge badge-danger">'+tmp_res[y].date_analyse+'</span><span class="badge badge-warning">'+tmp_res[y].type_analyse+'</span><span class="badge badge-warning">'+tmp_res[y].details_analyse+'</span><span class="badge badge-primary">Valeur = '+tmp_res[y].valeur_analyse+'</span></li></td>';
                }
                inner+='</tr>';
            }
        }
        inner+='</table>';
        document.getElementById('contenus').innerHTML+=inner;
    })
    .catch(function(err){
        alert(err.message);
    });

}

function getSoldeByQualite(){
    var formData=new FormData();
    formData.append('id',mvtId);
    axios.post("{{route('report.getListDetailsAnalyseByAnalyse')}}",formData)
    .then(function(res){
        
    })
    .catch(function(err){
        alert(err.message);
    });
}

</script>
@endsection