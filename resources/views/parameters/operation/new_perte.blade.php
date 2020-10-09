@extends('layout.app3')
@section('sub-menu')
<nav class="navbar navbar-expand-lg bg-warning text-dark mt-4">
<ul class="nav flex-column">
    <li class="nav-link"><a href="#" onclick="getOperationCharges()"> Charges</a></li>
    <li class="nav-link"><a href="#" onclick="getOperationPertes()"> Perte sur production </a></li>
    <li class="nav-link"><a href="#" onclick="createNewProcess()"> Processus de production</a></li>
</ul>
</nav>
@endsection
@section('contents')
<center>
    <h3>PERTE SUR PRODUCTION</h3>
    </center>
<div id="contento">

</div>
@endsection 
@section('scripts')
<script type="text/javascript">

function loading(){
    var inner='<div class="row"><div class="col-sm-2"></div>';
    inner+='<div class="col-sm-4">Type : <input type="text" class="form-control" id="type"/></div>';    
    inner+='<div class="col-sm-6">Comment : <input type="text" class="form-control" id="comment"/></div>';
    
    inner+='</div><br><div class="row"><div class="col-sm-4"></div><div class="col-sm-4"><button class="btn btn-block btn-danger" onclick="savePerte()">Enregistrer</button></div><div class="col-sm-4"></div></div>';
    document.getElementById('contento').innerHTML=inner;
}
function savePerte(){
    var formData=new FormData();
    formData.append('type',$("#type").val());
    formData.append('comment',$("#comment").val());
    axios.post("{{route('register.saveNewTypePerte')}}",formData)
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