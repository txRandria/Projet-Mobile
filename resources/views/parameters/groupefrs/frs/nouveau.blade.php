@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3"><a class="nav-link btn-outline-success table-bordered" href="{{route('frs.index')}}">Liste des Fournisseurs</a></li>
<li class="nav-item mb-3"><a class="nav-link btn-outline-success table-bordered" href="{{route('groupeFrs.index')}}">Groupes de fournisseurs</a></li>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">CODE : </span>
            </div>
            <input type="text" class="form-control" placeholder="reference" aria-label="reference" aria-describedby="basic-addon1" id="code">
        </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">NOUVEAU FOURNISSEUR : </span>
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
                    <span class="input-group-text" id="inputGroup-sizing-default">CONTACTS : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="tel">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">ADRESSE : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="adresse">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">MESSAGERIE : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="messagerie">
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
            window.location.href = "{{ route('groupeFrs.create')}}";
        }
    }
    function save(){
        var formData=new FormData();
        formData.append('code',$("#code").val());
        formData.append('name',$("#name").val());
        formData.append('groupe',$("#groupe").val());
        formData.append('tel',$("#tel").val());
        formData.append('adresse',$("#adresse").val());
        formData.append('messagerie',$("#messagerie").val());
        formData.append('comment',$("#comment").val());
        sendSave(formData);
    }
    function sendSave(formData){
        axios.post("{{route('frs.store')}}",formData)
        .then(function(res){
            window.location.href = "{{route('frs.index')}}";
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