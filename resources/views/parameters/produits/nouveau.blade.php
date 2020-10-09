<center><h2><b>PRODUITS</b></h2></center>
@extends('layout.app')
@section('sub-menu')
<li class="nav-item mb-3"><a class="nav-link btn-outline-success" href="{{ route('produit.index')}}" ><b>Les produits</b></a></li>
<li class="nav-item mb-3"><a class="nav-link btn-outline-success" href="{{ route('categorie.index')}}"><b>Categories</b></a></li>
@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">ID : </span>
            </div>
            <input type="text" class="form-control" placeholder="reference" aria-label="reference" aria-describedby="basic-addon1" id="code">
        </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">DESIGNATION : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="name">
            </div>
            
            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">COMMENTAIRES </span>
            </div>
            <textarea class="form-control" aria-label="observations et commentaires" id="comment"></textarea>
            </div>

            
            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">CATEGORIE </span>
            </div>
            <select class="form-control btn-warning rounded" id="category">
            <option>Selectionner .....</option>
            </select>
            </div>

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Classification </span>
            </div>
            <select class="form-control btn-warning rounded" id="class">
            <option>Brute</option>
            <option>intermédiaire</option>
            <option>Final</option>
            </select>
            </div>

            <div class="input-group mb-3" >
            <div class="input-group-prepend">
                <span class="input-group-text">Image </span>
            </div>
            <input type="text" id="image"/>
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
            $('#category').html(inner);
        }        
        else
        {
            window.location.href = "{{ route('categorie.create')}}";
        }
    }
    function save(){
        var code=$("#code").val();
        var name=$("#name").val();
        var comment=$("#comment").val();                                                                                        
        var category=$("#category").val();                                                                                        
        var image=$("#image").val();                                                                                        
        var formData=new FormData();
        formData.append('code',code);
        formData.append('name',name);
        formData.append('comment',comment);
        formData.append('category',category);
        formData.append('class',$("#class").val());
        formData.append('image',image);
        sendSave(formData);
    }
    function sendSave(formData){
        axios.post("{{route('produit.store')}}",formData)
        .then(function(res){
            window.location.href = "{{ route('produit.index')}}";
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