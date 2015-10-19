
function CambiarInfoCabesa(){
	
	   var centro=document.getElementById("selectCentro").value
	   var anio=parseInt(document.getElementById("campoAnioActual").value)
	   var periodo=parseInt(document.getElementById("campoPeriodoActual").value)
	   
	   if(validarNumero(anio) && validarNumero(periodo)){
	   
	    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarCentroElegido.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				location.reload();
				//alert(newURL)
				//location.reload();
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("regCen="+centro+"&anio="+anio+"&periodo="+periodo)
	   }
	   else{
		  alert("Datos erróneos")   
	   }
}

function obtenerAnioPeriodoCentro(centro){
	var btn=document.getElementById("btnGuardarCambioCentro")
	var backColorbtn=btn.style.backgroundColor
	btn.style.backgroundColor="rgba(210,210,210,1)"
	btn.disabled=true;
	
	    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarCentroElegido.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				if(conexion.responseText!=""){
					actualizaqrCamposAnioPeriodo(conexion.responseText)
					btn.style.backgroundColor=backColorbtn
					btn.disabled=false;
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idCentCambio="+centro)
	
	
}
function actualizaqrCamposAnioPeriodo(anioPeriodo){
	anioPeriodo=anioPeriodo.split("|")
	var anio=anioPeriodo[0]
	var periodo=anioPeriodo[1]
	
	var camAnio=document.getElementById("campoAnioActual")
	var camPeriodo=document.getElementById("campoPeriodoActual")
	
	camAnio.value=anio
	camPeriodo.value=periodo
}

function mostrarEdicion(){
  var c1=document.getElementById("tablaNormal")
  var c2=document.getElementById("tablaEditable")
  
  c1.style.display="none"
  c2.style.display="block"	
}


function validarNumero(numero){
	valido=false;
       if (parseInt(numero) % 1 == 0) {
            valido=true;
        }
   
   return valido;
}