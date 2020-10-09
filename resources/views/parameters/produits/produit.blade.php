<center><h2><b>PRODUITS</b></h2></center>
@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="#" onclick="parametreNouveauProduit()"><b> + Produit</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{ route('categorie.index')}}"><b>Categories</b></a></li>

@endsection

@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div id="produit">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
function loadingProduit(){
        var count=0;
        var list=@json($list);
        var inner='<table class="table table-sm table-hover table-info table-striped table-bordered">';
        inner+='<tr><th>#</th><th>CATEGORIES</th><th>Classifications</th><th>PRODUITS</th><th>COMMENTAIRES</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].id+'</td><td>'+list[i].categorie+'</td><td>'+list[i].class+'</td><td>'+list[i].name+'</td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        //alert("produit");
        if(count>0){
            document.getElementById("produit").innerHTML=inner;
        }else{
            window.location.href = "{{ route('produit.create')}}";
        }
        
}
$(document).ready(function(){
        loadingProduit();
});
</script>
@endsection
