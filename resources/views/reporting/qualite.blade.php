@extends('layout.app3')
@section('contents')
<div id="contento">
</div>

<div id="modalDialog">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportTitle"></h5>
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
    
    var categ=@json($categ);
    var lots=@json($lots);
    var arrivage=@json($arrivage);
    var qualite=@json($qualite);

    var resultatAll=new Array();
    var descriptAll=new Array();
    var arrivageAll=new Array();

    $(document).ready(function(){
        loading();
    });
    function loading(){
        document.getElementById("contento").innerHTML="";
        //alert('ok')
        for(var x in categ){
            InCategorie(categ[x]);
        }
    }
    function InCategorie(xcateg){
        var inner='<center class="mb-3"><h3>'+xcateg.name+'</h3></center><hr>';
        var lot=lots[xcateg.name];
        for(var i in lot){
            inner+='<center class="mb-3"><h5>'+lot[i].code+'</h5></center>'
            inner+='<table class="table table-xs table-hover table-striped table-primary">';
            inner+='<tr><th>QUALITE</th><th>QUANTITE A LA RECEPTION</th><th>QUANTITE EN STOCK</th></tr>';
            for(var j in qualite){
                var tmp=arrivage[lot[i].code][qualite[j].name];
                var qteItem=0;
                for(var k in tmp){
                    qteItem+=tmp[k].stock;
                }
                inner+='<tr><th>'+qualite[j].name+'</th><td>'+qteItem+'</td><td id="tr-'+qualite[j].id+'-'+lot[i].id+'"></td><td><span class="badge badge-primary btn" onclick="viewMoreResultat(\''+lot[i].id+'\',\''+qualite[j].id+'\')" id="q-'+qualite[j].id+'-'+lot[i].id+'"></span></td><td><span class="badge badge-success btn" onclick="viewMoreDescription(\''+lot[i].id+'\',\''+qualite[j].id+'\')" id="d-'+qualite[j].id+'-'+lot[i].id+'"></span></td></tr>';
                GetStock(lot[i].code,qualite[j].name,lot[i].id,qualite[j].id);
                getSuiviQualite(lot[i].code,qualite[j].name,lot[i].id,qualite[j].id);
            }
            inner+='</table><hr>';
        }
        document.getElementById("contento").innerHTML+=inner;
    }
    function GetStock(lotId,qId,xlotId,xqId){
        var formData=new FormData();
        formData.append('lot',lotId);
        formData.append('qualite',qId);
        axios.post("{{route('report.qteByLotQualite')}}",formData)
        .then(function(res){
            document.getElementById('tr-'+xqId+'-'+xlotId).innerHTML=res.data;
            })
        .catch(function(err){
            alert(err.message);
        });
    }
    function getSuiviQualite(lotId,qId,xlotId,xqId){
        var formData=new FormData();
        formData.append('lot',lotId);
        formData.append('qualite',qId);
        axios.post("{{route('report.allDescriptionByLotQualite')}}",formData)
        .then(function(res){
            var resultat=res.data.resultat;
            var descript=res.data.descript;
            var arrivage=res.data.arrivage;
            var countResultat=0;
            var countDescript=0;

            resultatAll[xlotId+'-'+xqId]=resultat;
            descriptAll[xlotId+'-'+xqId]=descript;
            arrivageAll[xlotId+'-'+xqId]=arrivage;

            for(var i in arrivage){
                if(resultat[arrivage[i].id].length>0){
                    countResultat++;
                }
                if(descript[arrivage[i].id].length>0){
                    countDescript++;
                }
            }

            document.getElementById('q-'+xqId+'-'+xlotId).innerHTML=countResultat+"-Résultat(s)";
            document.getElementById('d-'+xqId+'-'+xlotId).innerHTML=countDescript+"-Description(s)";
            })
        .catch(function(err){
            alert(err.message);
        });
    }
    function viewMoreDescription(xlotId,xqId){
        document.getElementById("parent").innerHTML='';
        var tmpArr=arrivageAll[xlotId+'-'+xqId];
        for(var i in tmpArr){
            var inner='<table class="table table-xs table-info table-striped table-bordered mb-3">';
            inner+='<tr><th>REF DU GROUPE : </th><td>'+tmpArr[i].lot+'</td></tr>';
            inner+='<tr><th>PRODUIT / QUALITE : </th><td>'+tmpArr[i].produit+' / '+tmpArr[i].qualite+'</td></tr>';
            inner+='<tr><th>FOURNISSEUR : </th><td>'+tmpArr[i].fournisseur+'</td></tr>';
            inner+='</table>';
            inner+='<table class="table table-sm table-success table-striped table-bordered">';
            inner+='<tr><th>CRITERE </th><th>VALEUR</th><th>AUTRES OBSERVATIONS</th></tr>';
            var tmpD= descriptAll[xlotId+'-'+xqId][tmpArr[i].id];
            for(var j in tmpD){
                inner+='<tr><td>'+tmpD[j].type_description+'</td><td>'+tmpD[j].valeur_description+'</td><td>'+tmpD[j].type_qualite+'#'+tmpD[j].responsable+'</td></tr>';
            }
            inner+='</table><hr><hr>';
            document.getElementById("parent").innerHTML+=inner;
        }
        $('#myModal').modal('show');
        //document.getElementById("parent").innerHTML=inner;
    }
    function viewMoreResultat(xlotId,xqId){
        document.getElementById("parent").innerHTML='';
        var tmpArr=arrivageAll[xlotId+'-'+xqId];
        for(var i in tmpArr){
            var inner='<table class="table table-xs table-info table-striped table-bordered mb-3">';
            inner+='<tr><th>REF DU GROUPE : </th><td>'+tmpArr[i].lot+'</td></tr>';
            inner+='<tr><th>PRODUIT / QUALITE : </th><td>'+tmpArr[i].produit+' / '+tmpArr[i].qualite+'</td></tr>';
            inner+='<tr><th>FOURNISSEUR : </th><td>'+tmpArr[i].fournisseur+'</td></tr>';
            inner+='</table>';
            inner+='<table class="table table-sm table-danger table-striped table-bordered">';
            inner+='<tr><th>DATE DE L\'ANALYSE</th><th>PRODUCTION STATE</th><th>GROUPE D\'ANALYSE</th><th>ANALYSES</th><th>RESULTATS</th><th></th></tr>';
            var tmpD= resultatAll[xlotId+'-'+xqId][tmpArr[i].id];
            for(var j in tmpD){
                inner+='<tr><td>'+tmpD[j].date_analyse+'</td><td><span class="badge badge-secondary"></span></td><td>'+tmpD[j].type_analyse+'</td><td>'+tmpD[j].details_analyse+'</td><td>'+tmpD[j].valeur_analyse+'</td><td>'+tmpD[j].responsable+'</td></tr>';
            }
            inner+='</table><hr><hr>';
            document.getElementById("parent").innerHTML+=inner;
        }
        $('#myModal').modal('show');
    }
</script>
@endsection