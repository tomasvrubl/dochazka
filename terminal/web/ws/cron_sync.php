<?php

include __DIR__.'/init.php';
require_once __DIR__.'/cron/sync_osoba.php';
require_once __DIR__.'/cron/dochazka.php';

try{
    
    sync_osoby();

}catch(Exception $ex){
    echo "ex-osoby: ", $ex->getMessage();
}


try{
    send_dochazka();
}catch(Exception $ex){
    echo "ex-dochazka: ", $ex->getMessage();
}


