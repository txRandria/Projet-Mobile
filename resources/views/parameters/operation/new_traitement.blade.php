@extends('layout.app3')
@section('sub-menu')
<nav class="navbar navbar-expand-lg bg-warning text-dark mt-4">
<ul class="nav flex-column">
    <li class="nav-link"><a href="#" onclick="getOperationProcess()">Processus de production</a></li>
    <li class="nav-link"><a href="#" onclick="getOperationCharges()"> Charge et coût production </a></li>
    <li class="nav-link"><a href="#" onclick="getOperationPertes()"> Perte sur production</a></li>
</ul>
</nav>
@endsection
@section('contents')
 <center>
    <h3>PROCESSUS DE PRODUCTION</h3>
    </center>
<div id="contento">


</div>
@endsection 
@section('scripts')
<script type="text/javascript">
var categ=@json($categ);
function loading(){
    var inner='<div class="row mb-3"><div class="col-sm-2">Categorie cible : </div><div class="col-sm-4"><select class="form-control" id="categorie">';
    for(var i in categ){
        inner+='<option>'+categ[i].name+'</option>';
    }
    inner+='</select></div></div>';
    inner+='<div class="row mb-3"><div class="col-sm-2">Code : </div><div class="col-sm-4"><input class="form-control" id="code"/></div></div>';
    inner+='<div class="row mb-3"><div class="col-sm-4">DESIGNATION : <input type="text" class="form-control" id="name"/></div>';    
    inner+='<div class="col-sm-6">Comment : <input type="text" class="form-control" id="comment"/></div>';
    
    inner+='<div class="col-sm-2"></div></div><br><div class="row mb-3"><div class="col-sm-4"></div><div class="col-sm-4"><button class="btn btn-block btn-danger" onclick="saveProcess()">Enregistrer</button></div><div class="col-sm-4"></div></div>';
    document.getElementById('contento').innerHTML=inner;
}

function saveProcess(){
    var formData=new FormData();
    formData.append('code',$("#code").val());
    formData.append('categ',$("#categorie").val());
    formData.append('name',$("#name").val());
    formData.append('comment',$("#comment").val());
    axios.post("{{route('register.saveNewTypeProcess')}}",formData)
    .then(function(res){
       // document.getElementById('c_'+arrId).innerHTML="Enregistrement réussi";
      alert(JSON.stringify(res.data))
    })
    .catch(function(err){
        alert(err.message);
    });
}

$(document).ready(function(){
    loading();
});
</script>
@endsection