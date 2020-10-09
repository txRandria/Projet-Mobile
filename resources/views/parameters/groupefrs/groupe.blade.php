<h1>Groupe fournisseur</h1>
@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3"><a class="nav-link btn-outline-success table-bordered" href="{{route('groupeFrs.create')}}"> + Groupes de fournisseurs</a></li>
<li class="nav-item mb-3"><a class="nav-link btn-outline-success table-bordered" href="{{route('frs.index')}}">Fournisseurs</a></li>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div id="groupe">
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
        inner+='<tr><th>#</th><th>GROUPES</th><th>DESCRIPTIONS</th><th>COMMENTAIRES</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].code+'</td><td><a href="#" onclick="read()">'+list[i].groupe+'</a></td><td>'+list[i].description+'</td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        if(count>0){
            document.getElementById("groupe").innerHTML=inner;
        }else{
            window.location.href = "{{ route('groupeFrs.create')}}";
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection