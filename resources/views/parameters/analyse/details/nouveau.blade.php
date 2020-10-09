@extends('layout.app')
@section('sub-menu')
<a class="dropdown-item" href="#">Autres 1</a>
<a class="dropdown-item" href="#">Autres 2</a>
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
                    <span class="input-group-text" id="inputGroup-sizing-default">OBJET DE L'ANALYSE : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="name">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Unite de mesure : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="unite">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Valeur max : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="max">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Valeur min : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="min">
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Valeur moyenne : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="moyenne">
            </div>

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">COMMENTAIRES </span>
            </div>
            <textarea class="form-control" aria-label="observations et commentaires" id="comment"></textarea>
            </div>

            
            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Analyse : </span>
            </div>
            <select class="form-control btn-warning rounded" id="analyse">
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
            $('#analyse').html(inner);
        }        
        else
        {
            window.location.href = "{{ route('analyse.create')}}";
        }
    }
    function save(){
        var code=$("#code").val();
        var name=$("#name").val();
        var unite=$("#unite").val();
        var min=$("#min").val();
        var max=$("#max").val();
        var moyen=$("#moyenne").val();
        var comment=$("#comment").val();
        var analyse=$("#analyse").val();;
        
        var formData=new FormData();
        formData.append('code',code);
        formData.append('name',name);
        formData.append('unite',unite);
        formData.append('min',min);
        formData.append('max',max);
        formData.append('moyen',moyen);
        formData.append('comment',comment);
        formData.append('analyse',analyse);
        sendSave(formData);
    }
    function sendSave(formData){
        axios.post("{{route('details.store')}}",formData)
        .then(function(res){
            window.location.href = "{{route('details.index')}}";
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