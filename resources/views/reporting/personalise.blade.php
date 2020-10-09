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
<div class="container">
	<div id="details">

	</div>
	<div id="corps" class="row">
		<div class="col-md-5">
			<div class="input-group mb-3">
		            <div class="input-group-prepend">
		                <span class="input-group-text bg-primary" id="basic-addon1">Type de graphe :</span>
		            </div>
		            <select class="form-control" id="analyse" onchange="changeType()">
		            	
		            </select>
		    </div>
		    <div id="detailsChamp">
    		</div>
	    </div>
  		<div class="col-md-7" id="graphe">
  		</div>

    </div>
    <div id="data">
        </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
var article=[];
var categorie=@json($category);
var lot=@json($lot);
var selectedLot=null;

function readCateg(ct){
    var inner='';
    for(var i in lot){
        if(ct==lot[i].categorie){
            inner+='<li class="nav-item mb-1"><a class="nav-link btn-primary" href="#" onclick="lectureLot(\''+lot[i].code+'\')">'+lot[i].code+'</a></li>';    
        }
        
    }
    document.getElementById('lot-list').innerHTML=inner;
}

function selectCateg(){
    var ct=$("#category").val();
    readCateg(ct);
}
function loading(){
	
	var analyseType=@json($type);
	var inner='<option>Sélectionner...........</option>'
	for(var i in analyseType){
		inner+='<option id="'+analyseType[i].code+'">'+analyseType[i].name+'</option>';
	}
	$("#analyse").html(inner);
	inner='<option>Sélectionner ici pour commencer </option>'
	for(var i in categorie){
		inner+='<option>'+categorie[i].name+'</option>'
	}
	$("#category").html(inner);
}
function changeType(){
	article=[];
	remplirChampDetails($("#analyse").val());
}
function isCheckeddd(){
     var dataR=[];
	if(selectedLot!=null){
		for(var i in article){
            var obj=document.getElementById("check_"+article[i].id);
            if(obj.checked){
                //alert()
                getData(article[i]);
            }else{

            }
		}
	}else{
		alert("veuillez sélectionner une réference");
	}
}
    function drawGraph(idCanvas,graphName,barChartData){
        var ctx = document.getElementById(idCanvas).getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    title: {
                        display: true,
                        text: graphName
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: false,
                        }],
                        yAxes: [{
                            stacked: false
                        }]
                    }
                }
            });
    }
function getData(art){
    var formData=new FormData();
    formData.append('lot',selectedLot);
     axios.post("{{route('report.getResultatData1')}}",formData)
    .then(function(res){
      // alert(JSON.stringify(res.data.line));
       var title=res.data.type;
       var tabData=res.data.AnalyseData;
       var labelGraph=[];
       var datasetG=[];
      
       var tabArr=res.data.ArrivageData;
       var tab=res.data.line;
       var inner='<table class="table table-sm table-rounded table-striped table-danger"><tr><th></th>';
       for(var i in tab){
            inner+='<th>'+tab[i]+'</th>';
            labelGraph.push(tab[i]);
       }
       inner+='</tr>';
       for(var i in tabArr){
            inner+='<tr><th>'+tabArr[i].date_arrive+'/'+tabArr[i].lot+'/'+tabArr[i].produit+tabArr[i].qualite+'</th>';
            var mesureG=[];
                for(var k in tab){
                    var val=0;
                    if(tabData[tabArr[i].id][art.name+tab[k]]){
                        val=tabData[tabArr[i].id][art.name+tab[k]];
                    }
                    inner+='<td>'+val+'</td>';
                    mesureG.push(val);
                }
            inner+='</tr>';

            var obj=new Object();
            obj.label=tabArr[i].produit+tabArr[i].qualite;
            obj.backgroundColor= random_rgba();
            obj.data=mesureG;            
            datasetG.push(obj);
       }
       var barChartData={
                    labels: labelGraph,
                    datasets: datasetG
                };
      
       document.getElementById("data").innerHTML=inner+'</table>';
       drawGraph('canvas_'+art.id,'nnnnnnnnnnn',barChartData);
    })
    .catch(function(err){
        alert(err.message);
    });
}
function remplirChampDetails(analys){
		var inner='';
		var formData=new FormData();
		formData.append('analyse',analys);
         axios.post("{{route('app.getListDetailsAnalyseByAnalyse')}}",formData)
        .then(function(res){
            var data=res.data;
            article=data;
            for(var i in data){
            	inner+='<div class="input-group mb-3"><div class="input-group-prepend"><div class="input-group-text"><input type="checkbox" aria-label="Checkbox for following text input" id="check_'+data[i].id+'" onclick="isCheckeddd()"></div></div><input type="text" class="form-control" aria-label="Text input with checkbox" value="'+data[i].name+'"></div>';	
            }
            
            document.getElementById("detailsChamp").innerHTML=inner;
            inner='';
            for(var i in data){
                inner+='<div style="width: 85%"><canvas id="canvas_'+data[i].id+'"></canvas></div>';
            }
            document.getElementById("graphe").innerHTML=inner;
        })
        .catch(function(err){
            alert(err.message);
        });
}


function lectureLot(id){
	selectedLot=null;
    var formData=new FormData();
    formData.append('id',id);
    axios.post("{{route('app.detailsLot')}}",formData)
    .then(function(res){
        var data=res.data;
        selectedLot=id;
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
         document.getElementById("details").innerHTML=inner;
    })
    .catch(function(err){
        alert(err.message);
    });
}
$(document).ready(function(){
	loading();
});
</script>
@endsection