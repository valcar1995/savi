


function verDescripcion(elem,evt){
	var descripcion=elem.getElementsByClassName("descripcionInforme")[0]
	
	var toltip=document.getElementById("toltip")
	var descrpToltip=document.getElementById("descripcionToltip")
	descrpToltip.innerHTML=descripcion.innerHTML
	
	
	
	toltip.style.display="block"
	toltip.style.left=(evt.clientX-75)+"px"
	toltip.style.top=evt.clientY+30+"px"
	
	toltip.style.display="block"
	
	
}

function quitarDescripcion(elem){
	var toltip=document.getElementById("toltip")
	toltip.style.display="none"
}