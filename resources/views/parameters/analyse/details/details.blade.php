<h3><img width="70" src="{{ asset('icon/icons8-tube-à-essai-96.png')}}"></h3>
@extends('layout.app3')
@section('sub-menu')
<ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link" href="#" onclick="parametreHome()"><img width="50" src="{{ asset('icon/icons8-menu-512.png') }}"><b> Home</b></a></li>
    <li class="nav-item"><a class="nav-link" href="#" onclick="parametreNouveauAnalyse()"><img width="50" src="{{ asset('icon/icons8-plus-128.png')}}"><b>  Groupe-analyse</b></a></li>
    <li class="nav-item"><a class="nav-link" href="#" onclick="parametresCreateNewDetailsAnalyse()"><img width="50" src="{{ asset('icon/icons8-plus-128.png')}}"><b>  Créer un type analyse</b></a></li>
    
    </ul>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div id="details">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
function loading(){
        var count=0;
        var list=@json($list);
        var inner='<table class="table table-hover table-warning table-striped rounded table-bordered">';
        inner+='<tr><th>#</th><th>Elements analyses</th><th>Analyse</th><th>COMMENTAIRES</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].id+'</td><td>'+list[i].name+'</td><td>'+list[i].analyse+'</td><td>';
            if(list[i].comment==null)
            inner+='</td></tr>';
            else
            inner+=list[i].comment+'</td></tr>';
        }
        inner+='</table>';
        if(count>0){
            document.getElementById("details").innerHTML=inner;
        }else{
            window.location.href = "{{ route('details.create')}}";
        }
        
}
$(document).ready(function(){
        loading();
});
</script>
@endsection