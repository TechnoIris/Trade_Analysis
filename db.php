<?php

$config = parse_ini_file('db.ini');
// $connect = new PDO($config['server'].":host=".$config['host'],$config['username'],$config['password']);
$connect = new PDO($config['server'].":host=".$config['host'],$config['username'],$config['password']);

?>
