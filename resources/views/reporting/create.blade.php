@extends('layout.app3')
@section('contents')
<div class="container">
<div class="row " id="body-contents">
    <div class="col-md-8 justify-content-center">
        
        <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">Designation </span>
                </div>
                <input type="text" class="form-control" id="name">
        </div>

        <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">Titre </span>
                </div>
                <input type="text" class="form-control" id="title">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary" id="basic-addon1">Type de graphe :</span>
            </div>
            <select class="form-control" id="chart" onchange="changeType()">
            	<option>line</option>
            	<option>horizontalBar</option>
            	<option>bar</option>
            	<option>pie</option>
            	<option>doughnut</option>
            </select>
        </div>


        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary" id="basic-addon1">CATEGORIE</span>
            </div>
            <select class="form-control" id="categList">

            </select>
        </div>

 		<div class="row">       
        <div class="col-md-10">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary" id="basic-addon1">Axe Y</span>
            </div>
            <select class="form-control" id="listanalyse">

            </select>
        </div>
    	</div>
    	<div class="col-md-2">
    		<button class="btn btn-primary btn-block" onclick="addConfig()">Ajouter</button>
    	</div>
    	</div>
        <div>
        	<table id="place1" class="table-sm table-bordered">
        		
        	</table>
        </div>

    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
var listAn=[];
var analyse=@json($analyse);
var categ=@json($category);
var count=0
function loading(){
	
	var inner=''
	for(var i in analyse){
		inner+='<option id="'+analyse[i].id+'">'+analyse[i].name+'</option>';
	}
	document.getElementById("listanalyse").innerHTML=inner;
	var inner=''
	for(var i in categ){
		inner+='<option id="'+categ[i].id+'">'+categ[i].name+'</option>';
	}
	document.getElementById("categList").innerHTML=inner;
}

function changeType(){
	var type=$("#chart").val();
	//alert(JSON.stringify(listAn))
	if(count>1 && (type=='pie' || type=='doughnut')){
		count=1;
		var inner='';

		for(var i in listAn){
			inner+='<tr><td>'+listAn[i].name+'</td><td>'+listAn[i].code+'</td></tr>';
			break;
	}
		var tmp=listAn[0];
		listAn=[];
		listAn.push(tmp);
		count=0;
		document.getElementById('place1').innerHTML=inner;
		alert("erreur: les types pie ou doughnut ne peuvent pas integrer plus de parametres");
	}else{

	}
}
function findInArray(data,array){
	if(array.indexOf(data)>-1){
		return true;
	}
	return false;
}
function addConfig(){
	var src=document.getElementById("listanalyse");
	var options=src.options;
	var id=options[options.selectedIndex].id;
	
	var type=$("#chart").val()
	if(!findInArray(analyse[id-1],listAn)){
		if(type=='pie' || type=='doughnut'){
			if(count<1){
				var inner='<tr><td>'+analyse[id-1].name+'</td><td>'+analyse[id-1].code+'</td></tr>';		
				document.getElementById('place1').innerHTML+=inner;
				count++;
				listAn.push(analyse[id-1]);
			}else{
				var inner='les types pie ou doughnut ne peuvent pas integrer plus de parametres';
				alert(inner)
			}
		
		}else{
			var inner='<tr><td>'+analyse[id-1].name+'</td><td>'+analyse[id-1].code+'</td></tr>';		
			document.getElementById('place1').innerHTML+=inner;
			count++;
			listAn.push(analyse[id-1]);
		}
	}else{
		alert("!!!!");
	}
}
$(document).ready(function(){
	loading();
});
</script>
@endsection