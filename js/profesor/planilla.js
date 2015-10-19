



function cambiarContenido(n,recarga){
	var tod=document.getElementsByClassName("itemsMenuPlanilla")
	for(i=0; i<tod.length; i++){
		tod[i].id="itemPlanilla"+(i+1)
	}
	
	var cam=document.getElementById("itemPlanilla"+n)
	cam.id="itemselPlanilla"
	
	tod=document.getElementsByClassName("recibeConten")
	for(i=0; i<tod.length; i++){
		tod[i].style.display="none"
	}
	
	cam=document.getElementById("recibeConten"+n)
	cam.style.display="block"
	
	 if(n==3){
		cargarPlanillaRecuperacion()
	 }
}

function modificarNota(idN,idM){
  
  var camNota=document.getElementById("campoN"+idN)
  var divImg=document.getElementById("divImg"+idN)
  
  var nota=camNota.value
  nota=nota.replace(",",".")
  if(isNumber(nota)){
	  if(nota<=5){
	  divImg.innerHTML="<img src='../imagenes/carga.gif' class='imgOk' />"
	  conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/planillaP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            if(conexion.responseText=="si"){
					modificarPromedio(idM)
					divImg.innerHTML="<img src='../imagenes/ok.png' class='imgOk' />"
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idNota="+idN+"&valorNota="+nota)
	  }
	  else{
		 alert("Las notas deben ser menores o iguales a cinco (5)") 
	  }
	}
	
}

function modificarPromedio(idM){
	
	var todo=document.getElementsByClassName("camposE"+idM)	
	var tdProm=document.getElementById("idPro"+idM)
	var sum=0;
	var sum2=0;
	
	var totalEvaluado=0;
	for(i=0; i<todo.length; i++){
		totalEvaluado+=parseFloat(todo[i].dataset.porcentaje)
	}
	
	
	for(i=0; i<todo.length; i++){
		var nota=todo[i].value
		nota=nota.replace(",",".")
		todo[i].value=nota
		nota=parseFloat(nota)
		var porcentaje=parseInt(todo[i].dataset.porcentaje)
		sum+=nota*porcentaje/totalEvaluado
		sum2+=nota*porcentaje/100
	}
	
	sum=Math.round(sum*10)/10
	sum2=Math.round(sum2*10)/10
	
	var notaInf=parseInt(tdProm.dataset.notaInferior)
	
	var classProm=(sum<3)?"tdPierdeMat":"tdGanaMat";
	
	tdProm.innerHTML=sum2+"("+sum+")"
	tdProm.className=classProm
	
}


function EliminarConceptoEv(n){
	
	var si=confirm("¿Realmente desea eliminar el concepto de evaluación?")
	
	if(si==true){
	var cam=document.getElementById("IdConceptElim")
	cam.value=n
	var btn=document.getElementById("btnEnviarElimConcept")
	btn.click()
	}
}


function abilitarModificacion(id){

 var c1=document.getElementById("c1"+id)
 var c2=document.getElementById("c2"+id)
 
 c1.removeAttribute("readonly")
 c2.removeAttribute("readonly")
 
 c1.className="camposConceptoTabla2"
 c2.className="camposPorcentTabla2"
 
 var btn1=document.getElementById("bt1"+id)
 var btn2=document.getElementById("bt2"+id)
 var btn3=document.getElementById("bt3"+id)
 
 btn1.style.display="inline-table"
 btn2.style.display="inline-table"
 btn3.style.display="none"
	
}


function cancelarEdicion(id){
	 var c1=document.getElementById("c1"+id)
 var c2=document.getElementById("c2"+id)
 
 c1.setAttribute("readonly",true)
 c2.setAttribute("readonly",true)
 
 c1.className="camposConceptoTabla"
 c2.className="camposPorcentTabla"
 
 var btn1=document.getElementById("bt1"+id)
 var btn2=document.getElementById("bt2"+id)
 var btn3=document.getElementById("bt3"+id)
 
 btn1.style.display="none"
 btn2.style.display="none"
 btn3.style.display="inline-table"
}

function guardarCambios(id){
	var cam=document.getElementById("btnEnvio"+id)
	cam.click()
}

function modificarFaltas(id,valor){
	var divImg=document.getElementById("campoFalta"+id)
	if(isNumber(valor)){
		 divImg.innerHTML="<img src='../imagenes/carga.gif' class='imgOk' />"
		 conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/planillaP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            if(conexion.responseText=="si"){
					divImg.innerHTML="<img src='../imagenes/ok.png' class='imgOk' />"
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idCampF="+id+"&valorCampF="+valor)
	}
}

function cambiarEstadoInd(id,check){
	
	var divImg=document.getElementById("imgCargaInd1"+id)
	divImg.innerHTML="<img src='../imagenes/carga.gif' class='imgOk' />"
	
	var valor=(check==true)?"si":"no"
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/planillaP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            if(conexion.responseText=="si"){
					divImg.innerHTML="<img src='../imagenes/ok.png' class='imgOk' />"
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idEstInd="+id+"&valorEstInd="+valor)
}

function cambiarFinInd(id,check){
	var divImg=document.getElementById("imgCargaInd2"+id)
	divImg.innerHTML="<img src='../imagenes/carga.gif' class='imgOk' />"
	
	var valor=(check==true)?"si":"no"
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/planillaP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            if(conexion.responseText=="si"){
					divImg.innerHTML="<img src='../imagenes/ok.png' class='imgOk' />"
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idFinInd="+id+"&valorFinInd="+valor)
}

function cargarPlanillaRecuperacion(){
	var recibe=document.getElementById("recibeConten3")
	recibe.innerHTML='<img src="../imagenes/barra.gif" />'	
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/planillaP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	           recibe.innerHTML=conexion.responseText
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("planiRec=1")
}


function cambiarNotaRecuperada(id,val){
	
	var divImg=document.getElementById("cargaCampRec"+id)
	
	val=val.replace(",",".")
	
	if(isNumber(val)){
	  divImg.innerHTML="<img src='../imagenes/carga.gif' class='imgOk' />"
	  conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/planillaP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            if(conexion.responseText=="si"){
					divImg.innerHTML="<img src='../imagenes/ok.png' class='imgOk' />"
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("idnRec="+id+"&valornRec="+val)
	}
}


function isNumber(n) { return !isNaN(parseFloat(n)) && isFinite(n); }

