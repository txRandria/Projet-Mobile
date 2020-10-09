<h1>LOCAL DE PRODUCTION OU MAGASIN DE STOCKAGE</h1>
@extends('layout.app')

@section('sub-menu')
<li class="nav-item mb-3"><a class="nav-link btn btn-primary" href="#" onclick="parametreLocal()">LA LISTE</a></li>
<li class="nav-item mb-3"><a class="nav-link btn btn-primary" href="#" onclick="parametreGrpLocal()">SITE DE PRODUCTION</a></li>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">CODE : </span>
            </div>
            <input type="text" class="form-control" placeholder="reference" aria-label="reference" aria-describedby="basic-addon1" id="code">
        </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">NOUVEAU LOCAL DE STOCKAGE : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="name">
            </div>

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">GROUPE : </span>
            </div>
            <select class="form-control btn-warning rounded" id="groupe">
            <option>Selectionner .....</option>
            </select>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">DESCRIPTIONS : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="description">
            </div>

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">COMMENTAIRES </span>
            </div>
            <textarea class="form-control" aria-label="observations et commentaires" id="comment"></textarea>
            </div>

            
            

    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    function loading(){
        var count=0;
        var list=@json($list);
        var inner=''
        for(var i in list){
            count++;
            inner+='<option id="'+list[i].id+'">'+list[i].groupe+'</option>'
        }
        if(count>0){
            $('#groupe').html(inner);
        }        
        else
        {
            window.location.href = "{{ route('groupeLocal.create')}}";
        }
    }
    function save(){
        var formData=new FormData();
        formData.append('code',$("#code").val());
        formData.append('name',$("#name").val());
        formData.append('groupe',$("#groupe").val());
        formData.append('description',$("#description").val());
        formData.append('comment',$("#comment").val());
        sendSave(formData);
    }
    function sendSave(formData){
        axios.post("{{route('local.store')}}",formData)
        .then(function(res){
            window.location.href = "{{route('local.index')}}";
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