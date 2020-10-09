<h1>SITE DE PRODUCTION</h1>
@extends('layout.app')
@section('sub-menu')

    <li></li>
<li class="nav-item mb-3"><a class="nav-link btn btn-primary" href="#" onclick="parametreNouveauGrpLocal()"> + SITE DE PRODUCTION</a></li>
<li class="nav-item mb-3"><a class="nav-link btn btn-primary" href="#" onclick="parametreLocal()">LOCAL DE STOCKAGE / PRODUCATION </a></li>

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
        var inner='<table class="table table-sm table-hover table-info table-striped table-rounded table-bordered">';
        inner+='<tr><th>#</th><th>SITES</th><th>DESCRIPTIONS</th><th>COMMENTAIRES</th></tr>';
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
            window.location.href = "{{ route('groupeLocal.create')}}";
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection