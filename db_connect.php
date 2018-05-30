<?php
// avvio una connessione con il database MySQL
$dbServer = "localhost";
$dbUser = "vmware";
$dbPassword = "Password1";
$dbName = "vmware";

# CONNESSIONE AL DB
$db = new mysqli("$dbServer", "$dbUser", "$dbPassword", "$dbName");
if ($db->connect_errno) { echo "Connection Failed" ; exit(); }

?>