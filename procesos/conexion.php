<?php

@$host="localhost";
@$user="root";
@$contrasena="";
@$db="sefu2";


session_start();
$con=mysql_connect($host,$user,$contrasena,$db);
mysql_select_db($db,$con);





?>