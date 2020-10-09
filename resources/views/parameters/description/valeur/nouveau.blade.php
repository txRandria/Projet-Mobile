@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="#" onclick="parametreNouveauDescription()"><b>Nouveau critère</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="#" onclick="parametreAnalyse()"><b>Paramètres et mesures</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{ route('valeurDescription.index')}}" ><b>Valeur de description</b></a></li>

@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">ID : </span>
            </div>
            <input type="text" class="form-control" placeholder="reference" aria-label="reference" aria-describedby="basic-addon1" id="code">
        </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Nouveau valeur de description : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="valeur">
            </div>

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">COMMENTAIRES </span>
            </div>
            <textarea class="form-control" aria-label="observations et commentaires" id="comment"></textarea>
            </div>

            
            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Type de description : </span>
            </div>
            <select class="form-control btn-warning rounded" id="description">
            <option>Selectionner .....</option>
            </select>
            </div>

    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    function loadingCategory(){
        var count=0;
        var list=@json($list);
        var inner=''
        for(var i in list){
            count++;
            inner+='<option id="'+list[i].id+'">'+list[i].name+'</option>'
        }
        if(count>0){
            $('#description').html(inner);
        }        
        else
        {
            window.location.href = "{{ route('description.create')}}";
        }
    }
    function save(){
        var code=$("#code").val();
        var valeur=$("#valeur").val();
        var comment=$("#comment").val();
        var analyse=$("#description").val();;
        
        var formData=new FormData();
        formData.append('code',code);
        formData.append('valeur',valeur);
        formData.append('comment',comment);
        formData.append('description',analyse);
        sendSave(formData);
    }
    function sendSave(formData){
        axios.post("{{route('valeurDescription.store')}}",formData)
        .then(function(res){
            window.location.href = "{{route('valeurDescription.index')}}";
        })
        .catch(function(err){
            alert(err.message);
        });
    }

    $(document).ready(function(){
        loadingCategory();
    });
</script>
@endsection