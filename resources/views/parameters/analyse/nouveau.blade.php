<h1>GROUPE-ANALYSE</h1>
@extends('layout.app')
@section('sub-menu')

<li class="nav-item dropdown"><a class="btn btn-block dropdown-item" href="#" onclick="parametreAnalyse()">Liste groupe-analyse</a></li>
<li class="nav-item dropdown"><a class="btn btn-block dropdown-item" href="#" onclick="parametreDetailsAnalyse()">les types d'analyse</a></li>
<li class="nav-item dropdown"><a class="btn btn-block dropdown-item" href="#" onclick="parametresCreateNewDetailsAnalyse()">Créer un type analyse</a></li>

@endsection
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-7">
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

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Image </span>
            </div>
            <input type="text" id="image"/>
            </select>
            </div>
    </div><div class="col-md-5">
        <table class="table table-sm table-striped table-bordered" id="list">
        </table>
    </div>
</div>
@endsection
@section('scripts')

<script type="text/javascript">
    function save(){
        var code=$("#code").val();
        var name=$("#name").val();
        var comment=$("#comment").val();  
        var image=$("#image").val();

        var formData=new FormData();
        formData.append('code',code);
        formData.append('name',name);
        formData.append('comment',comment);
        formData.append('image',image);

        sendSave(formData);
    }

    function sendSave(formData){
        axios.post("{{route('analyse.store')}}",formData)
        .then(function(res){
            window.location.href = "{{route('analyse.index')}}";
        })
        .catch(function(err){
            alert(err.message);
        });
    }

    $(document).ready(function(){
        loading();
    });

    function loading(){
        //alert("data")
        var formData=new FormData();
         axios.post("{{route('app.getListAnalyse')}}",formData)
        .then(function(res){
            var data=res.data;
            var inner='';
            for(var i in data){
                inner+='<tr><td>'+data[i].code+'</td><td>'+data[i].name+'</td><td>'+data[i].comment+'</td></tr>';
            }
            document.getElementById("list").innerHTML=inner;
        })
        .catch(function(err){
            alert(err.message);
        });
    }
</script>
@endsection
