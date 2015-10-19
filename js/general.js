function pagin(cantidad,datos,tipoFiltro,filtro,centro){
	this.cantidad=cantidad;
	this.datos=datos;
	this.tipoFiltro=tipoFiltro;
	this.filtro=filtro;
	this.centro=centro;
	this.paginaActual=1;
	
	var cantidad2=cantidad;
	
	this.setCantidad=function(c){
		this.cantidad=c;
		cantidad2=c;
	}
	
	this.crearItems=function(pag){
		conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/general.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				var conten=document.getElementById("pagin")
	            conten.innerHTML=conexion.responseText;
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("datos="+this.datos+"&tipoFiltro="+this.tipoFiltro+"&filtro="+this.filtro+"&centro="+this.centro+"&limitePag="+this.cantidad+"&pagina="+pag)
	}
}



window.onload=function(){
	var conten=document.getElementById("todo2")
	
	var fondo=document.createElement("div")
	fondo.id="fondo"
	fondo.style.width=window.innerWidth+"px"
	fondo.style.height=window.innerHeight+"px"
	fondo.style.position="fixed"
	
	
	var divFormularioEdicion=document.createElement("div")
	divFormularioEdicion.id="divFormularioEdicion"
	
	var contenFormularioEdicion=document.createElement("div")
	contenFormularioEdicion.id="contenFormularioEdicion"
	
	divFormularioEdicion.mostrar=function(){
		var f=document.getElementById("fondo")
		var c=document.getElementById("divFormularioEdicion")
		f.style.display="block"
		c.style.display="block"
	}
	
	divFormularioEdicion.cerrar=function(){
		var f=document.getElementById("fondo")
		var c=document.getElementById("divFormularioEdicion")
		f.style.display="none"
		c.style.display="none"
	}
	
	
	fondo.addEventListener("click",function(){
			var c=document.getElementById("divFormularioEdicion")
			c.cerrar()
	})
	
	
	
	var cerrar=document.createElement("button")
	cerrar.className="btnsCerrar"
	cerrar.innerHTML="X"
	
	cerrar.addEventListener("click",function(){
			var c=document.getElementById("divFormularioEdicion")
			c.cerrar()
	})
	
	var resultadoFormulario=document.createElement("div")
	
	resultadoFormulario.id="resultadoFormulario"
	
	
	contenFormularioEdicion.appendChild(cerrar)
	
	contenFormularioEdicion.appendChild(resultadoFormulario)
	
	conten.appendChild(fondo)
	divFormularioEdicion.appendChild(contenFormularioEdicion)
	conten.appendChild(divFormularioEdicion)
	
	
}

function hayPaginas(){
	var hay=false;
	var tod=document.getElementsByClassName("btnsPag")
	if(tod.length>0){
		hay=true
	}
	
	return hay;
}



