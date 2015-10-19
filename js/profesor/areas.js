function cambiarMenuArea(n){
	
	var tod=document.getElementsByClassName("areasMenu")
	
	for(i=0; i<tod.length;i++){
		tod[i].id="areaMenu"+(i+1)
	}
	
	var cam=document.getElementById("areaMenu"+n)
	cam.id="menuAreaElegido";
	
	tod=document.getElementsByClassName("contenAreas")
	
	for(i=0; i<tod.length; i++){
		tod[i].style.display="none"
	}
	
	cam=document.getElementById("contenArea"+n)
	cam.style.display="block"
}

function ElegirGrupo(val){
	
	if(val!="nada"){
	var cam=document.getElementById("contenBarraMateria")
	var cam2=document.getElementById("contenSelectMateria")
	cam.style.display="block"
	cam2.style.display="none"
	
	var recibe=document.getElementById("selectMateria")
	
	   conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/areasP.php",true)
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
					errorDatos("No hay materias en ese grupo.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idGrupo="+val)
	
	}
	
}

function ElegirMateria(materia){
	
	
	var cam=document.getElementById("contenBarraAnio")
	var cam2=document.getElementById("contenSelectAnio")
	cam.style.display="block"
	cam2.style.display="none"
	
	var recibe=document.getElementById("selectAnio")
	
	var grupo=document.getElementById("selectGrupo").value
	
	if(materia!=""){
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/areasP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				if(conexion.responseText!=""){
					recibe.innerHTML=""
					recibe.innerHTML=conexion.responseText
					cam.style.display="none"
	                cam2.style.display="block"
					errorDatos("")
				}
				else{
					errorDatos("No hay notas registradas para esa materia y ese grupo.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idGrupoM="+grupo+"&idMateria="+materia)
	}
	
}

function errorDatos(val){
	var cam=document.getElementById("divError1")
	cam.innerHTML=val;
}

function errorDatos2(val){
	var cam=document.getElementById("divError2")
	cam.innerHTML=val;
}
function errorDatos3(val){
	var cam=document.getElementById("divError3")
	cam.innerHTML=val;
}


function generarInformePlanillaPeriodo(){
	
	var grupo=document.getElementById("selectGrupo").value
	var materia=document.getElementById("selectMateria").value
	var anio=document.getElementById("selectAnio").value
	var periodo=document.getElementById("selectPeriodo").value
	
	var recibe=document.getElementById("recibeInformePlanillaPeriodo")
	recibe.innerHTML="<img src='../imagenes/barra.gif'>"
	
	if(grupo!="" && materia!="" && anio!="" && periodo!=""){
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/areasP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				
				if(conexion.responseText!=""){
					recibe.innerHTML=conexion.responseText
					errorDatos("")
				}
				else{
					errorDatos("Hubo un error al generar el informe.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idGrupoInf="+grupo+"&idMateriaInf="+materia+"&anioInf="+anio+"&periodoInf="+periodo)	
		
	}
	
}



//informe area estudiante

function cambiarGrupoInf(grupo){
	
	if(grupo!=""){
		var cam=document.getElementById("imgCargaAreas")
		cam.style.display="block"
		var recibe=document.getElementById("selecAreaInf")
		recibe.style.display="none"
		
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/areasP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				
				if(conexion.responseText!=""){
					cam.style.display="none"
					recibe.style.display="block"
					recibe.innerHTML="<option></option>"
					recibe.innerHTML+=conexion.responseText
					errorDatos2("")
				}
				else{
					errorDatos("Hubo un error al generar el informe.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idGrupoAreaInf="+grupo)	
		
	}
	
}

function cambiarAreaInf(areainf){
	var grupo=document.getElementById("selecGrupoAreaInf").value
	
	if(grupo!="" && areainf!=""){
		var cam=document.getElementById("imgCargaAnioArea")
		cam.style.display="block"
		var recibe=document.getElementById("selectAnioAreaInf")
		recibe.style.display="none"
		
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/areasP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				if(conexion.responseText!=""){
					cam.style.display="none"
					recibe.style.display="block"
					recibe.innerHTML=""
					recibe.innerHTML+=conexion.responseText
					errorDatos2("")
				}
				else{
					errorDatos("Hubo un error al generar el informe.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idGrupoAnioInf="+grupo+"&idaAreaAnioInf="+areainf)	
	}
}

function generarInformeAreasEstudiantes(){
	var area=document.getElementById("selecAreaInf").value
	var grupo=document.getElementById("selecGrupoAreaInf").value
	var anio=document.getElementById("selectAnioAreaInf").value
	var periodo=document.getElementById("selectPeriodoAreaInf").value
	
	var recibe=document.getElementById("recibeInformeAreaEstudiante")
	if(area!=""){
		recibe.innerHTML="<img src='../imagenes/barra.gif'>"
		
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		
		conexion.open("POST","../procesos/profesor/areasP.php",true)
		
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				
				if(conexion.responseText!=""){
					recibe.innerHTML=conexion.responseText
					errorDatos2("")
				}
				else{
					errorDatos2("Hubo un error al generar el informe.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idAreaInf="+area+"&grupoAreaInf="+grupo+"&anioAreaInf="+anio+"&periodoAreaInf="+periodo)	
	}
}

function BuscarEstIngresoTardio(){
	
	var grupo=document.getElementById("selectGrupoEstTard").value
	var recibe=document.getElementById("recibeEstudiantesTardios")
	
	
	if(grupo.value!=""){
	recibe.innerHTML="<img src='../imagenes/barra.gif' />"
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		
		conexion.open("POST","../procesos/profesor/areasP.php",true)
		
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				
				if(conexion.responseText!=""){
					recibe.innerHTML=conexion.responseText
					errorDatos3("")
				}
				else{
					errorDatos3("No hay estudiantes de ingreso tardío en el grupo seleccionado.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idGrupoTard="+grupo)	
		
	}
}

function BuscarNotasEstTardio(est,mat){
	
	var recibe=document.getElementById("td"+mat+est)
	recibe.innerHTML='<img src="../imagenes/barra.gif" width="220" height="19" />'
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		
		conexion.open("POST","../procesos/profesor/areasP.php",true)
		
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				
				if(conexion.responseText!=""){
					recibe.innerHTML=conexion.responseText
					errorDatos3("")
				}
				else{
					errorDatos3("No hay estudiantes de ingreso tardío en el grupo seleccionado.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("estTar="+est+"&matTar="+mat)	
}

function confirmarRegistroNotaTardia(){
	var confir=confirm("El sitema guardará la nota definitiva del estudiante para dicha materia, una vez realizada la acción no será posible modificar dicha nota. ¿Desea continuar?")
	return confir
}