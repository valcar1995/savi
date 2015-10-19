

<?php
//include("../procesos/token.php");
?>
<link rel="stylesheet" type="text/css" href="../css/estudiante/menuE.css"/>
<header>
<div id="cabesa">
<img src="../imagenes/logo.jpg" id="logo" />

<div id="contenPeriodo">
<?php
$anio=$_SESSION['anio'];
$periodo=$_SESSION['periodo'];
echo "<span>$anio - $periodo</span>";
?>



</div>

<a href="../procesos/cerrar.php">
<img src="../imagenes/salir.png" class="iconosMenu" />
</a>
<a href="cuentaE.php">
<img src="../imagenes/iconoEstudiante.png" id="imagenPerfil" class="iconosMenu" />
</a>

<div class="itemsEst"><?php
	   echo $_SESSION['nombreUsurio'];
	 ?></div>
</div>

</header>

<div id="menu">
<ul>
<a href="horarioE.php"><li id="li1">Horario</li></a>
<a href="notasE.php"><li id="li3">Notas</li></a>
<a href="acomuladaE.php"><li id="li4">Acumulada-Areas</li></a>
<a href="planificadorE.php"><li id="li2">Planificador</li></a>
<a href="cuentaE.php"><li id="li5">Mi cuenta</li></a>
</ul>
</div>


