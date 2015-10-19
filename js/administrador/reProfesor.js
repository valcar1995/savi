function validarProfesor(val){
	
	    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarProfesor.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				var cam=document.getElementById("existePro")
				if(conexion.responseText=="si"){
					cam.innerHTML="Ya existe un profesor con este documento de identidad"
				}
				else{
				  cam.innerHTML=""	
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("docPro="+val)
}