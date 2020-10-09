@extends('layout.app3')
@section('sub-menu')
    
    <ul class="nav flex-column" id="site-list">
    
    </ul>
@endsection
@section('contents')
<div id="contenus">
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
    var site=@json($site);
    var categorie=@json($categorie);
    var produit=@json($produit);
    var qualite=@json($qualite);
    var local=@json($local);

    function loading(){
        //alert(JSON.stringify(site));
        var inner='';
        for(var i in site){
            inner+='<li class="nav-link"><a class="btn btn-outline-danger btn-block" href="#" id="site_'+site[i].id+'" onclick="ViewSituationStocks(\''+site[i].groupe+'\',\''+site[i].id+'\')">'+site[i].groupe+'</a></li>'
        }
        document.getElementById('site-list').innerHTML=inner;
    }
    function ViewSituationStocks(grp,idGRP){
        var formData=new FormData();
        formData.append('siteID',idGRP);
        formData.append('grpName',grp);
        axios.post("{{route('report.getSituationStockInSite')}}",formData)
        .then(function(res){
            document.getElementById("contenus").innerHTML='';
            //alert(JSON.stringify(res.data));
            var data=res.data.data;
            var qte=res.data.qte;
           
            var inner='<table class="table table-sm table-hover table-striped bg-info"><tr><th>#</th><th>#</th>';
            var tmp=local[idGRP];
            for(var i in tmp){
                inner+='<th>'+tmp[i].name+'</th>';
            }
            inner+='</tr>';
            
            for(var x in data){
                var tab=x.split('-');
                inner+='<tr><td></td><td>'+produit[tab[1]].name+' '+qualite[tab[2]].name+'</td>';
                for(var j in tmp){
                    inner+='<td>'+qte[tab[1]+'-'+tab[2]+'-'+tmp[j].id]+'</td>';
                }
                inner+='</tr>';
            }
            inner+='</table>';
            document.getElementById("contenus").innerHTML=inner;
        })
        .catch(function(err){
            alert(err.message);
        });
    }
$(document).ready(function(){
    loading();
           // alert(JSON.stringify(res.data));
});
    function constructGraphic(xlabels,xdata,xcolor){
        var config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: xdata,
                    backgroundColor: xcolor,
                    label: 'Representation graphique'
                }],
                labels: xlabels
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Serie de données'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        };
        var ctx = document.getElementById('chart-area').getContext('2d');
        window.myDoughnut = new Chart(ctx, config);
    }
    function viewDetatils(categ,prod,qualite,siteId,site){
        var formData=new FormData();
        formData.append('categ',categ);
        formData.append('prod',prod);
        formData.append('qualite',qualite);
        formData.append('siteId',siteId);
        axios.post("{{route('report.viewDetailsByQualite')}}",formData)
        .then(function(res){
           // alert(JSON.stringify(res.data));
            var inner='<table class="table table-sm table -danger table-hover table-striped table-bordered">';
            var arrList=res.data.arr;
            var tabQte=res.data.data;
            var backColor=[];
            var gdata=[];
            var labels=[];
            for(var i in arrList){

                inner+='<tr><td>Référence <span class="badge badge-primary">'+arrList[i].lot+'</span></td><td>Réception du  <span class="badge badge-danger">'+arrList[i].date_arrive+'</span></td><td>'+arrList[i].fournisseur+'</td><td>'+tabQte[arrList[i].id].solde+'</td><td><a href="#" class="btn btn-sm btn-primary" onclick="sendTo(\''+arrList[i].id+'\',\''+siteId+'\',\''+site+'\')" >Envoyer vers ...</a></td></tr>';
                backColor.push(random_rgba());
                gdata.push(tabQte[arrList[i].id].solde);
                labels.push("ref : " +arrList[i].lot +" du "+arrList[i].date_arrive+" de "+ arrList[i].fournisseur);
            }
            //<td>Nbre de mvt <span class="badge badge-success">'+tabQte[arrList[i].id].count+'</span></td>
            inner+='</table>';
            document.getElementById('reportTitle').innerHTML=categ+"-"+prod+"-"+qualite+" à "+site;
            document.getElementById('reportData').innerHTML=inner;
           $('#myModal').modal('show');
           constructGraphic(labels,gdata,backColor);
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    function sendTo(arrId,siteId,site){
        var formData=new FormData();
        formData.append('arrId',arrId);
        formData.append('siteId',siteId);
        formData.append('site',site);
        axios.post("{{route('report.getRepartitionStock')}}",formData)
        .then(function(res){
           //alert(JSON.stringify(res.data));
            var list=res.data.local
            var data=res.data.data;
            var arr=res.data.arr;
            var inner='';
            inner+='<table class="table table-sm table-bordered table-striped table-hover">';
            for(var i in list){
                inner+='<tr><th>'+list[i].name+' - '+list[i].groupe+'</th><td>'+data[list[i].id].solde+'</td><td><a href="#" class="btn btn-block btn-danger" onclick="transfert(\''+arr.lot+' - '+arr.produit+' - '+arr.qualite+' - '+arr.fournisseur+'\',\''+list[i].id+'\',\''+data[list[i].id].solde+'\',\''+list[i].name+' - '+list[i].groupe+'\',\''+list[i].groupe+'\',\''+arr.id+'\',\''+data[list[i].id].ref+'\')">+ Transfert</a></td></tr>';
            }            
            inner+='</table>';
            document.getElementById('parent').innerHTML=inner;
            })
        .catch(function(err){
            alert(err.message);
        });
    }
    function transfert(arr,idlocal,Max,origine,site,arrId,ref){
        var inner='<div class="row mb-3"><div class="col-sm-4">Description : </div><div class="col-sm-8">'+arr+'</div></div>';
        inner+='<div class="row mb-3"><div class="col-sm-4">Localisation : </div><div class="col-sm-8">'+origine+'</div></div>';
        inner+='<div class="row mb-3"><div class="col-sm-4">Quantité : </div><div class="col-sm-8">'+Max+'</div></div>';
        inner+='<hr><div class="row mb-3"><div class="col-sm-6"><input type="checkbox" id="checkSite" onclick="CheckChoice(this)"/> Site de destination : </div><div class="col-sm-6" id="placeSite"></div></div>';
        inner+='<div class="row mb-3"><div class="col-sm-6"> <input type="checkbox" id="checkLocal" onclick="CheckChoice(this)"/> Local destination : </div><div class="col-sm-6" id="placeLocal"></div></div>';
        inner+='<div class="row mb-3"><div class="col-sm-6"> Quantité à deplacer : </div><div class="col-sm-6"><input class="form-control" type="number" id="qte"/></div></div>';
        inner+='<div class="row mb-3"><div class="col-sm-4"></div><div class="col-sm-4"><button class="btn btn-danger btn-block" onclick="save(\''+idlocal+'\',\''+arrId+'\',\''+Max+'\',\''+ref+'\')">Enregistrer</button></div></div>';
        document.getElementById('parent').innerHTML=inner;
        igutransfert1(site);
        igutransfert2(site);
    }
    function save(idlocal,arrId,Max,ref){
        var qte=$("#qte").val();
        var destSite=$("#select_site").val();
        var destLocal=$("#select_local").val();
        var select='';
        if(document.getElementById('checkLocal').checked){
            select='local';
        }
        if(document.getElementById('checkSite').checked){
            select='site';
        }
        qte=parseFloat(qte);
        var valMax=parseFloat(Max);
        if(select!=''){
            if(qte>0 && qte<=valMax){
               var formData=new FormData();
                formData.append('arrId',arrId);
                formData.append('idlocal',idlocal);
                formData.append('qte',qte);
                formData.append('destSite',destSite);
                formData.append('destLocal',destLocal);
                formData.append('select',select);
                formData.append('ref',ref);
                axios.post("{{route('register.saveTransfertStock')}}",formData)
                .then(function(res){
                    alert(JSON.stringify(res.data));
                })
                .catch(function(err){
                    alert(err.message);
                }); 
            }else{
                alert("Quantité invalide !");
            }
        }else{
            alert('Please select ')
        }
        
    }
    function igutransfert1(id){
        var formData=new FormData();
        formData.append('site',id);
        axios.post("{{route('app.IGUSelectLocalDestination')}}",formData)
        .then(function(res){
            document.getElementById('placeLocal').innerHTML=res.data;
            document.getElementById('checkLocal').checked=false;
            document.getElementById("select_local").disabled=true;
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    function igutransfert2(id){
        var formData=new FormData();
        formData.append('site',id);
        axios.post("{{route('app.IGUSelectSiteDestination')}}",formData)
        .then(function(res){
            document.getElementById('placeSite').innerHTML=res.data;
            document.getElementById('checkSite').checked=false;
            document.getElementById("select_site").disabled=true;
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    
    function setDisable(id){
        if(id=="site"){
            document.getElementById('checkSite').checked=false;
            document.getElementById("select_site").disabled=true;
        }else{
            document.getElementById('checkLocal').checked=false;
            document.getElementById("select_local").disabled=true;
        }
    }
    function CheckChoice(src){
        if(src==document.getElementById('checkLocal')){
            if(src.checked == true){
                document.getElementById("select_local").disabled=false;
                setDisable("site");
            }else{
                document.getElementById("select_local").disabled=true;
            }
        }else{
            if(src.checked == true){
                document.getElementById("select_site").disabled=false;
                setDisable("local");
            }else{
                document.getElementById("select_site").disabled=true;
            }
        }
    }
</script>
@endsection