<?php

$config = parse_ini_file('db.ini');
$connect = new PDO($config['server'].":host=".$config['host'].";dbname=".$config['dbname'],$config['username'],$config['password']);

?>
