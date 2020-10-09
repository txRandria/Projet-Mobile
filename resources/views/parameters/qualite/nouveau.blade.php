@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3"><a class="nav-link btn-outline-success" href="#">Categorie</a></li>
<li class="nav-item mb-3"><a class="nav-link btn-outline-success" href="#">Produit</a></li>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Categorie : </span>
        </div>
        <select class="form-control btn-success" id="categorie"></select>               
        </div>
        
        <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Classification  : </span>
        </div>
        <select class="form-control btn-success" id="class">
            <option>Brute</option>
            <option>intermédiaire</option>
            <option>Final</option>
        </select>               
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">ID : </span>
            </div>
            <input type="text" class="form-control" placeholder="reference" aria-label="reference" aria-describedby="basic-addon1" id="code" required>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Qualite : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="qualite" required>
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
    var categorie=@json($categorie);
    function save(){
        if(categorie.length>0){
            var code=$("#code").val();
            var name=$("#qualite").val();
            var comment=$("#comment").val();                                                                                        
            var formData=new FormData();
            formData.append('code',code);
            formData.append('qualite',name);
            formData.append('categorie',$("#categorie").val());
            formData.append('class',$("#class").val());
            formData.append('comment',comment);
            sendSave(formData);
        }
    }

    function sendSave(formData){
        axios.post("{{route('qualite.store')}}",formData)
        .then(function(res){
            window.location.href = "{{ route('qualite.index')}}";
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    $(document).ready(function(){
        document.getElementById('categorie').innerHTML='';
        for(var i in categorie){
            document.getElementById('categorie').innerHTML+='<option>'+categorie[i].name+'</option>';
        }
});
</script>
@endsection