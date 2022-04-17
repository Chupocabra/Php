<?php
$parameters=parse_ini_file('config/parameters.ini');
$dsn='mysql:host=' . $parameters['host'] . ';dbname=' . $parameters['dbname'];
    try {
        $dbh = new PDO($dsn, $parameters['login'], $parameters['password']);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>"; //в случае ошибки оставляем сообщение об ошибке
        die();
}