
var vectorDias=["Lunes","Martes","Miércoles","Jueves","Viernes","Sábado","Domingo"];

function iniciar(){
	var recibe=document.getElementById("contenHorasVerticales")
	
	recibe.innerHTML="";
	for(i=5; i<=22; i++){
		var tm=(i>=12)?" pm":" am"
		var hor=(i!=12)?(i%12):12
		
		hor=(hor<10)?"0"+hor:hor
		
		var tex=hor+tm
		
		recibe.innerHTML+="<div class='horasVerticales'>"+tex+"<div class='lineasHorasVerticales'></div></div>"
		recibe.innerHTML+="<div class='horasVerticales mediasVerticales'><div class='lineasHorasVerticales2'></div></div>"
	}
	controlarLineaDeTiempo()
	setInterval("controlarLineaDeTiempo()",60000);
	
	fecha =new Date();
	dia=fecha.getDay();
	
	var hora=fecha.getHours();
	
	dia=(dia==0)?7:dia
	
	dia=(hora>21)?((dia+1)%7):dia
	
	document.getElementById("columnadia"+dia).style.backgroundColor="rgba(0,102,255,0.3)"
	document.getElementById("columnadiaC"+dia).style.backgroundColor="rgba(255,153,0,1)"
	
}

function cambiarDia(n){
	var tod=document.getElementsByClassName("diasMenu")
	
	for(i=0; i<tod.length; i++){
		tod[i].id="dia"+(i+1)
	}
	
	var cam=document.getElementById("dia"+n)
	cam.id="diaElegido"
	
	cargarHorario(n)
	
}

function cargarHorario(){
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/horarioEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            procesarHorarioP(conexion.responseText);
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("diaHor="+vectorDias[n-1])
}

function procesarHorarioP(tex){
	
	var conten=document.getElementById("recibeTablasHorario")
	conten.innerHTML=""
	var horarios=tex.split("*-*")
	for(i=0; i<horarios.length; i++){
		var partes=horarios[i+1].split("-*-")
		var h1=partes[0]
		var h2=partes[1]
		var materia=partes[2]
		var grupo=partes[3]
		agragarHorario(h1,h2,materia,grupo)
	}
}

function agragarHorario(h1,h2,mate,grad){
	
	var diferenciaHoras=rangoHoras(h1,h2)
	var horaInicial=convertirHora(h1)
	
	var pos1=obtenerPosHora(horaInicial)
	var h=obtenerPosHora((horaInicial+diferenciaHoras))
	h=(h-pos1)+"px"
	pos1+="px"
	var conten=document.getElementById("recibeTablasHorario")
	
	conten.innerHTML+="<div class='ranfost' style='margin-top:"+pos1+"; height:"+h+";  max-height:"+h+" '><div class='nombreGrado'>"+grad+"</div><div class='nombreMateria'>"+mate+"</div></div>"
	
	
	
}


function rangoHoras(h1,h2){
 

 var hora1=convertirHora(h1)
 var hora2=convertirHora(h2)
 var hora=hora2-hora1;
 return hora
 
}

function convertirHora(hor){
	var hsp=hor.split(":")
	var hora=parseInt(hsp[0])
	var minutos=parseInt(hsp[1])
	return (minutos!=0)?hora+(minutos/60):hora
}


function obtenerPosHora(h1){
	return ((h1-5)*40)+10
}


 
 function controlarLineaDeTiempo(){
	 var d=new Date()
	 var hor=parseInt(d.getHours());
	 var minu=d.getMinutes()
	 minu=(minu/60)
	 hor=(hor+minu)-5
	 var tam= (hor*40)
	 var cam=document.getElementById("lineaTiempo")
	 cam.style.height=tam+"px"
 }