@extends('layout.app3')
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div id="lots">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">

function loading(){
        var count=0;
        var list=@json($lots);
        var inner='<table class="table table-hover table-warning table-striped rounded table-bordered">';
        inner+='<tr><th>#</th><th>N* de reference</th><th>Date de livraison</th><th>Categorie</th><th>Produit</th><th>Qualites</th><th>Quantite</th><th>Origine/Fournisseurs</th><th>Responsables</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].id+'</td><td><a href="#" onclick="showLot(\''+list[i].id+'\')">'+list[i].code+'</a></td><td>'+list[i].description+'</td><td>'+list[i].comment+'</td><td>'+list[i].user+'</td></tr>';
        }
        inner+='</table>';
        if(count>0){
            document.getElementById("lots").innerHTML=inner;
        }else{
           document.getElementById("lots").innerHTML='<center><h3>No data</h3></center>';
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection