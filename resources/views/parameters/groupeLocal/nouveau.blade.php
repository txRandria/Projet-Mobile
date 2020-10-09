@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{route('groupeLocal.index')}}"><b>SITE</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{ route('local.index')}}"><b>MAGASIN DE STOCKAGE ET LOCAL</b></a></li>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">ID : </span>
            </div>
            <input type="text" class="form-control" placeholder="reference" aria-label="reference" aria-describedby="basic-addon1" id="code" required>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Nouveau SITE : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="groupe" required>
            
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Description : </span>
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
    function save(){                                                                                      
        var formData=new FormData();
        formData.append('code',$("#code").val());
        formData.append('groupe',$("#groupe").val());
        formData.append('description',$("#description").val());
        formData.append('comment',$("#comment").val());
        sendSave(formData);
    }

    function sendSave(formData){
        axios.post("{{route('groupeLocal.store')}}",formData)
        .then(function(res){
            window.location.href = "{{ route('groupeLocal.index')}}";
        })
        .catch(function(err){
            alert(err.message);
        });
    }
</script>
@endsection