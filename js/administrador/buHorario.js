var pagin;

function iniciar(){
	pagin=new pagin("10","horario","dia","","si")
	buscarDatos(0,1)
}

function filtrarDatos(){
	var campo=document.getElementById("campoBuscar")
	pagin.filtro=campo.value
	buscarDatos(0,1)
}

function buscarDatos(idInicial,pag){
	var cam=document.getElementById("resultados")
	cam.innerHTML='<img src="../imagenes/barra.gif" width="220" height="19" />'
	pagin.paginaActual=pag
	var cantidad=pagin.cantidad;
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarHorario.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				cam.innerHTML=conexion.responseText
				pagin.crearItems(pag);
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idInicial="+idInicial+"&cantidad="+cantidad+"&tipoFiltro="+pagin.tipoFiltro+"&filtro="+pagin.filtro+"&centro="+pagin.centro)
}

function obtenerFormularioEdicion(id){
	
	var cam=document.getElementById("divFormularioEdicion")
	cam.mostrar();
	var recibe=document.getElementById("resultadoFormulario")
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/procesarHorario.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				recibe.innerHTML=conexion.responseText
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idFormulario="+id)
	
	
}

function eliminarHorario(id){
	
	var siElimina=confirm("¿Realmente desea eliminar el horario?")
	
	if(siElimina==true){
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/administrador/eliminarRegistro.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				
				
				if(conexion.responseText=="si"){
					var hayPag=hayPaginas()
					if(hayPag==true){
					document.getElementById("pagSelected").click()
					}
					else{
					  location.reload();	
					}
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("elimHor="+id)
	}
	
}
