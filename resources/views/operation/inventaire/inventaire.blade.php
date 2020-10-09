@extends('layout.app3')
@section('sub-menu')
    <ul class="nav flex-column" id="lot-list">
    
    </ul>
@endsection
@section('contents')
<div class="container mb-3" id="co1"></div>
<div class="container mb-3" id="co"></div>
<div class="container mb-3" id="colisage"></div>
@endsection
@section('scripts')
<script type="text/javascript">
    var categorie=[];
    var lots=[];
    var produit=[];
    var qualite1=[];
    var qualite=[];
    var local=[];
function loading(id){
    var items=id.split("-");
   // alert(JSON.stringify(items));
    var formData=new FormData();
    formData.append('arrId',items[2]);
    formData.append('localId',items[1]);
    formData.append('inv',items[0]);
        axios.post("{{route('report.viewDetailsInventory')}}",formData)
        .then(function(res){
            // 
            var inv=res.data.inventaire;
            var arr=res.data.arrivage;
            var arrivage=res.data.arr;
            var details=res.data.details;

            var inner='<table class="table table-sm table-success table-striped table-bordered table-hover">';
            inner+='<tr><th>Categorie</th><th>Groupe</th><th>Description</th><th>Réference</th><th>Poids</th></tr>';
            var total=0;
            var int=0;
            for(var i in details){
                int++;
                total+=details[i].qte;
                inner+='<tr><td>'+categorie[arr.categorieId].name+'</td><td>'+arrivage.lot+'</td><td>'+produit[arr.produitId].name+' '+qualite[arr.qualiteId].name+'</td><td>'+details[i].ref+'</td><td>'+details[i].qte+'</td></tr>';
            } 
            inner+='</table><hr><center>';
            
            if(total<inv.qte){
                inner+='<span class="badge badge-danger btn" onclick="processDescription(\''+arr.id+'\',\''+inv.id+'\',\''+inv.daty+'\',\''+total+'\',\''+inv.qte+'\')">'+(inv.qte-total) +' nécessitent encore des descriptions</span>';
            }else{
                inner+='<span class="badge badge-success btn">Completed ****</span>';
            }
            inner+='</center><div class="row"><div class="col-sm-3" id="btn-etiquette"></div><div class="col-sm-3"></div></div>';
            inner+='<div id="descript"></div>';
            inner+='<div id="etik"></div>';
            document.getElementById('co1').innerHTML=inner;
            document.getElementById('co').innerHTML='';
            if(int>0){
                document.getElementById("btn-etiquette").innerHTML='<button class="btn btn-outline-primary mb-3" onclick="viewAllEtiquette(\''+items[2]+'\',\''+items[1]+'\',\''+items[0]+'\')">Etiquette</button>';
            }else{
                document.getElementById("btn-etiquette").innerHTML='';
            }
        })
        .catch(function(err){        
            alert(err.message);
        });
}
function processDescription(arr,invId,inv,total,invQte){
        var inner=document.getElementById("co").innerHTML;
        document.getElementById("colisage").innerHTML='';
        if(inner.length>0){
            document.getElementById("co").innerHTML='';
        }else{
            inner='<div class="row mb-3"><div class="col-sm-4">Date inventaire : <input class="form-control" type="date" id="date_inv" value="'+inv+'"disabled/></div></div>'
            inner+='<hr><div class="row mb-3">';
            inner+='<div class="col-sm-4">Nombre de colis : <input type="number" id="nb" class="form-control" onblur="validateNbColis(this,\''+arr+'\',\''+invId+'\')"/></div>';
            inner+='<div class="col-sm-3">Restant à identifier : <input class="form-control" type="number" id="qte_inv" value="'+(invQte-total)+'"disabled/></div>';
            //inner+='<div class="col-sm-3"><button class="btn btn-danger btn-block">Enregistrer</button></div>';
            inner+='</div><hr><div id="colisage"></div>';
            document.getElementById("co").innerHTML=inner;
        }
            
    }
    function validateNbColis(src,arrId,invId){
        document.getElementById("colisage").innerHTML='';
        var nb=parseInt(src.value);
        var inner='';
        for(var i=0;i<nb;i++){
            inner+='<div class="row mb-3">';
            inner+='<div class="col-sm-6"><table class="mb-3 table table-sm table-bordered table-hover table-striped">'
            inner+='<tr><th><span>Poids :</span></th><td><input class="form-control" type="number" min="0" id="desc-poids-'+i+'" onblur="getInfosQR(\''+arrId+'\',\''+invId+'\',this,\''+i+'\')"/></td></tr>';
            inner+='<tr><th><span>Observations :</span></th></th><td><input type="text" class="form-control" id="desc-cmt-'+i+'" /></td></tr>';
            inner+='</table></div>';
             inner+='<div class="col-sm-5"><span>Aperçu étiquette</span><div id="qrcode-'+i+'"></div><span id="ref-'+i+'"></span></div>';
            inner+='</div>';
        }
        inner+='<center><button class="btn btn-success" onclick="saveDetailsInv(\''+arrId+'\',\''+invId+'\',\''+nb+'\')">Enregister</button></center>';
        document.getElementById('colisage').innerHTML=inner;
    }
function getInfosQR(arrID,invID,src,index){
        var qte=parseFloat(src.value);
        if(qte>0){
            var formData=new FormData();
            formData.append('arrId',arrID);
            formData.append('invId',invID);
            formData.append('pd',qte);
            axios.post("{{route('report.scriptInventaireCarton')}}",formData)
            .then(function(res){
                //alert("ok");
                document.getElementById("qrcode-"+index).innerHTML="";
               // alert(res.data);
                makeTheQr("qrcode-"+index,res.data);
            })
            .catch(function(err){        
                alert(err.message);
            });
        }
    }
    function viewAllEtiquette(arrId,localId,id){
        var formData=new FormData();
        formData.append('arrId',arrId);
        formData.append('localId',localId);
        formData.append('inv',id);
        axios.post("{{route('report.viewCompletedetiquette')}}",formData)
        .then(function(res){
             var inner='<div class="row mb-4"><div class="col-sm-4"></div><div class="col-sm-4"><center><button class="btn btn-outline-danger" onclick="printEtiquette()">Imprimer</button></center></div><div class="col-sm-4"></div></div><div id="printable"></div>';
            document.getElementById("etik").innerHTML=inner;
            var data=res.data;
            for(var i in data){
                var x=data[i].split("|");
                //var tmp=data[i];
                var tmp=x[2].split("§");
                //alert(JSON.stringify(tmp))
                inner='<hr><div class="mb-3"><center><h5><b>SAHANALA MADAGASCAR SA - N°:'+tmp[1]+'</b></h5></center>';
                inner+='<center><div id="etikety-'+i+'"></div></center>';
                inner+='<center><h6>Prod n° : '+tmp[2].trim()+'</h6><h6> '+x[1].trim()+'</h6></center></div><hr>';
                document.getElementById("printable").innerHTML+=inner;
            }
            for(var i in data){
             makeTheQr('etikety-'+i,data[i]);
            }
        })
        .catch(function(err){        
            alert(err.message);
        });
    }
    function printEtiquette(){
        var divToPrint=document.getElementById('printable');
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},10);
    }
    function saveDetailsInv(arrId,invId,nb){
        var total=0;
        for(var i=0;i<nb;i++){
            var qte=parseFloat($("#desc-poids-"+i).val());
            total+=qte;
        }
        var xx=parseFloat($("#qte_inv").val());
        alert(xx);
        if(total<=xx){
            for(var i=0;i<nb;i++){
            var qte=parseFloat($("#desc-poids-"+i).val());
            if(qte>0){
                var formData=new FormData();
                formData.append('arrId',arrId);
                formData.append('invId',invId);
                formData.append('qte',qte);
                formData.append('comment',$("#desc-cmt-"+i).val());
                axios.post("{{route('register.saveInventoryDetails')}}",formData)
                .then(function(res){
                
                })
                .catch(function(err){        
                    alert(err.message);
                });
            }
        }
        }else{
            alert("Erreur sur la quantité");
        }
        
    }
    function loadingData(id){
        var items=id.split("-");
    var formData=new FormData();
    formData.append('mx',items[2]);
    axios.post("{{route('report.descriptionDetailsInv')}}",formData)
    .then(function(res){
        categorie=res.data.categorie;
        lots=res.data.lot;
        produit=res.data.produit;
        qualite1=res.data.qualite1;
        qualite=res.data.qualite;
        local=res.data.local;
       // alert(JSON.stringify(lots))
        loading(id);
    }).catch(function(err){
    alert(err.message);
    });
}
$(document).ready(function(){
   // alert("ok")
    var id=@json($inv);
    loadingData(id);
});
</script>
@endsection