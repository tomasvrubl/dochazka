<?php

if (@!include __DIR__ . '/vendor/autoload.php') {
	die('Install packages using `composer install`');
}




if(!isset($_REQUEST['action'])){
    
    $dt = json_decode(file_get_contents('php://input'));

    if(!$dt){
        die('neplatny format vstupnich dat');
    }

    try{
        if(strcmp($dt->action, "zapis") == 0){

            dibi::connect([
                'driver' => 'pdo',
                'pdo' => new \PDO ("dblib:host=192.168.1.186:1433;dbname=dochazka;charset=UTF-8", "sa", "sai")
            ]);
                
            $oid = intval($dt->oid);
            $u = $dt->udalost;
            
            $a = [
                'ServerTime'=>date('Y-m-d H:i:s'),
                'EventTime'=>date('Y-m-d H:i:s'),
                'HardwareID'=>33,
                'EventTypeID'=>1,
                'IsAuthorized'=>1,
                'IsAttnEvent'=>1,
                'InOutType'=> intval($u->io),
                'SensorNumber'=>intval($u->sensortype),
                'MediumType'=>2,
                'TouchCodeLo'=>0,
                'TouchCodeHi'=>0,
                'PersonID'=>$oid,
                'DenoteID'=>$u->id
            ];

            try{
                dibi::query('insert into [pwk].[AccessEvent] %v', $a);
                echo json_encode(['code'=>0, 'msg'=>'Úspěšně zapsáno', 'data'=>null]);
            }
            catch(Dibi\Exception $e){
                echo json_encode(['code'=>1, 'msg'=>$e->getMessage(), 'data'=>null]);
            }
            

        }
    }catch(Exception $ex){
        echo json_encode(['code'=>1, 'msg'=>$ex->getMessage(), 'data'=>null]);
    }

    exit();
}



$action = trim(filter_var($_REQUEST['action'], FILTER_SANITIZE_SPECIAL_CHARS));


if(strcmp($action, "osoby") == 0){

    echo json_encode([
                      ['osoba'=>'Radek Chýlek', 'personid'=>100221],
                      ['osoba'=>'Petr Otýpka', 'personid'=>2211]
                    ]);
}
else if(strcmp($action, "akce") == 0){

    echo json_encode([  
                    ['lbl'=>'Příchod', 'id'=>143, 'io'=>1, 'sensortype'=>1,  'css'=>'css-prichod fas fa-sign-in-alt'],     //nazev, denoteid, inout
                    ['lbl'=>'Odchod', 'id'=>145, 'io'=>2, 'sensortype'=>2, 'css'=>'css-odchod fas fa-sign-out-alt'],
                    ['lbl'=>'Dovolená', 'id'=>147, 'io'=>2, 'sensortype'=>2, 'css'=>'css-dovol fas fa-umbrella-beach'],
                    ['lbl'=>'Paragraf', 'id'=>148, 'io'=>2, 'sensortype'=>2, 'css'=>'css-parag fas fa-user-md'],
                    ['lbl'=>'Služební cesta', 'id'=>149, 'io'=>2, 'sensortype'=>2, 'css'=>'css-slcesta fas fa-suitcase'],
                    ['lbl'=>'Náhradní volno', 'id'=>152, 'io'=>2, 'sensortype'=>2, 'css'=>'css-nahvol fas fa-sign-out-alt'],
                    ['lbl'=>'Lékař', 'id'=>158, 'io'=>2, 'sensortype'=>2, 'css'=>'css-lekar fas fa-user-md']
                ]);

}
else{
    echo "...";
}

