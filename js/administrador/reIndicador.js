function validarIndicador(val){
	
	    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarIndicador.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				var cam=document.getElementById("existeInd")
				if(conexion.responseText=="si"){
					var nombreCen=document.getElementById("selectCentro")
					
					var nombreC = nombreCen.options[nombreCen.selectedIndex].text; 
					
					cam.innerHTML="En el centro educativo "+nombreC+" ya existe un indicador con ese nombre"
				}
				else{
				  cam.innerHTML=""	
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("nomInd="+val)
}