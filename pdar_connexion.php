<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_pdar_connexion = "193.37.145.62";
$database_pdar_connexion = "ruche582371";
$username_pdar_connexion = "ruche582371";
$password_pdar_connexion = "nsT3yZpP";
$pdar_connexion = mysql_pconnect($hostname_pdar_connexion, $username_pdar_connexion, $password_pdar_connexion) or trigger_error(mysql_error(),E_USER_ERROR);   
/*$hostname_pdar_connexion = "localhost";
$database_pdar_connexion = "o108703_sepdar";
$username_pdar_connexion = "o108703_pdarsse";
$password_pdar_connexion = "qTUE5B_";
$pdar_connexion = mysql_pconnect($hostname_pdar_connexion, $username_pdar_connexion, $password_pdar_connexion) or trigger_error(mysql_error(),E_USER_ERROR);*/
?>