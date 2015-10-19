
function validarCentro(val){
	    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarCentros.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				var cam=document.getElementById("existeCen")
				
				if(conexion.responseText=="si"){
					cam.innerHTML="El centro educativo ya existe"
				}
				else{
				  cam.innerHTML=""	
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("cenRe="+val)
}

function enviarImagen(){
	document.getElementById("enviarLogo").click()
}

function enviarFormulario(){
	document.getElementById("envioRegistroCentro").click()
}

function obtenerDirLogoCentro(){
	var cam=document.getElementById("reCenLog")
	cam.value=window.frames['frameLogoCentro'].document.getElementById("imagenLogoCentro").title
}