
var dias=[{dia:"Lunes",value:1},{dia:"Martes",value:2},{dia:"Miércoles",value:3},{dia:"Jueves",value:4},{dia:"Viernes",value:5},{dia:"Sábado",value:6},{dia:"Domingo",value:7}]
var meses=[{mes:"Enero",value:1},{mes:"Febrero",value:2},{mes:"Marzo",value:3},{mes:"Abril",value:4},{mes:"Mayo",value:5},{mes:"Junio",value:6},{mes:"Julio",value:7},{mes:"Agosto",value:8},{mes:"Septiembre",value:9},{mes:"Octubre",value:10},{mes:"Noviembre",value:11},{mes:"Diciembre",value:12}]
var tareas=[]
var mes
var anio
var cuadro=1
var animarDesde;
var manejadorF
var vercuadro=false


var manejadorFechas=function manejadorFechas(dia,mes,anio){
	this.dia=dia
	this.mesInicial=mes
	this.anioInicial=anio
	this.mes=mes
	this.anio=anio
	this.dias=dias
	this.meses=meses
	
	this.setMes=function(mes){
		this.mes=mes
	}
	this.setAnio=function(an){
		this.anio=an
		this.mes=(an==this.anioInicial)?this.mesInicial:1
	}
	
	this.getDia=function(){
		return this.dia
	}
	this.getMes=function(){
		return this.mes
	}
	this.getAnio=function(){
		return this.anio
	}
	
	this.mesSiguiente=function(){
		this.mes=(this.mes>=12)?1:this.mes+1
		return this.meses[this.mes-1]
	}
	
	this.mesAnterior=function(){
		this.mes=(this.mes<=1)?12:this.mes-1
		return this.meses[this.mes-1]
	}
	
	this.setMesByTextValue=function setMesByTextValue(textValue){
		var mesEncontrado=false
		var contmes=0
		while(mesEncontrado==false && iniciomes<12){
			if(this.meses[contmes].mes==textValue){
				mesEncontrado=true
				this.mes=this.meses[contmes].value
			}
			contmes++
		}
	}
	
	this.actualizarValoresHTML=function(){
		$("#valorMes").html(this.meses[this.mes-1].mes)
		$("#valorAnio").html(this.anio)
	}
	
	this.cargarListasMesesHTML=function(){
		var an=this.anio
		var contenMeses=document.getElementById("contenMeses")
		contenMeses.innerHTML="";
		for(i=0; i<this.meses.length; i++){
			
				contenMeses.innerHTML+="<div class='lis' onclick='seleccionarMes("+this.meses[i].value+")'>"+this.meses[i].mes+"</div>"
			
		}
	}
	
	
}


$(document).ready(function(e) {
    
	
	var fecha= new Date()
	dia=fecha.getDay()
	
	mes=fecha.getMonth()+1;
	anio=anioActual;
	manejadorF=new manejadorFechas(dia,mes,anio)
	manejadorF.actualizarValoresHTML()
	manejadorF.cargarListasMesesHTML()
	cargarTareas()
	
	
	
});

function imprimirDias(){
  var diastesxt="";
  for (i=0; i<dias.length; i++){
	  diastesxt+="<td>"+dias[i].dia+"</td>";
  }
  return diastesxt
}

function cargarTareas(){
	document.getElementById("contenResultadosTareas").style.display="none"	
	    var conexion;
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/planificadorEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				
				procesarTareas(conexion.responseText)
				
			}
		}
		var mesE=manejadorF.getMes()
		mesE=(mesE<10)?"0"+mesE:mesE
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("mesPlan="+mesE+"&anioPlan="+manejadorF.getAnio())
}

function procesarTareas(textoR){
	tareas=[]
	var reservasT=textoR.split('<|>')
	for(i=0; i<reservasT.length; i++){
		parteR=reservasT[i].split('-->')
		if(parteR!= undefined && parteR!=""){
		dia=parteR[0]
		cantidad=parteR[1]
		fecha=parteR[2]
		tipo=parteR[3]
		tareas.push({dia:dia,cantidad:cantidad,fecha:fecha,tipo:tipo})
		}
	}
	
	pintarTareas()
}

function pintarTareas(){
	
	
  var conten=document.getElementById("cuadro"+cuadro)
  
   var antesD=llenarDiasAntes()
   var llenaD=llenarDias()
   var enfecha=llenaD.r
   var restantes=llenarRestantes(llenaD.i)
  
	conten.innerHTML=""
	+"<table class='tablasReservas' cellpadding='0' cellspacing='0' width='100%'>"
	+ "<tr class='trDias'>"
	+ imprimirDias()
	+"</tr>"
	+"<tr>"
	+antesD
	+enfecha
	+restantes
	+"</tr>"
	+"</table>"
	

	$("#cuadro1").animate({opacity:1},'slow','easeInOutExpo')
	
}


function llenarRestantes(inicioF){
	var resultadoT="";
	var fechaFinal=obtenerFechaFinMes()
	var diaF=fechaFinal.split("/")[0]
	
	while(inicioF<8){
		diaF=(parseInt(diaF)<10)?"0"+diaF:diaF
		resultadoT+="<td class='tdDespues'>"+diaF+"</td>"
		var fechaFinal=sumarFecha(1, fechaFinal)
	    var diaF=fechaFinal.split("/")[0]
		inicioF++
	}
	return resultadoT
	
}

function obtenerFechaFinMes(){
	var fechaText="1/"+manejadorF.getMes()+"/"+manejadorF.getAnio()
	var mesF=manejadorF.getMes()
	while(manejadorF.getMes()==mesF){
		fechaText=sumarFecha(1, fechaText)
		mesF=parseInt(fechaText.split("/")[1])
	}
	
	
	return fechaText
	
}

function llenarDias(){
	var resultadoT="";
	var fechaText=manejadorF.getMes()+"/01/"+manejadorF.getAnio()
	var mesF=manejadorF.getMes()
	var fechaM=new Date(fechaText)
	var inicioF=fechaM.getDay();
	var retornaI=inicioF
    fechaText="1/"+manejadorF.getMes()+"/"+manejadorF.getAnio()
	var diaF=fechaText.split("/")[0]
	while(mesF==manejadorF.getMes()){
		diaF=(parseInt(diaF)<10)?"0"+diaF:diaF
		var mesparaTd=manejadorF.getMes()
		mesparaTd=(mesparaTd<10)?"0"+mesparaTd:mesparaTd
		var idTd="td"+manejadorF.getAnio()+""+mesparaTd+""+diaF
		var examinaTd=buscarClaseTd(diaF)
		var claseTd=examinaTd.clase
		var canti=(examinaTd.tiene==true)?"<div onclick='buscarTareasDeDia("+diaF+")'>"+examinaTd.cantidad+"</div>":""
		resultadoT+="<td id='"+idTd+"' class='"+claseTd+"' onclick='selectDiaCalendario(this)'>"+diaF+" "+canti+"</td>"
		
		fechaText=sumarFecha(1, fechaText)
		diaF=fechaText.split("/")[0]
		mesF=parseInt(fechaText.split("/")[1])
		if(inicioF%7==0){
			resultadoT+="</tr><tr>"
			retornaI=1
		}
		inicioF++
		retornaI++
		
		
		
	}
	return {r:resultadoT,i:retornaI-1}
}

function llenarDiasAntes(){
	var resultadoT="";
	var fechaText=manejadorF.getMes()+"/01/"+manejadorF.getAnio()
	var fechaM=new Date(fechaText)
	var inicioF=fechaM.getDay();
	var inicioF=(inicioF==0)?6:inicioF-1
	fechaText="1/"+manejadorF.getMes()+"/"+manejadorF.getAnio()
	
	while(inicioF>0){
		inicioF--
		fechaText=sumarFecha(-1, fechaText)
		var diaF=fechaText.split("/")[0]
		
		diaF=(parseInt(diaF)<10)?"0"+diaF:diaF
		resultadoT="<td class='tdAntes'>"+diaF+"</td>"+resultadoT
	}
	
	return resultadoT
}


function buscarClaseTd(dia){
  
  dia=parseInt(dia)
 
  var esta=false
  var canti=0;
  var tipo="";
  for(i=0; i<tareas.length; i++){
	  
	  
	  
	  if(dia==tareas[i].dia){
		  esta=true
		  canti=tareas[i].cantidad
		  tipo=tareas[i].tipo
		  break;
	  }
  }
  
 
  
  clasetd=(esta==true)?"tdTieneAhora":"tdAhora"
  clasetd+=tipo
	
  
  
  
  var retorno={tiene:esta,clase:clasetd,cantidad:canti}
  
  return retorno
	
}


function verReservas(mes,anio){
	//alert(mes+"-"+anio)	
}


function sumarFecha(days, fecha){
	
	fecha=fecha.split("/")
	fecha=fecha[1]+"/"+fecha[0]+"/"+fecha[2]
	
    fecha=new Date(fecha);
    day=fecha.getDate();
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();
 
     
 
    tiempo=fecha.getTime();
    milisegundos=parseInt(days*24*60*60*1000);
    total=fecha.setTime(tiempo+milisegundos);
    day=fecha.getDate();
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();
 
   return day+"/"+month+"/"+year
}

function seleccionarMes(n){
	
	$("#contenMeses").css("visibility","hidden")
	if(n!=manejadorF.getMes()){
	manejadorF.setMes(n)
	manejadorF.actualizarValoresHTML()
	}
	setTimeout(function(){$("#contenMeses").css("visibility","visible")},10)
	cargarTareas()
	$("#cuadro1").css({opacity:0})
	
}




function navegarReservas(dir){
	
	if(dir==0){
		
		manejadorF.mesAnterior()
	manejadorF.actualizarValoresHTML()
	}
	else{
		
		manejadorF.mesSiguiente()
	manejadorF.actualizarValoresHTML()
	}
	cargarTareas()
	$("#cuadro1").css({opacity:0})
	
}

function selectDiaCalendario(ele){
	
	
	if(vercuadro==false){
	   document.getElementById("contenResultadosTareas").style.display="none"	
	}
	
	var dia=ele.innerHTML.split("<div")[0]
	var mes=manejadorF.getMes()
	mes=(mes<10)?"0"+mes:mes
	var anio=manejadorF.getAnio()
	
	var fecha=anio+"-"+mes+"-"+dia
	fecha=fecha.replace(" ","")
	var cam=document.getElementById("fechaTarea")
	cam.style.backgroundColor="rgba(230,230,230,1)"
	cam.value=fecha
	setTimeout(function(){cam.style.backgroundColor="rgba(250,250,250,1)"},200)
	
	
	$(".tdTieneAhoraTarea, .tdTieneAhoraClase, .tdAhora, .tdTieneAhoraEvaluación").css("box-shadow","0px 0px 0px #999999 inset")
	
	
	ele.style.boxShadow="0px 0px 35px #999999 inset"	
}

function cambiarFechaCampo(val){
	var partes=val.split("-")
	var anio=partes[0]
	var dia=partes[2]
	var mes=partes[1]
	
	var idtd="td"+anio+""+mes+""+dia
	var cam=document.getElementById(idtd)
	cam.click()
}


function cambiarColorTarea(val){
   var cam1=document.getElementById("colorTarea1")
   var cam2=document.getElementById("colorTarea2")
   var cam3=document.getElementById("colorTarea3")
   
   if(val=="Evaluación"){
	   cam1.style.display="block"
	   cam2.style.display="none"
	   cam3.style.display="none"
   }
   if(val=="Tarea"){
	    cam1.style.display="none"
	   cam2.style.display="block"
	   cam3.style.display="none"
   }
   if(val=="Clase"){
	   cam1.style.display="none"
	   cam2.style.display="none"
	   cam3.style.display="block"
   }

}

function ValidarCampos(){
	var fech=document.getElementById("fechaTarea").value
	var retorn=validarFecha(fech)
	if(retorn==false){
	  alert("Formato de fecha incorreco. el formato debe ser YYYY-MM-DD ejemplo 2015-03-15")	
	}
	return retorn;
}

function validarFecha(f){
	var partes=f.split("-")
	if(partes.length!=3){
		return false
	}
	var anio=partes[0]
	var mes=partes[1]
	var dia=partes[2]
	
	if(anio.length!=4){
		return false
	}
	if(mes.length!=2){
		return false
	}
	if(dia.length!=2){
		return false
	}
	return true;
	
}

function buscarTareasDeDia(dia){
	var mes=manejadorF.getMes()
    mes=(mes<10)?"0"+mes:mes
	var anio=manejadorF.getAnio()
	dia=(dia<10)?"0"+dia:dia
	var fecha=anio+"-"+mes+"-"+dia
	
	var posX=(event.clientX-150)+"px"
	var posY=event.clientY+"px"
	
	var mov=document.getElementById("contenResultadosTareas")
	mov.style.display="block"
	
	mov.style.left=posX
	mov.style.top=posY
	vercuadro=true
	setTimeout(function(){vercuadro=false},1000)
	
	var recibe=document.getElementById("resultadosTareas")
	recibe.innerHTML="<img src='../imagenes/barra.gif' />"
	
	var conexion;
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/planificadorEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				recibe.innerHTML=conexion.responseText
			}
		}
		var mesE=manejadorF.getMes()
		mesE=(mesE<10)?"0"+mesE:mesE
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("fechaTareas="+fecha)
	
}

function EliminarTarea(idT){
	
	var confir=confirm("¿Realemente desea eliminar este evento?")
	
	if(confir==true){
	var cam=document.getElementById("tareaNum"+idT)
	
	var conexion;
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/planificadorEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				if(conexion.responseText=="si"){
					cam.style.display="none"
				}
			}
		}
		var mesE=manejadorF.getMes()
		mesE=(mesE<10)?"0"+mesE:mesE
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("ElimTarId="+idT)
		
	}
	
}

