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
    <div class="justify-content-center">
        <div id="analyse">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
var list=@json($list);
function loading(){
        var count=0;
        
       // alert(JSON.stringify(list))
        var inner='<table class="table table-sm table-hover table-warning table-striped table-bordered">';
        inner+='<tr><th>#</th><th>NOM DES ANALYSES</th><th>COMMENTAIRES</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr><td>'+list[i].id+'</td><td>'+list[i].name+'</td><td>';
            if(list[i].comment==null)
            inner+='</td>';
            else
            inner+=list[i].comment+'</td>';
            inner+="<td><center><a class=\"btn\" onclick=\"read('"+list[i].name+"')\"><img width=\"20\" src=\"{{ asset('icon/icons8-eye-96.png')}}\" ></a></center></td>";
            inner+="<td><center><a class=\"btn\"><img width=\"20\" src=\"{{ asset('icon/icons8-modifier-64.png')}}\"></a></center></td>";
            inner+="<td><center><a class=\"btn\" onclick=\"deleteSRC('"+list[i].id+"')\"><img width=\"20\" src=\"{{ asset('icon/icons8-effacer-64.png')}}\"></a></center></td></tr>";
        }
        inner+='</table>';
        //alert("produit");
        if(count>0){
            document.getElementById("analyse").innerHTML=inner;
        }else{
            window.location.href = "{{ route('analyse.create')}}";
        }
        
}
function read(src){
    window.location.href = "{{ route('analyse.show','"+src+"')}}";
}
function deleteSRC(src){
   var formData=new FormData();
   formData.append('id',src);
         axios.post("{{route('app.deleteAnalyse')}}",formData)
        .then(function(res){
            window.location.href = "{{ route('analyse.index')}}";
        })
        .catch(function(err){
            alert(err.message);
    });
}
$(document).ready(function(){
        loading();
});
</script>
@endsection