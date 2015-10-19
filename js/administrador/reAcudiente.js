function validarAcudiente(val){
	
	    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarAcudiente.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				var cam=document.getElementById("existeAcu")
				if(conexion.responseText=="si"){
					cam.innerHTML="Ya existe un acudiente con este documento de identidad"
				}
				else{
				  cam.innerHTML=""	
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("docAcu="+val)
}