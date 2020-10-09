<h1>LOCAL DE PRODUCTION OU MAGASIN DE STOCKAGE</h1>
@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3"><a class="nav-link btn btn-primary" href="#" onclick="parametreNouveauLocal()"> + Local</a></li>
<li class="nav-item mb-3"><a class="nav-link btn btn-primary" href="#" onclick="parametreGrpLocal()">SITE DE PRODUCTION</a></li>
@endsection

@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div id="site">
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
        inner+='<tr><th>#</th><th>LOCALS</th><th>SITES</th><th>DESCRIPTIONS</th><th>INFORMATIONS</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].code+'</td><td>'+list[i].name+'</td><td>'+list[i].groupe+'</td><td>'+list[i].description+'</td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        //alert("produit");
        if(count>0){
            document.getElementById("site").innerHTML=inner;
        }else{
            window.location.href = "{{ route('local.create')}}";
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection
