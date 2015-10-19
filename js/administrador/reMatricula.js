

$(document).ready(function(e) {
    
	$("#m11").datepicker()
	
	
});

function validarNumeroMatricula(val){
	
	    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarMatricula.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				var cam=document.getElementById("existeNumMat")
				if(conexion.responseText=="si"){
				var nombreCen=document.getElementById("selectCentro")
					
					var nombreC = nombreCen.options[nombreCen.selectedIndex].text; 
					
					cam.innerHTML="En el centro educativo "+nombreC+" ya existe una matricula con este numero"
				}
				else{
				  cam.innerHTML=""	
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("numMat="+val)
}

function validarDocIdMatricula(val){
		    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarMatricula.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				var cam=document.getElementById("existeDocMat")
				if(conexion.responseText=="si"){
				  cam.innerHTML="Ya existe una matrícula con este documento de identidad"
				}
				else{
				  cam.innerHTML=""	
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("docMat="+val)
}