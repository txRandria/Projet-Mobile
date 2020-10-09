@extends('layout.app3')
@section('sub-menu')
    <ul class="nav flex-column" id="analyse-list">
    
    </ul>
@endsection
    @section('contents')
    <div class="justify-content-center" id="contento">
        <table id="aff" class="table table-sm table-striped table-bordered px-2">
        </table>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    var categorie=@json($categorie);
    var lots=@json($lot);
    var produit=@json($produit);
    var qualite=@json($qualite);
    var groupeAnalyse=@json($groupe);
    var detailsAnalyse=@json($analyse);

function selectCateg(){
        var categ=$("#category").val();
        var inner='';
        document.getElementById('lot-list').innerHTML=inner;
        var lotsList=lots[categ];
        for(var i in lotsList){
            inner+='<li class="list-group-item badge d-flex justify-content-between align-items-center bg-success form-control" href="#" onclick="clickLot(\''+lotsList[i].id+'\',\''+lotsList[i].code+'\',\''+categ+'\')">'+lotsList[i].code+'</li>';
        }
        document.getElementById('lot-list').innerHTML=inner;
}

$(document).ready(function(){
    loading();
});
function loading(){
    var inner='<option>SELECTIONNER ICI POUR COMMENCER</option>';
    document.getElementById('category').innerHTML=inner;
    for(var i in categorie){
        inner='<option>'+categorie[i].name+'</option>';
        document.getElementById('category').innerHTML+=inner;
    }
}

</script>
@endsection