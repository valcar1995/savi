

function iniciar(){
	document.getElementById("btnBuscarArea").click()
}

function buscarArea(){
	var cam=document.getElementById("selectArea").value
	
	if(cam=="Todas"){
		cargarTodasAreas()
	}
	else{
		cargarArea(cam)
	}
}

function cargarTodasAreas(){
	
	var cam=document.getElementById("contenResultados")
	cam.innerHTML="<img src='../imagenes/barra.gif' />"
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/acomuladaEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            cam.innerHTML=conexion.responseText;
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("cargarNotasAreas=0")
}

function cargarArea(area){
	var cam=document.getElementById("contenResultados")
	cam.innerHTML="<img src='../imagenes/barra.gif' />"
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/acomuladaEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            cam.innerHTML=conexion.responseText;
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("cargarNotasPorArea="+area)
}