@extends('layout.app3')
@section('sub-menu')
<nav class="navbar navbar-expand-lg bg-warning text-dark mt-4">
<ul class="nav flex-column">
    <li class="nav-link"><a href="#" onclick="createNewPerte()"> + Ajouter</a></li>
    <li class="nav-link"><a href="#" onclick="getOperationCharges()"> Charge et coût production </a></li>
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
var list=@json($pertes);
function loading(){
    var inner='<table class="table table-sm table-primary table-hover table-striped table-bordered">';
    for(var i in list){
        inner+='<tr><td>'+list[i].type+'</td></tr>';
    }
    inner+='</table>';
    document.getElementById('contento').innerHTML=inner;
}

$(document).ready(function(){
    loading();
});
</script>
@endsection