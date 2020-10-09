<h1>QUALITE</h1>
@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3"><a class="nav-link btn-outline-success" href="#" onclick="parametreNouveauQualite()"> + Qualite</a></li>
<li class="nav-item mb-3"><a class="nav-link btn-outline-success" href="#">Categorie</a></li>
<li class="nav-item mb-3"><a class="nav-link btn-outline-success" href="#">Produit</a></li>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div id="qualite">
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
        inner+='<tr><th>#</th><th>Qualites</th><th>classifications</th><th>Categories cibles</th><th>Commentaires et description</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].id+'</td><td>'+list[i].name+'</td><td>'+list[i].class+'</td><td>'+list[i].categorie+'</td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        if(count>0){
            document.getElementById("qualite").innerHTML=inner;
        }else{
            window.location.href = "{{ route('qualite.create')}}";
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection