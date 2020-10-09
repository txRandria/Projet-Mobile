<h1>FOURNISSEURS</h1>
@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3"><a class="nav-link btn-outline-success table-bordered" href="{{route('frs.create')}}">+ Fournisseur</a></li>
<li class="nav-item mb-3"><a class="nav-link btn-outline-success table-bordered" href="{{route('groupeFrs.index')}}">Groupes de fournisseurs</a></li>
@endsection

@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div id="frs">
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
        inner+='<tr><th>#</th><th>FOURNISSEURS</th><th>GROUPE</th><th>CONTACTS</th><th>MESSAGERIES</th><th>ADRESSES</th><th>INFOS</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].code+'</td><td>'+list[i].name+'</td><td>'+list[i].groupe+'</td><td>'+list[i].tel+'</td><td>'+list[i].email+'</td><td>'+list[i].adresse+'</td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        //alert("produit");
        if(count>0){
            document.getElementById("frs").innerHTML=inner;
        }else{
            window.location.href = "{{ route('frs.create')}}";
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection
