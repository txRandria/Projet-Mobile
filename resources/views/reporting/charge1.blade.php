@extends('layout.app3')
@section('sub-menu')
    <div class="row">
        <div class="col-md-12">
        <select class="form-control btn btn-primary mb-3" id="category" onchange="selectAnCateg()"></select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-8">
            <ul class="list-group mb-3" id="lot-list">
                
            </ul>
        </div>
   </div>
   <hr>
    
@endsection
@section('contents')
<div class="container mb-3" id="achat">
</div>
<center>
<div id="carouselExampleIndicators" class="carousel slide table-primary" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="w-50" src="..." alt="initial" id="initial">
        <div style="width: 100%"><canvas id="canvasInit"></canvas></div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="w-50" src="..." alt="actuel" id="actuel">
      <div style="width: 100%"><canvas id="canvasAct"></canvas></div>
      </div>
    </div>
    
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</center>
<div class="row">
<div class="col-md-6" id="contento2">
</div>
<div class="col-md-6" id="contento1">
</div>
</div>
<div id="analyse" class="container mb-3">

</div>
@endsection
@section('scripts')

<script type="text/javascript">
var categorie=@json($categorie);
var lots=@json($lot);
var produit=@json($produit);
var qualite=@json($qualite);
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
    function graphLotInitial(code){
        var formData=new FormData();
        formData.append('code',code);
        axios.post("{{route('report.situationInitLot')}}",formData)
        .then(function(res){
            var label=res.data.label;
            var type_prod=res.data.type_prod;

            var index=[];
            var data=[];
            var colors=[];
            for(var i in label){
                 var tab=label[i].split('-');
                if(type_prod[label[i]]>0){
                    index.push(produit[tab[0]].name+' '+qualite[tab[1]].name);
                    data.push(type_prod[label[i]]);
                    colors.push(random_rgba());
                }
            }

            var config= {
			type: 'doughnut',
			data: {
				datasets: [{
					data:data,
					backgroundColor: colors,
					label: 'A la livraison'
				}],
				labels: index
			},
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: code+':situation à la livraison'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		    };

            var ctx = document.getElementById("canvasInit").getContext('2d');
	        new Chart(ctx, config);
        })
        .catch(function(err){
            alert(err.message);
        });    
    }
    function graphLotActuel(code){
        var formData=new FormData();
        formData.append('code',code);
        axios.post("{{route('report.situationActuelLot')}}",formData)
        .then(function(res){
            var label=res.data.label;
            var type_prod=res.data.type_prod;

            var index=[];
            var data=[];
            var colors=[];
            for(var i in label){
                var tab=label[i].split('-');
                if(type_prod[label[i]]>0){
                    index.push(produit[tab[0]].name+' '+qualite[tab[1]].name);
                    data.push(type_prod[label[i]]);
                    colors.push(random_rgba());
                }
            }

            var config= {
			type: 'doughnut',
			data: {
				datasets: [{
					data:data,
					backgroundColor: colors,
					label: 'Actuelle'
				}],
				labels: index
			},
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: code+':situation actuelle'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		    };

            var ctx = document.getElementById("canvasAct").getContext('2d');
	        new Chart(ctx, config);
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    function selectAnCateg(){
        var categ=$("#category").val();
        var inner='';
        document.getElementById('lot-list').innerHTML=inner;
        var lotsList=lots[categ];
        for(var i in lotsList){
            inner+='<li class="list-group-item d-flex justify-content-between align-items-center btn" onclick="clickLot(\''+lotsList[i].id+'\',\''+lotsList[i].code+'\',\''+categ+'\')">'+lotsList[i].code+'<span class="badge badge-primary badge-pill">1</span></li>';
        }
         document.getElementById('lot-list').innerHTML=inner;
    }
    function clickLot(lotId,lotCode,categ){
        perteSurProduction(lotId,lotCode,categ);
        prixDesMarchandises(lotId,lotCode,categ);
        var chargeAll=@json($charge);
       
        var tabCharge=[];
        var valCharge=[];
        var charge=chargeAll[lotId];
       
         var inner='';
        document.getElementById('contento1').innerHTML='';
        graphLotInitial(lotCode);
        graphLotActuel(lotCode);
        for(var i in charge){
            if(tabCharge.indexOf(charge[i].chargeAttachable_type)==-1){
                tabCharge.push(charge[i].chargeAttachable_type);
                valCharge[charge[i].chargeAttachable_type]=charge[i].valeur;
            }else{
                valCharge[charge[i].chargeAttachable_type]+=charge[i].valeur;
            }
        }
        inner+='<center class="mb-3"><b>AUTRES CHARGES DE PRODUCTIONS </b></center><table class="table table-sm table-info table-striped table-bordered">';
        var total=0;
        for(var i in tabCharge){
            inner+='<tr><th>'+tabCharge[i]+'</th><td>'+valCharge[tabCharge[i]]+'</td></tr>';
            total+=valCharge[tabCharge[i]];
        }
        inner+='<tr class="bg-warning"><th><b>TOTAL</b></th><td id="totalChr">'+total+'</td></tr>';
        inner+='</table>';
        document.getElementById('contento1').innerHTML=inner;
        inner='<center><button class="btn btn-danger" onclick="calculer()">Résultat des analyses</button></center><div id="response1"></div>';
        document.getElementById('analyse').innerHTML=inner;
    }
    function calculer(){
        var coutUnit=parseFloat($("#coutUnit").html());
        var qteLivr=parseFloat($("#qteLivr").html());//perteTx
        var perteT=parseFloat($("#perteTx").html());
        var totalCh=parseFloat($("#totalChr").html());
        var achatT=parseFloat($("#achatT").html());

        var inner='<br><hr><table class="table table-sm table-bordered"  style="width:40%;text-align:right;margin-left:0" id="response2"><tr><th>Coût unit : </th><th>'+coutUnit+'</th></tr></table><hr><br>';

        document.getElementById('response1').innerHTML=inner;
        inner='<tr><th>Quantite à la livraison</th><th>'+qteLivr+'</th></tr>';
        inner+='<tr><th>Total perte (qte)</th><th>'+perteT+'</th></tr>';
        inner+='<tr><th>Total perte (valeur)</th><th>'+perteT*coutUnit+'</th></tr>';
        inner+='<tr><th>Total charge</th><th>'+totalCh+'</th></tr>';
        inner+='<tr><th>Quantite(livr)-Perte</th><th>'+(qteLivr-perteT)+'</th></tr>';
        inner+='<tr><th>Coût unitaire moyen final</th><th>'+(perteT*coutUnit+totalCh+achatT)/(qteLivr-perteT)+'</th></tr>';
        document.getElementById('response2').innerHTML+=inner;
    }
    function perteSurProduction(lotId,lotCode,categ){
        document.getElementById('contento2').innerHTML='';
        var formData=new FormData();
        formData.append('lotId',lotId);
        axios.post("{{route('report.reportPerteByLot')}}",formData)
        .then(function(res){
            var data=res.data.data;
            var perte=res.data.perte;
            var inner='<center class="mb-3"><b>PERTE EN QUANTITE</b></center><table class="table table-sm table-primary table-striped table-hover table-bordered">';
            var total=0;
            for(var i in perte){
                inner+='<tr><th>'+perte[i].type+'</th>';
                var tmp=data[perte[i].id];
                var sousTotal=0;
                for(var j in tmp){
                    sousTotal+=tmp[j].out;
                }
                inner+='<td>'+sousTotal+'</td>';
                total+=sousTotal;
            }
            inner+='<tr class="bg-warning"><th><b>TOTAL</b></th><td id="perteTx">'+total+'</td></tr>';
            inner+='</table>';
            document.getElementById('contento2').innerHTML=inner;
        })
        .catch(function(err){
            alert(err.message);
        });
    }
    function calculPrixMoyen(elmtPrix,elmtTaxe,elmtQte){
        var inner='<center><table class="table table-sm table-bordered bg-danger table-striped table-hover" style="width:40%;text-align:right;margin-left:0" id="pMcontents" ></table>';
        document.getElementById('pMoyen').innerHTML=inner;
        var totalP=0;
        var totalT=0;
        var totalM=0;
        var totalQ=0;

        for(var i in elmtPrix){
            totalP+=parseFloat(elmtPrix[i])*parseFloat(elmtQte[i]);
            totalT+=parseFloat(elmtTaxe[i])*parseFloat(elmtQte[i]);
            totalM+=(parseFloat(elmtPrix[i])+parseFloat(elmtTaxe[i]))*parseFloat(elmtQte[i]);
            totalQ+=parseFloat(elmtQte[i]);
        }
        var prixM=totalP/totalQ;
        inner='<tr><th>PRIX UNITAIRE MOYEN : </th><th>'+prixM+'</th></tr>';
        inner+='<tr><th>QUANTITE TOTAL A LA LIVRAISON : </th><th id="qteLivr">'+totalQ+'</th></tr>';
        inner+='<tr><th>TOTAL TAXE A RECUP : </th><th>'+totalT+'</th></tr>';
        inner+='<tr><th>COÛT TOTAL DES ACHATS : </th><th id="achatT">'+(totalT+totalP)+'</th></tr>';
        inner+='<tr><th>COÛT MOYEN UNITAIRE : </th><th id="coutUnit">'+(totalT+totalP)/totalQ+'</th></tr></table></center>';
        document.getElementById('pMcontents').innerHTML=inner;
    }
    function prixDesMarchandises(lotId,lotCode,categ){
        document.getElementById('contento2').innerHTML='';
        var formData=new FormData();
        formData.append('lotId',lotId);
        axios.post("{{route('report.reportPrixByLot')}}",formData)
        .then(function(res){
           
            var arr=res.data.arr;
            var data=res.data.data;
            if(data.length>0){
                var elmtPrix=[];
                var elmtTaxe=[];
                var elmtQte=[];
                var inner='<table class="table table-sm table-hover table-striped table-secondary table-bordered">';
                inner+='<tr><th>DATE ACHAT</th><td>DATE RECEPTION</td><td>DESCRIPTIONS</td><td colspan="2">FOURNISSEURS</td><td>QTE A LA LIVRAISON</td><td>PRIX U</td><td>TAXE U</td><td>MONTANT</td></tr>';
                for(var i in data){
                    var arrivage=arr[data[i].achatAttachable_id];
                    inner+='<tr><td>'+data[i].daty+'</td><td>'+arrivage.date_arrive+'</td><td>'+arrivage.lot+'-'+arrivage.produit+'-'+arrivage.qualite+'</td><td>'+data[i].groupeFrs+'</td><td>'+data[i].fournisseur+'</td><td>'+arrivage.stock+'</td><td>'+data[i].prix+'</td><td>'+data[i].taxe+'</td><td>'+arrivage.stock*(data[i].taxe+data[i].prix)+'</td></tr>';
                    elmtPrix.push(data[i].prix);
                    elmtTaxe.push(data[i].taxe);
                    elmtQte.push(arrivage.stock);
                }
                inner+='</table><div id="pMoyen" class="mb-3"></div>';
                document.getElementById('achat').innerHTML=inner;
                calculPrixMoyen(elmtPrix,elmtTaxe,elmtQte);
            }else{
                document.getElementById('achat').innerHTML='<span class="badge badge-danger mb-3">Prix d\'achat non indiqué !!</span>';
            }
            
        })
        .catch(function(err){
            alert(err.message);
        });
    }
</script>
@endsection