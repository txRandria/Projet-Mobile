<h1>Entree en stock</h1>

@extends('layout.app2')
@section('sub-menu')
<a class="dropdown-item" href="#">Autres 1</a>
<a class="dropdown-item" href="#">Autres 2</a>
@endsection
@section('contents')
<div class="row">
    <div class="col-md-4">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-dark">N* de REFERENCE : </span>
            </div>
            <select class="form-control btn-success" id="reference">
            <option>Selectionner une reference</option>
            </select>
        </div>
        <hr>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-dark">N* de REFERENCE : </span>
            </div>
            <select class="form-control btn-success" id="reference">
            <option>Selectionner une reference</option>
            </select>
        </div>
    </div>
    <div class="col-md-8 justify-content-center" id="body-contents">
            <table class="table table-sm table-hover table-striped bg-primary" id="place-arrivage">
            
            </table>
            
            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-dark">OBSERVATIONS </span>
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
        var list=@json($lots);
        var inner='<option id="0">Selectionner une reference</option>'
        for(var i in list){
            count++;
            inner+='<option id="'+list[i].id+'">'+list[i].code+'</option>'
        }
        if(count>0){
            $('#reference').html(inner);
        }        
        else
        {
            inner='<center><h3>No product category in database. Please create an categorie ! </h3></center>';
            inner+='<center><h4>Cliquez <a href="">ici</a> pour creer une nouvelle reference </h4></center>';
            document.getElementById('body-contents').innerHTML=inner;
        }
    }
    function getSelectedLot(lot){
        var formData=new FormData();
        formData.append('id',lot);
        axios.post("{{route('app.detailsLot')}}",formData)
        .then(function(res){
            var inner='<tr><th>#</th><th>DATE</th><th>CATEGORIE</th><th>PRODUITS</th><th></th></tr>';


            document.getElementById('place-arrivage').innerHTML=inner;
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    $(document).ready(function(){
        loading();
        $('#reference').change(function(){
            var ref=$('#reference').val();
        });
    });
</script>
@endsection

