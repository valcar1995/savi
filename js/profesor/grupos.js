
function iniciar(){
	document.getElementById("grupo1").click()
}


function cambiarGrupo(n,index){
	
	
	var tod=document.getElementsByClassName("gruposMenu")
	for(i=0; i<tod.length; i++){
		tod[i].id="grupo"+(i+1)
	}
	
	var cam=document.getElementById("grupo"+index)
	cam.id="grupoElegido"
	
	   conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/gruposP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            document.getElementById("recibeGrupos").innerHTML=conexion.responseText;
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("grupo="+n+"&index="+index)
}

function verMateria(n){
	    conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/profesor/gruposP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            self.location="planilla.php"
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("materia="+n)
}