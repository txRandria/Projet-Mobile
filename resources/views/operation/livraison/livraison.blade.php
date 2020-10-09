@extends('layout.app3')
@section('sub-menu')
    <ul class="nav flex-column" id="lot-list">
    
    </ul>
@endsection
    @section('contents')
   <div class="row">
        <div class="justify-content-center col-md-5" >
        <select class="form-control btn btn-primary mb-3" id="category" onchange="selectAnCateg()"></select>
        </div>
    </div>
    <div class="row">
        <div class="justify-content-center col-md-10" id="contento" >
        <div id="carouselExampleIndicators" class="carousel slide table-primary" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
            <div class="w-70" src="..." alt="initial" id="initial">
                <div style="width: 100%"><canvas id="canvasInit"></canvas></div>
            </div>
            </div>
            <div class="carousel-item">
            <div class="w-70" src="..." alt="actuel" id="actuel">
            <div style="width: 100%"><canvas id="canvasAct"></canvas></div>
            </div>
            </div>
            <div class="carousel-item">
            <div class="w-70" src="..." alt="site" id="site">
            <div style="width: 100%"><canvas id="canvasSite"></canvas></div>
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
        </div>
    </div>
    
@endsection
@section('scripts')
<script type="text/javascript">
    var categories=@json($categories);
    var lots=@json($lots);
    var type=@json($type);
    var produit=@json($produit);
    var qualite=@json($qualite);
    $(document).ready(function(){
        var inner='';
        for(var i in categories){
            inner+='<option id="ct-'+categories[i].id+'">'+categories[i].name+'</option>';
        }
        document.getElementById('category').innerHTML=inner;
        selectAnCateg();
    });
    function selectAnCateg(){
        var categ=$('#category').val();
        var lot=lots[categ];
        var inner='';
        document.getElementById("lot-list").innerHTML=inner;
        for(var i in lot){
            inner='<li class="nav-item btn btn-primary mb-2" id="lot-'+lot[i].id+'" onclick="selectLot(\''+lot[i].code+'\')">'+lot[i].code+'</li>';
            document.getElementById("lot-list").innerHTML+=inner;
        }
    }
    function selectLot(code){
        graphLotInitial(code);
        graphLotActuel(code);
        tableauSite(code);
    }
    function tableauSite(code){
        var formData=new FormData();
        formData.append('code',code);
        axios.post("{{route('report.situationActuelLotBySite')}}",formData)
        .then(function(res){
            var label=res.data.label;
            var type_prod=res.data.type_prod;
            var site=res.data.site;
           // alert(JSON.stringify(res.data));
            var inner='<table class="table table-sm table-bordered table-hover table-striped bg-info"><tr><th></th><th></th>';
            for(var i in site){
                inner+='<th>'+site[i].groupe+'</th>';
            }
            inner+='</tr>';
            for(var i in type){
                inner+='<tr><th></th><th>'+type[i].prod+' '+type[i].qlt+'</th>';
                for(var j in site){
                    inner+='<td>'+type_prod[site[j].id+'-'+i]+'</td>';
                }
                inner+='</tr>';
            }
            inner+='</table>';
            document.getElementById("site").innerHTML=inner;
        })
        .catch(function(err){
            alert(err.message);
        }); 
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
</script>
@endsection