<h1>GROUPE</h1>
@extends('layout.app3')
@section('contents')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div id="lots">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
function loading(){
        var count=0;
        var list=@json($lots);
        var inner='<table class="table table-sm table-hover table-warning table-striped rounded table-bordered">';
        inner+='<tr><th>#</th><th>N* de reference</th><th>Descriptions</th><th>Observations</th><th>Responsables</th></tr>';
        for(var i in list){
            count++;
            inner+='<tr onclick="readLot(\''+list[i].code+'\')"><td>'+list[i].id+'</td><td><a href="#" onclick="readLot(\''+list[i].code+'\')">'+list[i].code+'</a></td><td>'+list[i].description+'</td><td>'+list[i].comment+'</td><td>'+list[i].user+'</td></tr>';
        }
        inner+='</table>';
        if(count>0){
            document.getElementById("lots").innerHTML=inner;
        }else{
           document.getElementById("lots").innerHTML='<center><h3>No data</h3></center>';
        }
        
}
$(document).ready(function(){
        loading();
});
function readLot(id){
    var formData=new FormData();
    formData.append('id',id);
    axios.post("{{route('app.detailsLot')}}",formData)
    .then(function(res){
        var data=res.data;
        var inner='<table class="table table-sm table-success table-striped table-hover table-bordered table-rounded">';
        inner+='<tr><th>N*</th><th>DATE DE LIVRAISON</th><th>CATEGORIE DE PRODUIT</th><th>PRODUIT</th><th>QUALITE</th><th>QUANTITE</th><th>RESPONSABLE</th></tr>';
        for(var i in data){
            inner+='<tr><td>'+data[i]['data'].lot+'</td><td>'+data[i]['data'].date_arrive+'</td><td></td><td>'+data[i]['data'].produit+'</td><td>'+data[i]['data'].qualite+'</td><td></td><td>'+data[i]['data'].responsable+'</td>';
            var analyse=data[i]['analyse'];
            var descript=data[i]['description'];
            var count=0;
           // alert(JSON.stringify(analyse))
            for(var x in analyse){
                count++;
            }
            inner+='<td><span class="btn badge badge-danger" href="#" onclick="readResultats(\''+data[i]['data'].id+'\')">'+count+' Resultat(s)</span></td>';
            
            count=0;
            for(var x in descript){
                count++;
            }
             inner+='<td><span class="btn badge badge-info" href="#" onclick="readDescription(\''+data[i]['data'].id+'\')">'+count+' Description(s)</span></td>';
            inner+='</tr>'
        }
        inner+='</table>';
         document.getElementById("lots").innerHTML=inner;
    })
    .catch(function(err){
        alert(err.message);
    });
}
function readResultats(id){
    var formData=new FormData();
    formData.append('id',id);
    axios.post("{{route('app.getResultatAnalyseArrivage')}}",formData)
    .then(function(res){
        var inner='<div></div><center><button class="btn btn-danger" onclick="createAnResultat(\''+id+'\')">Ajouter un resultat analyse</button></center></div><hr>';
        inner+='<table class="table table-sm table-bordered table-info">';
        var data=res.data;
        inner+='<tr><th>DATE DE L\'ANALYSE</th><th>TYPE D\'ANALYSE</th><th>OBJET DE L\'ANALYSE</th><th>VALEUR</th><th></th><th></th></tr>';
        for(var i in data){
            inner+='<tr><td>'+data[i].date_analyse+'</td><td>'+data[i].type_analyse+'</td><td>'+data[i].details_analyse+'</td><td>'+data[i].valeur_analyse+'</td><td></td></td><td>'+data[i].responsable+'</td></tr>';
        }
        inner+='</table>';
        //alert(JSON.stringify(data));
         document.getElementById("lots").innerHTML=inner;

    })
    .catch(function(err){
        alert(err.message);
    });
}
function readDescription(id){
    var formData=new FormData();
    formData.append('id',id);
    axios.post("{{route('app.getResultatDescriptionArrivage')}}",formData)
    .then(function(res){
        var inner='<div></div><center><button class="btn btn-danger" onclick="createAnDescription(\''+id+'\')">Ajouter une description du produit</button></center></div><hr>';
        inner+='<table class="table table-sm table-bordered table-info">';
        var data=res.data;
        inner+='<tr><th>CRITERE DE DESCRIPTION</th><th>VALEUR</th><th></th><th></th></tr>';
        for(var i in data){
            inner+='<tr><td>'+data[i].type_description+'</td><td>'+data[i].valeur_description+'</td><td></td></td><td>'+data[i].responsable+'</td></tr>';
        }
        inner+='</table>';
        //alert(JSON.stringify(data));
         document.getElementById("lots").innerHTML=inner;

    })
    .catch(function(err){
        alert(err.message);
    });
}
function selectTypeAnalyse(){
    document.getElementById("espace-saisie").innerHTML='';
    var analyse=$("#type_analyse").val();
    var formData=new FormData();
    formData.append('analyse',analyse);
    axios.post("{{route('app.saisieDetailsResultats')}}",formData)
    .then(function(res){
        var inner=res.data;
         document.getElementById("espace-saisie").innerHTML=inner;
    })
    .catch(function(err){
        alert(err.message);
    });
}
function selectCritereDescription(){
    document.getElementById("espace-saisie").innerHTML='';
    var critere=$("#critere").val();
    var formData=new FormData();
    formData.append('critere',critere);
    axios.post("{{route('app.saisieValeurDescription')}}",formData)
    .then(function(res){
        var inner=res.data;
         document.getElementById("espace-saisie").innerHTML=inner;
    })
    .catch(function(err){
        alert(err.message);
    });
}

function createAnResultat(id){
    var formData=new FormData();
    formData.append('id',id);
    axios.post("{{route('app.saisieResultats')}}",formData)
    .then(function(res){
        var inner=res.data;
         document.getElementById("lots").innerHTML=inner;

    })
    .catch(function(err){
        alert(err.message);
    });
}
function createAnDescription(id){
    var formData=new FormData();
    formData.append('id',id);
    axios.post("{{route('app.saisieDescription')}}",formData)
    .then(function(res){
        var inner=res.data;
         document.getElementById("lots").innerHTML=inner;
    })
    .catch(function(err){
        alert(err.message);
    });
}
function defineDescription(){
    document.getElementById("valeur-infos").innerHTML='';
    document.getElementById("confirm").innerHTML='';
    var valeur=$("#d-value").val();
    var formData=new FormData();
    formData.append('id',valeur);
    axios.post("{{route('app.getValeurDescriptionInfos')}}",formData)
    .then(function(res){
        var inner=res.data;
        for(var i in inner){
            document.getElementById("valeur-infos").innerHTML=inner[i].comment;
            document.getElementById("confirm").innerHTML='<button class="btn btn-danger" onclick="saveDescription()">Enregistrer</button>';
        }
    })
    .catch(function(err){
        alert(err.message);
    });
}
function writeResultat(){
    document.getElementById("unite").innerHTML='';
    document.getElementById("valeur-espace").innerHTML='';
    document.getElementById("date-espace").innerHTML='';
    document.getElementById("echantillon-espace").innerHTML='';
    document.getElementById("confirm").innerHTML='';
    var objet=$("#objet").val();
    var formData=new FormData();
    formData.append('id',objet);
    axios.post("{{route('app.getDetailsAnalyseParameters')}}",formData)
    .then(function(res){
        var inner=res.data;
        for(var i in inner){
            document.getElementById("unite").innerHTML=inner[i].unite;
            document.getElementById("valeur-espace").innerHTML='<input class="form-control" type="text" id="valeur-area"/>';
            document.getElementById("date-espace").innerHTML='<input class="form-control" type="date" id="date-area"/>';
            document.getElementById("echantillon-espace").innerHTML='<input class="form-control" type="number" id="echantillon-area"/>';
            document.getElementById("confirm").innerHTML='<button class="btn btn-danger" onclick="saveResultat()">Enregistrer</button>';
        }
        
    })
    .catch(function(err){
        alert(err.message);
    });
}
function saveDescription(){
    var formData=new FormData();
    formData.append('type_description',$('#critere').val());
    formData.append('valeur_description',$('#d-value').val());
    formData.append('type_prod','0');
    formData.append('id_arrivage',$('#arrive_id').html());
    formData.append('type_qualite',$('#arrive_lot').html());

    axios.post("{{route('register.saveResultatDescription')}}",formData)
    .then(function(res){
        var id=$('#arrive_id').html();
        readDescription(id);
    })
    .catch(function(err){
        alert(err.message);
    });
}
function saveResultat(){
    var formData=new FormData();
    formData.append('date_analyse',$('#date-area').val());
    formData.append('type_analyse',$('#type_analyse').val());
    formData.append('details_analyse',$('#objet').val());
    formData.append('valeur_analyse',$('#valeur-area').val());
    formData.append('type_prod',$('#echantillon-area').val());
    formData.append('id_arrivage',$('#arrive_id').html());
    formData.append('type_qualite',$('#arrive_lot').html());

    axios.post("{{route('register.saveResultatAnalyse')}}",formData)
    .then(function(res){
        var id=$('#arrive_id').html();
        readResultats(id);
    })
    .catch(function(err){
        alert(err.message);
    });
}
</script>
@endsection