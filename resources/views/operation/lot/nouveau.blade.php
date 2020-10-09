<h1>Nouveau groupe</h1>
@extends('layout.app3')
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-8" id="body-contents">
    <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-secondary" id="basic-addon1">N* de REFERENCE / CODE DU GROUPE : </span>
            </div>
            <input type="text" class="form-control" placeholder="n*"  id="code" required>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-secondary" id="inputGroup-sizing-default">DESCRIPTION DU GROUPE : </span>
                </div>
                <input type="text" class="form-control"  id="description" required>
                </div>


            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-secondary">OBSERVATIONS </span>
            </div>
            <textarea class="form-control" aria-label="observations et commentaires" id="comment"></textarea>
            </div>

             <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-secondary">CATEGORIE DE PRODUIT CONCERNE : </span>
            </div>
            <select class="form-control btn-success" id="categorie">
            <option>Selectionner un item</option>
            </select>
            </div>

           
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary mt-4">
<div class="container">
    <ul class="navbar-nav ml-lg-auto">
        <li class="nav-item mb-3">
        <a class="nav-link btn-success" href="#" onclick="save()">Enregister
        </a>
        </li> 
    </ul>
    </div>
</div>
</nav>
@endsection
@section('scripts')

<script type="text/javascript">
    function save(){
        var code=$("#code").val();
        var description=$("#description").val();
        var comment=$("#comment").val();  
        var categorie=$("#categorie").val();

        var formData=new FormData();
        formData.append('code',code);
        formData.append('description',description);
        formData.append('comment',comment);
        formData.append('categorie',categorie);

        sendSave(formData);
    }

    function sendSave(formData){
        axios.post("{{route('register.nouveaulot')}}",formData)
        .then(function(res){
            window.location.href = "{{ route('app.lot')}}";
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    function loading(){
        var count=0;
        var list=@json($category);
        var inner=''
        for(var i in list){
            count++;
            inner+='<option id="'+list[i].id+'">'+list[i].name+'</option>'
        }
        if(count>0){
            $('#categorie').html(inner);
        }        
        else
        {
            inner='<center><h3>No product category in database. Please create an categorie ! </h3></center>';
            document.getElementById('body-contents').innerHTML=inner;
        }
    }

    $(document).ready(function(){
        loading();
    });
</script>
@endsection

