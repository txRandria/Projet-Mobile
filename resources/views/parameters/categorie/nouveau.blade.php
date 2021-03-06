@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{route('categorie.index')}}"><b>Categories</b></a></li>
<li class="nav-item mb-3 btn btn-secondary"><a class="nav-link" href="{{ route('produit.index')}}"><b>Produits</b></a></li>
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
                    <span class="input-group-text" id="inputGroup-sizing-default">DESIGNATION : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="name" required>
                </div>


            <div class="input-group">
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
        var code=$("#code").val();
        var name=$("#name").val();
        var comment=$("#comment").val();                                                                                        
        var formData=new FormData();
        formData.append('code',code);
        formData.append('name',name);
        formData.append('comment',comment);
        sendSave(formData);
    }

    function sendSave(formData){
        axios.post("{{route('categorie.store')}}",formData)
        .then(function(res){
           // var data=res.data;
            window.location.href = "{{ route('categorie.index')}}";
        })
        .catch(function(err){
            alert(err.message);
        });
    }
</script>
@endsection