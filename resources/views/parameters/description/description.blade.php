<CENTER><h2><B>DESCRIPTION DES PRODUITS</B></h2></CENTER>
@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="#" onclick="parametreNouveauDescription()"><b>Nouveau critère</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="#" onclick="parametreAnalyse()"><b>Paramètres et mesures</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{ route('valeurDescription.index')}}" ><b>Valeur de description</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{ route('valeurDescription.create')}}" ><b>Nouveau valeur de description</b></a></li>

@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div id="description">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
function loading(){
        var count=0;
        var list=@json($list);
        var inner='<table class="table table-sm table-hover table-info table-striped rounded table-bordered">';
        inner+='<tr><th>#</th><th>Descriptions possibles</th><th>COMMENTAIRES</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].id+'</td><td>'+list[i].name+'</td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        if(count>0){
            document.getElementById("description").innerHTML=inner;
        }else{
            window.location.href = "{{ route('description.create')}}";
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection