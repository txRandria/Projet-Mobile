@extends('layout.app3')
@section('sub-menu')
    <div class="row">
        <div class="col-md-3">
        <center><img width="25" src="{{ asset('icon/icons8-ungroup-objects-96.png') }}"/></center>
        </div>
            
        <div class="col-md-8">
            <select class="form-control" id="category" onchange="selectCateg()">
                    
            </select>
        </div>
   </div>
   <hr>
    <ul class="nav flex-column" id="lot-list">
    
    </ul>
@endsection
@section('contents')
<div id="contento">
</div>
<div id="modalDialog">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportTitle">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="parent">
            <div class="row">
            <div class="col-md-5" id="graphic" style="width:100%"><canvas id="chart-area"></canvas></div>
            <div class="col-md-7" id="reportData"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
           <!-- <button type="button" class="btn btn-primary">Save changes</button>-->
          </div>
        </div>
      </div>
    </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
    var categorie=@json($categorie);
    var lots=@json($lot);
    var produit=@json($produit);
    var qualite1=@json($qualite1);
    var qualite=@json($qualite1);
    var local=@json($local);
    cumul=[];
    indexCumul=[];
    indexLastCumul=[];

    $(document).ready(function(){
        loading();
    });
    function loading(){
        var inner='<option>SELECTIONNER ICI POUR COMMENCER</option>';
        document.getElementById('category').innerHTML=inner;
        for(var i in categorie){
            inner='<option>'+categorie[i].name+'</option>';
            document.getElementById('category').innerHTML+=inner;
        }
    }
    function selectCateg(){
        var select=$("#category").val();
        var listLot=lots[select];
        document.getElementById('lot-list').innerHTML='';
        for(var i in listLot){
            var inner='<li class="nav-item btn btn-success btn-block"><a class="nav-link" onclick="openAllArrivage(\''+listLot[i].code+'\')">'+listLot[i].code+' >> </a></li>';
            document.getElementById('lot-list').innerHTML+=inner;
        }
    }
    function openAllArrivage(code){
        document.getElementById('contento').innerHTML='';
        cumul=[];
        indexCumul=[];
        indexLastCumul=[];
        var formData=new FormData();
        formData.append('id',code);
        axios.post("{{route('report.mvtLocalQualiteProduitByLot')}}",formData)
        .then(function(res){
            var data=res.data.data;
            //alert(JSON.stringify(data));
            var qte=res.data.qte;
            var analyse=res.data.analyse;
            for(var z in local){
                    var data_z=data[local[z].name];
                    for(var x in produit){
                        var nbligne=0;
                        var inner='<center><h4><b>Local :: '+local[z].groupe+'-'+local[z].name+'  '+produit[x].categorie+'-'+produit[x].name+'</b></h4></center><table class="table table-sm table-bordered table-striped table-hover table-secondary bg-primary mb-3"><tr><th>Decsriptions des produits</th><th>Disponibles</th><th>Historiques des actions</th><th></th><th>Input</th><th>Output</th><th>En stock</th><th>ACTIONS</th></tr>';
                        var sq=qualite[produit[x].categorie][produit[x].class];
                        var data_x=data_z[produit[x].name];
                        for(var y in sq){
                            var indexQ=produit[x].id+'-'+sq[y].id+'-'+local[z].id;
                            if(qte[indexQ]>0){
                                nbligne++;
                                var mvt=data_x[sq[y].name];
                                inner+='<tr><th rowspan="'+mvt.length+'">'+produit[x].categorie+' '+produit[x].name+' '+sq[y].name+'</th><td rowspan="'+mvt.length+'">'+qte[indexQ]+'</td>';
                                var ii=0;
                                for(var k in mvt){
                                    indexLastCumul[mvt[k].arrId+'-'+indexQ]=mvt[k].id;
                                    if(indexCumul.indexOf(mvt[k].arrId+'-'+indexQ)==-1){
                                        indexCumul.push(mvt[k].arrId+'-'+indexQ);
                                        cumul[mvt[k].arrId+'-'+indexQ]=mvt[k].input-mvt[k].output;
                                    }else{
                                        cumul[mvt[k].arrId+'-'+indexQ]+=mvt[k].input-mvt[k].output;
                                    }
                                    if(ii==0){
                                        inner+='<td><li>Origine : Origine</li><li>Action : '+mvt[k].description_mvt+'</li></td><td>'+produit[mvt[k].produitId].name+'</td><td>'+mvt[k].input+'</td><td>'+mvt[k].output+'</td><td>'+cumul[mvt[k].arrId+'-'+indexQ]+'</td><td id="act-'+mvt[k].id+'"></td></tr>';
                                    }else{
                                        inner+='<tr><td><li>Origine : Origine</li><li>Action : '+mvt[k].description_mvt+'</li></td><td>'+produit[mvt[k].produitId].name+'</td><td>'+mvt[k].input+'</td><td>'+mvt[k].output+'</td><td>'+cumul[mvt[k].arrId+'-'+indexQ]+'</td><td id="act-'+mvt[k].id+'"></td></tr>';
                                    }
                                    getIventaireInfo(mvt[k].id);
                                    ii++;
                                }
                                
                            }
                        }
                    inner+='</table>';
                    if(nbligne>0){
                        document.getElementById('contento').innerHTML+=inner;    
                    }
                    }
            }    
        })
        .catch(function(err){
            alert(err.message);
        });

    }
    function getIventaireInfo(id){
        var formData=new FormData();
        formData.append('mx',id);
        axios.post("{{route('report.headOfInventory')}}",formData)
        .then(function(res){
            var data=res.data;
            //alert(JSON.stringify(data));
            //document.getElementById('place-'+arrId+'-'+localId).innerHTML=data;
            var nb=0;
            var inner='';
            for(var i in data){
                inner+='<li class="btn" onclick="goToAddNew(\''+data[i].id+'-'+data[i].local+'-'+data[i].arrivage+'\')"><a href="#" class="badge badge-success">'+data[i].daty+'</a><a href="#" class="badge badge-info">Quatité : <b>'+data[i].qte+'</b></a><a href="#" class="badge badge-secondary">Nb objects : <b>'+data[i].count+'</b></a></li>';
                nb++;
            }
            if(nb==0){
                inner='<a href="#" class="badge badge-danger btn" onclick="launchAddNew(\''+id+'\')"> + nouveau </a>';
            }else{
                inner+='<a href="#" class="badge badge-danger btn" onclick="launchAddNew(\''+id+'\')"> + nouveau </a>';
            }
            document.getElementById('act-'+id).innerHTML=inner;
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    function DetailsOfInventories(arrId,localId){
        var formData=new FormData();
        formData.append('id',arrId);
        formData.append('local',localId);
        axios.post("{{route('report.getInventoryInfos')}}",formData)
        .then(function(res){
            var data=res.data.inv;
            document.getElementById('inv-'+arrId+'-'+localId).innerHTML=data.length+" inventaire(s)";
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    function launchAddNew(id){
        window.location.href ="{{ route('app.addNoewInv',[':id'])}}".replace(':id', id);
    }
    function goToAddNew(id){
        window.location.href ="{{ route('app.addNoewInv1',[':id'])}}".replace(':id', id);
    }
    function addInventaire(arrId,localId){
        var formData=new FormData();
        formData.append('id',arrId);
        formData.append('local',localId);
        axios.post("{{route('report.getInventoryInfos')}}",formData)
        .then(function(res){
            var data=res.data.inv;
            var packet=res.data.details;
            //document.getElementById('inv-'+arrId+'-'+localId).innerHTML=data.length+" inventaire(s)";
            var inner='<table class="table table-sm table-success table striped table-hover table-bordered"><tr><th>#</th><th>DATE DE L\'INVENTAIRE</th><th>DESCRIPTION DU PRODUIT</th><th>RESPONSABLE</th><th>LOCAL + SITE</th><th>QUANTITE INVENTORE</th><th>OBSERVATIONS</th></tr>'
            for(var i in data){
                inner+='<tr><th>'+data[i].id+'</th><td>'+data[i].daty+'</td><td>'+data[i].daty+'</td><td>'+data[i].resp+'</td><td>'+local[data[i].local].name+"-"+local[data[i].local].name+'</td><td>'+data[i].qte+'</td><td>'+data[i].comment+'</td><td><span class="badge badge-primary btn" onclick="viewAllObjectInventory(\''+arrId+'\',\''+localId+'\',\''+data[i].id+'\')">'+packet[data[i].id].length+' objet(s)</span></td></tr>';     
            }
                inner+='</table>';
                inner+='<hr><div class="row mb-3">';
                inner+='<div class="col-sm-3">Date<input type="date" class="datepicker form-control" id="daty"/></div>';
                inner+='<div class="col-sm-3">Quantite total inventoré<input type="number" class="form-control" id="qte"/></div>';
                inner+='<div class="col-sm-6"><span class="label ">Observations</span><input type="text" class="form-control" id="comment"/></div>';
                inner+='</div>';
                inner+='<div class="row"><div class="col-sm-4"></div><div class="col-sm-4"><a href="#" class="btn btn-danger btn-block" onclick="saveInv(\''+arrId+'\',\''+localId+'\')">Enregistrer</a></div><div class="col-sm-4"></div></div>';
                document.getElementById('parent').innerHTML=inner;
                $('#myModal').modal('show');
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    function viewAllObjectInventory(arrId,localId,id){
        var formData=new FormData();
        formData.append('arrId',arrId);
        formData.append('localId',localId);
        formData.append('inv',id);
        axios.post("{{route('report.viewDetailsInventory')}}",formData)
        .then(function(res){
           // alert(JSON.stringify(res.data));
            var inv=res.data.inventaire;
            var arr=res.data.arrivage;
            var local=res.data.local;
            var details=res.data.details;

            var inner='<table class="table table-sm table-success table-striped table-bordered table-hover">';
            inner+='<tr><th>#</th><th>Categorie</th><th>Description</th><th>Réference</th><th>Poids</th></tr>';
            var total=0;
            var int=0;
            for(var i in details){
                int++;
                total+=details[i].qte;
                inner+='<tr><td>'+details[i].id+'</td><td>'+arr.lot+'</td><td>'+arr.produit+' '+arr.qualite+'</td><td>'+details[i].ref+'</td><td>'+details[i].qte+'</td></tr>';
            } 
            inner+='</table>';
            
            if(total<inv.qte){
                inner+='<span class="badge badge-danger btn" onclick="processDescription(\''+arr.id+'\',\''+inv.id+'\',\''+inv.daty+'\',\''+total+'\',\''+inv.qte+'\')">'+(inv.qte-total) +' nécessitent encore des descriptions</span>';
            }else{
                inner+='<span class="badge badge-success btn">Completed ****</span>';
            }
            inner+='<div class="row"><div class="col-sm-3" id="btn-etiquette"></div><div class="col-sm-3"></div></div>';
            inner+='<div id="descript"></div>';
            inner+='<div id="etik"></div>';
            document.getElementById('parent').innerHTML=inner;
            if(int>0){
                document.getElementById("btn-etiquette").innerHTML='<button class="btn btn-outline-primary mb-3" onclick="viewAllEtiquette(\''+arrId+'\',\''+localId+'\',\''+id+'\')">Etiquette</button>';
            }else{
                document.getElementById("btn-etiquette").innerHTML='';
            }
        })
        .catch(function(err){        
            alert(err.message);
        });
    }
    
    
</script>
@endsection