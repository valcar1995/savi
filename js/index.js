function iniciar(){
	
	
	$("#fondo, #myBody").css({height:window.innerHeight,width:window.innerWidth})
}

function obtenerRegistro(){
	
	var matricula=document.getElementById("reUsuMat").value
	var docId=document.getElementById("reUsudoc").value
	
	if(matricula!="" && docId!=""){
		var recibe=document.getElementById("recibeFormRegistro")
		
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","procesos/administrador/procesarUsuarios.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				if(conexion.responseText!=""){
					recibe.innerHTML=conexion.responseText
				}
				else{
					errorDatos("Hubo un error al generar el informe.")
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("NumMatri="+matricula+"&DocIdMatri="+docId)	
	}
	else{
	  alert("Por favor complete los campos.")	
	}
	
	return false
}

function cerrarFormRegistro(){
	$("#fondo, #contenFormRegistro, #confirmRegistroUsu").css("display","none")
}

function validarRegistroUsuario(){
	var cam1=document.getElementById("validaExisteUsu").innerHTML
	var cam2=document.getElementById("reUsucontra").value
	var cam3=document.getElementById("reUsucontra2").value
	
	if(cam1!="" || cam2!=cam3){
		alert("Formulario incorrecto")
		return false;
	}
	
	return true;
}

function validarSiExisteUsu(val){
	var recibe=document.getElementById("validaExisteUsu")
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","procesos/administrador/procesarUsuarios.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				if(conexion.responseText!="no"){
					recibe.innerHTML="El nombre de usuario ya existe"
				}
				else{
					recibe.innerHTML=""
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("existeUsuIndex="+val)	
}

function mostrarRegistro(){
	$("#fondo, #contenFormRegistro").css("display","inline-table")
}

function verErrorRegistro(){
	$("#fondo, #confirmRegistroUsu").css("display","inline-table")
}