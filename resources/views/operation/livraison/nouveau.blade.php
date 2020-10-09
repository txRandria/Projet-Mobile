@extends('layout.app2')
@section('contents')
<div class="container-fluid">
<div class="row " id="body-contents">
    <div class="col-sm-5 justify-content-center">
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary" id="basic-addon1">Categorie : </span>
            </div>
            <select class="form-control bg-info" id="categorie" onchange="selectCateg()">

            </select>
        </div>

        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text bg-primary" id="basic-addon1">N* Reference : </span>
            </div>
            <select class="form-control bg-info" id="lot" onchange="readLot()">

            </select>
        </div>

            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">PRODUIT </span>
                </div>
                <select class="form-control bg-info" id="produit" onchange="selectProduit()">
                </select>
            </div>

            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">QUALITE DU PRODUIT </span>
                </div>
                <select class="form-control bg-info" id="qualite">
                </select>
            </div>

            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">FOURNISSEUR </span>
                </div>
                <select class="form-control bg-info" id="frs">
                </select>
            </div>
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">DATE : </span>
                </div>
                <input type="date" class="form-control bg-info" id="date_arrive">
            </div>

            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">Observations : </span>
                </div>
                <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="comment">
            </div>

            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">Quantite : </span>
                </div>
                <input type="number" class="form-control" id="quantite">
            </div>
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">Site : </span>
                </div>
                <select class="form-control bg-info" id="groupeLocal" onchange="readLocal()">
                </select>
            </div>
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary" id="inputGroup-sizing-default">Local : </span>
                </div>
                <select class="form-control bg-info" id="local">
                </select>
            </div>

            <div class="mb-3">
                <center><button class="btn btn-danger" onclick="addInListArrivage()"> + Ajouter</button></center>
            </div>

    </div>
    <div class="col-md-7 justify-content-center">
        <table class="table table-sm table-success table-striped table-hover table-bordered" >
            <thead class="bg-success">
            <tr>
            <th>DATE LIV</th><th>PRODUITS</th><th>QUALITE</th><th>FOURNISSEURS</th><th>QUANTITES</th>
            </tr>
            </thead>
            <tbody id="list-arrivage">
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    var arrayListArrivage=[];
    var index=[];
    var tx_categorie=@json($categ);
    var tx_produit=@json($produit);
    var tx_lot=@json($lots);
    var tx_qualite=@json($qualite);
    var tx_frs=@json($frs);
    var tx_site=@json($grpLocal);

    function addInListArrivage(){
        var temp=[];
        temp['lot']=$('#lot').val();
        temp['produit']=$('#produit').val();
        temp['frs']=$('#frs').val();
        temp['date']=$('#date_arrive').val();
        temp['obs']=$('#comment').val();
        temp['qualite']=$('#qualite').val();
        temp['quantite']=$('#quantite').val();
        temp['site']=$('#groupeLocal').val();
        temp['local']=$('#local').val();
        temp['state']=0;
        if(temp['date'].length>0 && parseFloat(temp['quantite'])>0 && temp['local'].length>0){
            var x=temp['lot']+temp['frs']+temp['date']+temp['qualite']+temp['produit'];
            var xx=findIndex(x);
            if(xx==-1){
                arrayListArrivage.push(temp);
                index.push(x); 
            }else{
                arrayListArrivage[xx]=temp;
            }           
        }
        afficheList();
    }
    function findIndex(x){
        return index.indexOf(x);
    }
    function readLocal(){
        document.getElementById('local').innerHTML='';
        var formData=new FormData();
        formData.append('groupe',$("#groupeLocal").val());
       // alert($("#groupeLocal").val())
        axios.post("{{route('app.getListLocal')}}",formData)
        .then(function(res){
            var data=res.data;
            var inner='';
           // alert(JSON.stringify(data))
            for(var i in data){
                inner+='<option>'+data[i].name+'</option>';
            }
            document.getElementById('local').innerHTML=inner;
        })
        .catch(function(err){
                alert(err.message);
        });
    }
    function afficheList(){
        var inner='';
            for(var i=0; i<arrayListArrivage.length;i++){
                var temp=arrayListArrivage[i];
                inner+='<tr><td>'+temp['date']+'</td><td>'+temp['produit']+'</td><td>'+temp['qualite']+'</td><td>'+temp['frs']+'</td><td>'+temp['quantite']+'</td></tr>'
            }
        document.getElementById('list-arrivage').innerHTML=inner;
    }

    function selectProduit(){
        document.getElementById("qualite").innerHTML='';
        var src=document.getElementById("produit");
        var option=src.options;
        
	    var idProd = option[option.selectedIndex].id;
        var tmp=idProd.split('$$');

        var tmp_list_qualite=tx_qualite[tmp[1]];
        var qualite=tmp_list_qualite[tmp[2]];
        //alert(tmp[1])
        for(var i in qualite){
            document.getElementById("qualite").innerHTML+='<option id="qlt_'+qualite[i].id+'">'+qualite[i].name+'</option>';
        }

    }
    function readLot(){
        arrayListArrivage=[];
        index=[];
        document.getElementById("qualite").innerHTML='';
        var formData=new FormData();
        formData.append('id',$('#lot').val());
        axios.post("{{route('app.detailsLot')}}",formData)
        .then(function(res){
            var data=res.data;
            //alert(JSON.stringify(data));
            var inner='';
            for(var i in data){
                var tmp_data=data[i].data;
                inner+='<tr><td>'+tmp_data.date_arrive+'</td><td>'+tmp_data.produit+'</td><td>'+tmp_data.qualite+'</td><td>'+tmp_data.fournisseur+'</td><td>'+tmp_data.stock+'</td></tr>';
                
                var x=tmp_data.lot+tmp_data.fournisseur+tmp_data.date_arrive+tmp_data.qualite+tmp_data.produit;
                var temp=[];
                temp['lot']=tmp_data.lot;
                temp['produit']=tmp_data.produit;
                temp['frs']=tmp_data.fournisseur;
                temp['date']=tmp_data.date_arrive;
                temp['obs']=tmp_data.observation;
                temp['qualite']=tmp_data.qualite;
                temp['quantite']=tmp_data.stock;
                temp['state']=1;
                var xx=findIndex(x);
                if(xx==-1){
                    arrayListArrivage.push(temp);
                    index.push(x); 
                }else{
                    arrayListArrivage[xx]=temp;
                }
            }
            document.getElementById('list-arrivage').innerHTML=inner;
        })
        .catch(function(err){
                alert(err.message);
        });
    }
    function selectCateg(){
        var categ=$("#categorie").val();
        document.getElementById("lot").innerHTML='';
        document.getElementById("produit").innerHTML='';
        document.getElementById("qualite").innerHTML='';
        var lots=tx_lot[categ];
        var produits=tx_produit[categ];
        for(var i in lots){
            document.getElementById("lot").innerHTML+='<option>'+lots[i].code+'</option>';
        }
        readLot();
        for(var i in produits){
            document.getElementById("produit").innerHTML+='<option id="'+produits[i].id+'$$'+produits[i].categorie+'$$'+produits[i].class+'">'+produits[i].name+'</option>';
        }
        selectProduit();
    }
    function loading(){
        for(var i in tx_categorie){
            document.getElementById('categorie').innerHTML+='<option>'+tx_categorie[i].name+'</option>';
        }
        selectCateg();

            var count=0;
            var inner='';
            for(var i in tx_frs){
                count++;
                inner+='<option id="'+tx_frs[i].id+'">'+tx_frs[i].name+'</option>'
            }
            if(count>0){
                $('#frs').html(inner);
            }else{
                var x='<center><h3> Erreur : Pas de fournisseur dans les bases de donnees</h3></center>'
                $("#body-contents").html(x);
            }

            count=0;
            inner='';
            for(var i in tx_site){
                count++;
                inner+='<option id="'+tx_site[i].id+'">'+tx_site[i].groupe+'</option>'
            }
            if(count>0){
                $('#groupeLocal').html(inner);
                readLocal();
            }else{
                var x='<center><h3> Erreur : Pas d\'infos sur les locaux de stockage dans les bases de donnees</h3></center>'
                $("#body-contents").html(x);
            }        
    }        
        
        /*var count=0;
        var list=@json($lots);
        var inner=''
        for(var i in list){
            count++;
            inner+='<option id="'+list[i].id+'">'+list[i].code+'</option>'
        }
        if(count>0){
            $('#lot').html(inner);
            var numero=$('#lot').val(); 
            readLot();
           
            
            inner='';
            count=0;
            list=@json($qualite);
            for(var i in list){
                count++;
                inner+='<option id="'+list[i].id+'">'+list[i].name+'</option>'
            }
            if(count>0){
                $('#qualite').html(inner);
            }else{
                var x='<center><h3> Erreur : Ne peut pas definir une classe(qualite) des produits</h3></center>'
                $("#body-contents").html(x);
            }

            inner='';
            count=0;
            list=@json($frs);
            for(var i in list){
                count++;
                inner+='<option id="'+list[i].id+'">'+list[i].name+'</option>'
            }
            if(count>0){
                $('#frs').html(inner);
            }else{
                var x='<center><h3> Erreur : Pas de fournisseur dans les bases de donnees</h3></center>'
                $("#body-contents").html(x);
            }

            inner='';
            count=0;
            list=@json($grpLocal);
            for(var i in list){
                count++;
                inner+='<option id="'+list[i].id+'">'+list[i].groupe+'</option>'
            }
            if(count>0){
                $('#groupeLocal').html(inner);
                readLocal();
            }else{
                var x='<center><h3> Erreur : Pas d\'infos sur les locaux de stockage dans les bases de donnees</h3></center>'
                $("#body-contents").html(x);
            }
           /// readLot();
            
        }        
        else
        {
            inner='<center><h3>Erreur : Pas de numerotation de produit </h3></center><p><a href="#">Creer une numerotation/reference</a></p>';
            $("#body-contents").html(inner);
        }*/
    
    
    function save(){
        var load = index.length;
        for(var i in arrayListArrivage){
            var temp=arrayListArrivage[i];
            if(temp['state']==0){
                var formData=new FormData();
                formData.append('lot',temp['lot']);
                formData.append('date_arrive',temp['date']);
                formData.append('produit',temp['produit']);
                formData.append('qualite',temp['qualite']);
                formData.append('fournisseur',temp['frs']);
                formData.append('stock',temp['quantite']);
                formData.append('observation',temp['obs']);
                formData.append('site',temp['site']);
                formData.append('local',temp['local']);
            
                sendSave(formData);
            }
            load--;
            if(load==0){
                alert("loading");
            }
        }
        
    }
    function sendSave(formData){
        axios.post("{{route('register.nouveauArrivage')}}",formData)
        .then(function(res){
            //alert(JSON.stringify(res.data))
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