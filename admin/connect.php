<?php 

    $dsn    = 'mysql:host=localhost;dbname=shop - ecommerce' ; // database source name
    $user   = 'root';
    $pass   = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
    try{
        // connect
        $con = new PDO($dsn, $user, $pass, $option);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo 'You Are Connected , Welcome To Database';  // For Debug
    }
    catch(PDOException $e){
        echo 'Failed To Connect' . $e->getMessage();
    }


?>