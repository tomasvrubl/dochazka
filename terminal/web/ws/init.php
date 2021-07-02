<?php

if (@!include __DIR__ . '/vendor/autoload.php') {
	die('Install packages using `composer install`');
}

require __DIR__."/lib.php";


try{


    dibi::connect([
        'driver' => 'pdo',
        'pdo' => new \PDO ("dblib:version=5.0;host=192.168.1.186:1433;dbname=dochazka;charset=UTF-8", "sa", "sai")
    ], 'mssql');


    dibi::connect([
        'driver'   => 'mysqli',
        'host'     => 'localhost',
        'username' => 'dterm',
        'password' => 'Dt3rm*272',
        'database' => 'dochazka',
    ], 'mlocal');
    
}
catch(Exception $ex){

    echo $ex->getMessage();
    die();
}




