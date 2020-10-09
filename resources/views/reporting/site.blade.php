@extends('layout.app3')
@section('contents')
<div>
	<div class="row " id="general">
		<div class="col-md-7" id="tab">
			
		</div>
		<div class="col-md-5" id="graph">
        	
	   </div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	var config=[];
function loading(){
	var data = @json($data);
	var categ=@json($categorie);
	var produit=@json($produit);
	var qualite=@json($qualite);
	var site=@json($site);
	config=[];
	//alert(JSON.stringify(site));
	document.getElementById('tab').innerHTML='';
	
	for(var x in site){
		var inner='<center><h4>'+site[x].groupe+'</h4></center>';

		document.getElementById('graph').innerHTML+=inner;

		for(var y in categ){
			var labels=[];
			var dataG=[];
			var colors=[];
			inner+='<center><h6>'+categ[y].name+'</h6><table class="table-sm table-primary table-striped table-hover">';
			var prod=produit[categ[y].name];
			var canvasId=categ[y].name+'_'+site[x].id;
			document.getElementById('graph').innerHTML+='<div style="width: 85%"><canvas id="'+canvasId+'"></canvas></div><hr>';
			for(var i in prod){
				for(var j in qualite){
					var xlab=prod[i].name+'-'+qualite[j].name;
					var value=data[site[x].groupe][categ[y].ref][prod[i].name][qualite[j].name];

					inner+='<tr><th>'+xlab+'</th><td>'+value+'</td>';
					inner+='</tr>';

					labels.push(xlab);
					colors.push(random_rgba());
					///////////////////////////////////////
					dataG.push(value);
				}
			}
			inner+='</table></center><center>==================================</center>';
			////////////////////////////////////////////
			config[canvasId] = {
			type: 'doughnut',
			data: {
				datasets: [{
					data:dataG,
					backgroundColor: colors,
					label: 'vv'
				}],
				labels: labels
			},
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: categ[y].name+' en stock ('+site[x].groupe+')'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		};

		}

		inner+='<hr>';
		document.getElementById('tab').innerHTML+=inner;
	}
}
function draw(id){
	var ctx = document.getElementById(id).getContext('2d');
	new Chart(ctx, config[id]);
}
$(document).ready(function(){
	loading();
	for(var i in config){
		draw(i);
	}
});

</script>
@endsection