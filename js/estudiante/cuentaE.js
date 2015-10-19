var infAct=false
function validarUsuario(){
	
	
	var usu=document.getElementById("logAnt").value
	var pw=document.getElementById("pwAnt").value
	
	var recibe=document.getElementById("recibevalidarUsu")
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/cuentaEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
				
	            if(conexion.responseText=="si"){
					recibe.innerHTML="<div class='notificaciones'>Información actual correcta</div>"
					infAct=true
				}
				else{
					recibe.innerHTML="<div class='error'>Información actual incorrecta</div>"
					infAct=false
				}
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("logAntE="+usu+"&pwAntE="+pw)
}

function validarTodo(){
	if(infAct==false){
		alert("La información actual es incorrecta")
	 return false;	
	}
	
	var existeUsu=document.getElementById("existeUsuNuevo").innerHTML
	if(existeUsu!=""){
		alert("El nombre de usuario ya existe.")
		return false;	
	}
	
	var pw1=document.getElementById("pwNuevo").value
	var pw2=document.getElementById("pwNuevo2").value
	
	if(pw1!=pw2){
		alert("Las contraseñas no coinciden")
		return false;
	}
	
	return true;
}

function validarUsuarioNuevo(UsuarioNuevo){
	var recibe=document.getElementById("existeUsuNuevo")
	
	conexion= new XMLHttpRequest();
		if(window.XMLHttpRequest){
			conexion= new XMLHttpRequest();
		}
		else{
			conexion=new ActiveXobject("Microsoft.XMLHTTP")
		}
		conexion.open("POST","../procesos/estudiante/cuentaEP.php",true)
		conexion.onreadystatechange=function(){
			if(conexion.readyState==4 && conexion.status==200){
	            if(conexion.responseText=="si"){
					recibe.innerHTML="El nombre de usuario ya existe."
				}
				else{
					recibe.innerHTML="";
				}
				
			}
		}
		conexion.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		conexion.send("valUsuNuevo="+UsuarioNuevo)
	
}