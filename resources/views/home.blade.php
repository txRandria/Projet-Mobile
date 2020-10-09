@extends('layout.app3')
@section('sub-menu')
    <ul class="nav flex-column" id="task-list">
        <li class="nav-link mb-2"><a class="btn btn-outline-danger btn-block" href="#" onclick="listTransfert()"> <span class="badge badge-danger" id="task-transfert"></span>Transfert de stock en site</a></li>
        <li class="nav-link mb-2"><a class="btn btn-outline-success btn-block" href="{{route('report.ListingInventaire')}}">Inventaire </a></li>
        <li class="nav-link mb-2"><a class="btn btn-outline-info btn-block" href="{{route('app.getIGUReportCharge')}}">Relevé des dépenses </a></li>
    </ul>
    <div id="list-box">
    </div>
@endsection
@section('contents')
<div class="container-fluid">
	<div id="contenus-1"></div>
    <div id="contenus-2">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" id="slide">
            <ol class="carousel-indicators" id="indicator">
            </ol>
            <div class="carousel-inner" id="contents-0">
                
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
    </div>
    <div id="contenus-3" class="row">
        <div id="p-list" class="col-md-6" style="width:75%;"></div>
        <div id="q-list" class="col-md-6" style="width:75%;"></div> 
    </div>
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
var lots=@json($lots);
var type=@json($type);
var Vdata=@json($data);
var label=@json($label);
$(document).ready(function(){
       var inner1='';
       var inner2='';
       var x=0;
       for(var i in categorie){
           if(i==0){
               inner1='<li data-target="#carouselExampleIndicators" data-slide-to="'+x+'" class="active"></li>';
               inner2='<div class="carousel-item active"><img class="d-block w-80" src="..." alt="'+categorie[i].name+'"><canvas id="canvas-'+categorie[i].id+'"></canvas></div>';
           }else{
               inner1='<li data-target="#carouselExampleIndicators" data-slide-to="'+x+'"></li>';
               inner2='<div class="carousel-item"><img class="d-block w-80" src="..." alt="'+categorie[i].name+'"><canvas id="canvas-'+categorie[i].id+'"></canvas></div>';
           }
           document.getElementById("indicator").innerHTML+=inner1;
           document.getElementById("contents-0").innerHTML+=inner2;
         x++;  
       }
//
alert
       for(var i in categorie){
           var lot=lots[categorie[i].id];
           var xdatasets=[];
           for(var j in lot){
               var xdata=[];
               var typ=label[categorie[i].id];
               for(var k in typ){
                   xdata.push(Vdata[lot[j].id+'-'+typ[k]]);
               }
               xdatasets.push({
                   label: lot[j].code,
                   backgroundColor: random_rgba(),
                   stack: 'Stack 0',
                   data:xdata
               });
           }
           var barChartData = {
			labels:type[categorie[i].id],
			datasets:xdatasets
            };
            
            var ctx = document.getElementById('canvas-'+categorie[i].id).getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					title: {
						display: true,
						text: 'SITUATION EN STOCK'
					},
					tooltips: {
						mode: 'index',
						intersect: false
					},
					responsive: true,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}
			});
       }
    });
    function EvoPrix(){
        $("#p-list").html('<canvas id="canvas-p"></canvas>');
        var formData=new FormData();
        formData.append('code',code);
        axios.post("{{route('report.evoPrix')}}",formData)
        .then(function(res){
            var listDate=res.data.daty;
            var label=res.data.label;
            var valeur=res.data.valeur;
            var tabData=[];
            for(var i in listDate){
                
            }
            var config = {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'My First dataset',
                        backgroundColor: window.chartColors.red,
                        borderColor: window.chartColors.red,
                        data: [
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor()
                        ],
                        fill: false,
                    }, {
                        label: 'My Second dataset',
                        fill: false,
                        backgroundColor: window.chartColors.blue,
                        borderColor: window.chartColors.blue,
                        data: [
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor()
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Value'
                            }
                        }]
                    }
                }
            };
        })
        .catch(function(err){
            alert(err.message);
        }); 
    }
</script>
@endsection