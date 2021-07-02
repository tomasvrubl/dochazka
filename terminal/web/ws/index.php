<?php

if (@!include __DIR__ . '/vendor/autoload.php') {
	die('Install packages using `composer install`');
}

require __DIR__."/lib.php";




$dt = json_decode(file_get_contents('php://input'));

if(!$dt){
    die('neplatny format vstupnich dat');
}


dibi::connect([
    'driver'   => 'mysqli',
    'host'     => 'localhost',
    'username' => 'dterm',
    'password' => 'Dt3rm*272',
    'database' => 'dochazka',
], 'mlocal');


$resp = [];

if(strcmp($dt->action, "akce") == 0){

    $lst = dibi::query("select * from udalost order by id")->fetchAll();

    $resp = [];
    foreach($lst as $l){
        $resp[] = ['lbl'=> $l['lbl'], 'id'=> $l['id'], 'io'=> $l['io'], 'css'=> $l['css'], 'sensortype'=> $l['sensortype']];
    }

}
else if(strcmp($dt->action, "osoba") == 0){

    $chip = substr($dt->chip, 0, 10);
    $o = dibi::query("select * from osoby where new_chip=%s", $chip)->fetch();

    if($o){
        $resp['id'] = $o['id'];
        $resp['osoba'] = $o['prijmeni']." ".$o['jmeno'];
        $resp['personalnum'] = $o['personalnum'];
        $resp['code_lo'] = $o['code_lo'];
        $resp['code_hi'] = $o['code_hi'];
        $resp['new_chip'] = $o['new_chip'];
        $resp['old_chip'] = $o['old_chip'];
    }
    else {
        $resp['id'] = -1;
        $resp['osoba'] = 'Neznáma osoba';
        $resp['personalnum'] = '';
        $resp['code_lo'] = '';
        $resp['code_hi'] = '';
        $resp['new_chip'] = '';
        $resp['old_chip'] = '';
    }
    

}
else if(strcmp($dt->action, "zapis") == 0){
    $oid = intval($dt->oid);
    $uid = intval($dt->uid);
    
    $a = [
        'cas'=>date('Y-m-d H:i:s'),
        'osoba_id'=> $oid,
        'udalost_id'=> $uid
    ];

    try{
        dibi::query('insert into dochazka %v', $a);
        $resp = ['code'=>0, 'msg'=>'Úspěšně zapsáno', 'data'=>null];
    }
    catch(Dibi\Exception $e){
        $resp = ['code'=>1, 'msg'=>$e->getMessage(), 'data'=>null];
    }
}
else if(strcmp($dt->action, "zapis2") == 0){
    
    $uid = intval($dt->uid);
    
    $chip = substr($dt->chip, 0, 10);
    $o = dibi::query("select * from osoby where new_chip=%s", $chip)->fetch();

    if($o){

        $a = [
            'cas'=>date('Y-m-d H:i:s'),
            'osoba_id'=> $o->id,
            'udalost_id'=> $uid
        ];

        try{
            dibi::query('insert into dochazka %v', $a);
            $resp = ['code'=>0, 'msg'=>'Úspěšně zapsáno', 'data'=>null];
        }
        catch(Dibi\Exception $e){
            $resp = ['code'=>1, 'msg'=>$e->getMessage(), 'data'=>null];
        }

    }
    else{
        $resp = ['code'=>0, 'msg'=>'Osoba nenalezena. Neplatný čip', 'data'=>null];
    }
    
}


echo json_encode($resp);


