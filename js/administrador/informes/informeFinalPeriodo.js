

function cambiarBotones(n){
	 var btnImprimir=document.getElementById("btnImprimir")
	 var btnGenerarInf=document.getElementById("btnGenerrarInforme")
	 
	 if(n==0){
		 btnImprimir.style.display="none"
		 btnGenerarInf.style.display="block"
	 }
	 
	 if(n==1){
		 btnImprimir.style.display="block"
		 btnGenerarInf.style.display="none"
	 }
}


function ElegirAnio(anio){
	cambiarBotones(0)
	
	var cam=document.getElementById("contenBarraPeriodo")
	var cam2=document.getElementById("contenSelectPeriodo")
	
	cam.style.display="block"
	cam2.style.display="none"
	
	var recibe=document.getElementById("selectPeriodo")
	
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../../procesos/administrador/informes/procesarInformeFinalPeriodo.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            
				
				if(conexion.responseText!=""){
					recibe.innerHTML="<option></option>"
					recibe.innerHTML+=conexion.responseText
					cam.style.display="none"
	                cam2.style.display="block"
					errorDatos("")
				}
				else{
					errorDatos("No hay periodos en ese Año.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("anioEleg="+anio)
	
}


function ElegirPeriodo(periodo){
	cambiarBotones(0)
	var anio=document.getElementById("selectAnio").value
	
	var cam=document.getElementById("contenBarraGrupo")
	var cam2=document.getElementById("contenSelectGrupo")
	
	cam.style.display="block"
	cam2.style.display="none"
	
	var recibe=document.getElementById("selectGrupo")
	
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../../procesos/administrador/informes/procesarInformeFinalPeriodo.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            
				
				if(conexion.responseText!=""){
					recibe.innerHTML="<option></option>"
					recibe.innerHTML+=conexion.responseText
					cam.style.display="none"
	                cam2.style.display="block"
					errorDatos("")
				}
				else{
					errorDatos("No hay datos en para ese año y ese periodo.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("anioPerEleg="+anio+"&periodoEleg="+periodo)
	
}

function ElegirGrupo(grupo){
	cambiarBotones(0)
	var anio=document.getElementById("selectAnio").value
	var periodo=document.getElementById("selectPeriodo").value
	
	var cam=document.getElementById("contenBarraEst")
	var cam2=document.getElementById("contenSelectEst")
	
	cam.style.display="block"
	cam2.style.display="none"
	
	var recibe=document.getElementById("selectEst")
	
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../../procesos/administrador/informes/procesarInformeFinalPeriodo.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            
				
				if(conexion.responseText!=""){
					recibe.innerHTML="<option></option>"
					recibe.innerHTML+=conexion.responseText
					cam.style.display="none"
	                cam2.style.display="block"
					errorDatos("")
				}
				else{
					errorDatos("No hay datos para ese estudiante.")
				}
			}
		}
		
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("anioGruEleg="+anio+"&periodoGruEleg="+periodo+"&gruEleg="+grupo)
}

function generarInformePorPeriodo(){
	var anio=document.getElementById("selectAnio").value
	var periodo=document.getElementById("selectPeriodo").value
	var est=document.getElementById("selectEst").value
	var grupo=document.getElementById("selectGrupo").value
	cambiarBotones(0)
	
	var recibe=document.getElementById("recibeDatos")
	recibe.innerHTML="<img src='../../imagenes/barra.gif'>"
	
	if(anio!="" && periodo!="" && est!="" && grupo!=""){
		
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../../procesos/administrador/informes/procesarInformeFinalPeriodo.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				if(conexion.responseText!=""){
					recibe.innerHTML=conexion.responseText
					cambiarBotones(1)
					errorDatos("")
				}
				else{
					errorDatos("No hay datos para ese estudiante.")
				}
			}
		}
		
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("anioInf="+anio+"&periodoInf="+periodo+"&estInf="+est+"&grupoInf="+grupo)
	}
}

function errorDatos(val){
	var cam=document.getElementById("divError1")
	cam.innerHTML=val;
}

function imprimirInforme(){
	var selecctores=document.getElementById("tablaselectores")
	selecctores.style.display="none"
	window.print() 
	selecctores.style.display="block"
	
}