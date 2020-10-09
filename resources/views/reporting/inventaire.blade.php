@extends('layout.app3')
@section('sub-menu')

@endsection
@section('contents')
<div id="contenus"></div>
@endsection
@section('scripts')
<script type="text/javascript">
    var categ=@json($categorie);
    var site=@json($site);
    var inventaire=@json($inventaire);
    var salle=@json($salle);

    var transColis=[];
    var invCarton=[];
    var newTrans=[];

    $(document).ready(function(){
        loading();
    });
    function loading(){
        for(var i in site){
            var inner='<h5><b>'+i+'</b></h5>';
            inner+='<table class="table table-sm table-hover table-striped table-primary"><tr>';
            var tab=site[i];
            for(var j in tab){
                inner+='<th>'+i+'-'+tab[j].name+'</th>';
            }
            inner+='</tr><tr>';
            for(var j in tab){
                var elmt=inventaire[tab[j].id]
                inner+='<td onclick="viewAll(\''+tab[j].id+'\',\''+i+'\')"><span class="badge badge-danger">'+elmt.length+'</span> Inventaire(s)</td>';
            }
            inner+='</tr></table><div id="div-'+i+'"></div><div id="view-'+i+'"></div><br><hr><hr>';
            document.getElementById('contenus').innerHTML+=inner;
        }
    }
    function viewAll(idLocal,place){
        transColis=[];
        invCarton=[];
        newTrans=[];

        document.getElementById("view-"+place).innerHTML='';
        var inner='<table class="table table-sm table-warning tablestriped table-bordered"><tr><th>Date de l\'inventaire</th><th>Description des produits</th><th>Localisation</th><th>Quantité inventoré</th></tr>';
        var elmt=inventaire[idLocal];
        for(var i in elmt){
            inner+='<tr><td>'+elmt[i].daty+'</td><td id="arr-'+elmt[i].id+'">'+elmt[i].arrivage+'</td><td><b>'+salle[elmt[i].local].groupe+'</b>('+salle[elmt[i].local].name+')</td><td><span class="badge badge-success">'+elmt[i].qte+'</span> sur <span class="badge badge-success" id="solde-'+elmt[i].id+'"></span></td><td onclick="viewObjet(\''+elmt[i].arrivage+'\',\''+elmt[i].id+'\',\''+elmt[i].local+'\',\''+place+'\')"><span class="badge badge-info btn" id="ref-'+elmt[i].id+'"></span> objet(s)</td></tr>';
        }
        inner+='</table>';
        document.getElementById("div-"+place).innerHTML=inner;
        for(var i in elmt){
            infosArrivage(elmt[i].local,elmt[i].arrivage,elmt[i].id);
            viewDetailsInventory(elmt[i].arrivage,elmt[i].id,elmt[i].local,elmt[i].id);
        }
    }
    function infosArrivage(local,idArr,place){
        var formData=new FormData();
        formData.append('arrId',idArr);
        formData.append('local',local);
        axios.post("{{route('report.getArrivageInfos')}}",formData)
        .then(function(res){
            var arr=res.data.arr;
            var solde=res.data.soldes.solde;
            document.getElementById("arr-"+place).innerHTML=arr.lot+' ('+arr.produit+'-'+arr.qualite+')';
            document.getElementById("solde-"+place).innerHTML=solde;
            })
        .catch(function(err){
            alert(err.message);
        });
    }
    function viewDetailsInventory(arrId,inv,localId,place){
        var formData=new FormData();
        formData.append('arrId',arrId);
        formData.append('localId',localId);
        formData.append('inv',inv);
        axios.post("{{route('report.viewDetailsInventory')}}",formData)
        .then(function(res){
            var details=res.data.details;
            document.getElementById("ref-"+place).innerHTML=res.data.count;
            })
        .catch(function(err){
            alert(err.message);
        });
    }
    function viewObjet(arrId,inv,localId,place){
        var formData=new FormData();
        formData.append('arrId',arrId);
        formData.append('localId',localId);
        formData.append('inv',inv);
        axios.post("{{route('report.viewDetailsInventory')}}",formData)
        .then(function(res){
            var details=res.data.details;
            var inv=res.data.inventaire;
            var situation=res.data.local;
            var arr=res.data.arrivage;
            invCarton=details;
            //<center><span class="badge badge-success btn" onclick="">Etiquette</span></center>
            var inner='<table class="table table-sm table-danger table-striped table-hover"><tr><th>Réference</th><th>date inventaire</th><th>Quantité</th><th></th></tr>';
            for(var i in details){
                inner+='<tr><th>'+details[i].ref+'</th><th>'+inv.daty+'</th><th>'+details[i].qte+'</th><th><center><input type="checkbox" id="check-'+details[i].id+'" onclick="getCheck()"/></center></th></tr>';
            }
            inner+='</table><div class="row" id="command-panel" style="display:none"><div class="col-md-3">Quantité total : <input class="form-control" id="totals" type="number"/></div>';
            inner+='<div class="col-md-3">Site de destination <select class="form-control btn btn-primary" id="select-site"></select></div>';
            inner+='<div class="col-md-3"></div>';
            inner+='<div class="col-md-3"><button class="btn btn-danger" onclick="saveTransfert(\''+inv.id+'\',\''+arr.id+'\')">Transférer</button></div></div>';
            document.getElementById("view-"+place).innerHTML=inner;
            //alert(JSON.stringify(site));
            for(var i in site){
                if(i!=situation.groupe){
                    inner='<option>'+i+'</option>';
                    document.getElementById("select-site").innerHTML+=inner;
                }
            }
            })
        .catch(function(err){
            alert(err.message);
        });
    }
    function getCheck(){
        var count=0;
        var total=0;
        for(var i in invCarton){
            var src=document.getElementById("check-"+invCarton[i].id);
            if(src.checked){
                count++;
                total+=invCarton[i].qte;
            }
        }
        
        document.getElementById("totals").value=total;
        
        if(count>0){
            document.getElementById("command-panel").style.display="block";
        }else{
            document.getElementById("command-panel").style.display="none";
        }
    }

    function saveTransfert(data,arrId){
        newTrans=[];
        var tabElse=[];
        for(var i in invCarton){
            var src=document.getElementById("check-"+invCarton[i].id);
            if(src.checked){
                newTrans.push(invCarton[i].id);
            }else{
                tabElse.push(invCarton[i].id);
            }
        }
    var poids=0;
        if(newTrans.length>0){
            var transScript='';
            var elseScript='';
            //alert("ok1="+JSON.stringify(newTrans))
            for(var i in newTrans){
                transScript+=newTrans[i]+'|';
                poids+=invCarton[newTrans[i]].qte;
            }
            //alert("ok2="+newTrans.length)
            for(var i in tabElse){
                elseScript+=tabElse[i]+'|';
            }
            //alert("ok")

            
            var formData=new FormData();
            formData.append('arrId',arrId);
            formData.append('inv',data);
            formData.append('transId',transScript);
            formData.append('elseScript',elseScript);
            formData.append('qteTotal',poids);
            formData.append('destSite',$("#select-site").val());
             axios.post("{{route('register.convertInvToTrans')}}",formData)
            .then(function(res){
                alert(JSON.stringify(res.data));
            })
            .catch(function(err){
                alert(err.message);
            });
        }
    }
</script>
@endsection