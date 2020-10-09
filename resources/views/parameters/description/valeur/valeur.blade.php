<CNETER><h2><B>DESCRIPTIONS DES PRODUITS</B></h2></CENTER>
@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="#" onclick="parametreNouveauDescription()"><b>Nouveau critère</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="#" onclick="parametreAnalyse()"><b>Paramètres et mesures</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{ route('valeurDescription.create')}}" ><b> + Valeur de description</b></a></li>
@endsection

@section('contents')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div id="valeur">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
function loadingProduit(){
        var count=0;
        var list=@json($list);
        var inner='<table class="table table-hover table-warning table-striped rounded table-bordered">';
        inner+='<tr><th>#</th><th>Type de description</th><th>Valeur de description</th><th>COMMENTAIRES</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].id+'</td><td>'+list[i].description+'</td><td>'+list[i].valeur+'</td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        //alert("produit");
        if(count>0){
            document.getElementById("valeur").innerHTML=inner;
        }else{
            window.location.href = "{{ route('valeurDescription.create')}}";
        }
        
}
$(document).ready(function(){
        loadingProduit();
});
</script>
@endsection
