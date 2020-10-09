<h1><img width="70" src="{{ asset('icon/icons8-ungroup-objects-96.png') }}"></h1>
@extends('layout.app3')
@section('sub-menu')
<ul class="nav flex-column">
<li class="nav-item"><a class="nav-link" href="#" onclick="parametreHome()"><img width="50" src="{{ asset('icon/icons8-menu-512.png') }}"><b> Home</b></a></li>
<li class="nav-item"><a class="nav-link" href="#" onclick="parametreNouveauCategorie()">
<img width="50" src="{{ asset('icon/icons8-plus-128.png') }}"><b> Catégorie</b></a></li>
<li class="nav-item"><a class="nav-link" href="#" onclick="parametreNouveauProduit()">
<img width="50" src="{{ asset('icon/icons8-plus-128.png') }}"><b> Produit</b></a></li>
<li class="nav-item"><a class="nav-link" href="#" onclick="parametreProduit()">
<img width="50" src="{{ asset('icon/icons8-produit-96.png') }}"><b> Listes des produits</b></a></li>
</ul>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div id="category">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
function loading(){
        var count=0;
        var list=@json($list);
        var inner='<table class="table table-sm table-hover table-warning table-striped rounded table-bordered">';
        inner+='<tr><th>#</th><th>CATEGORIES</th><th>COMMENTAIRES</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].id+'</td><td><a href="#" onclick="read()">'+list[i].name+'</a></td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        //alert("produit");
        if(count>0){
            document.getElementById("category").innerHTML=inner;
        }else{
            window.location.href = "{{ route('categorie.create')}}";
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection