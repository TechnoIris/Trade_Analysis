<?php
include('db.php');

$query = "drop database kdb if exists";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

$query = "create database if not exists kdb";
$statement = $connect->prepare($query);
$statement->execute();
// $result = $statement->fetchAll();

$query = "use kdb";
$statement = $connect->prepare($query);
$statement->execute();

$query = "create table stocks (id int(4) not null primary key auto_increment, stockname varchar(64) not null, date_ date not null, price integer(12) not null)";
// $query = "CREATE TABLE STOCKS (ID INT(4) NOT NULL AUTO_INCREMENT, STOCKNAME VARCHAR(64) NOT NULL PRIMARY KEY, DATE_ DATE NOT NULL, PRICE INT(12) NOT NULL)";
$statement = $connect->prepare($query);
$statement->execute();
 ?>
