function iniciar(){
	document.getElementById("btnBuscarMateria").click()
}

function buscarMateria(){
	var cam=document.getElementById("selectMateria").value
	if(cam=="Todas"){
		cargarTodasLasNotas()
	}
	else{
		cargarNotasMateria(cam)
	}
}

function cargarTodasLasNotas(){
	var periodo=document.getElementById("selectPeriodo").value
	var cam=document.getElementById("contenResultados")
	cam.innerHTML="<img src='../imagenes/barra.gif' />"
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/notasEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            cam.innerHTML=conexion.responseText;
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("cargarNotas=0&periodo="+periodo)
}

function cargarNotasMateria(materia){
	var periodo=document.getElementById("selectPeriodo").value
	var cam=document.getElementById("contenResultados")
	cam.innerHTML="<img src='../imagenes/barra.gif' />"
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/notasEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            cam.innerHTML=conexion.responseText;
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("cargarNotasMateria="+materia+"&periodo="+periodo)
}