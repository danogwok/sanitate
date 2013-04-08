<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_water = "localhost";
$database_water = "sanitate";
$username_water = "root";
$password_water = "";
$water = mysql_pconnect($hostname_water, $username_water, $password_water) or trigger_error(mysql_error(),E_USER_ERROR); 
?>