<?php

require_once __DIR__."/../lib.php";

/***
 * dbr - dochazka server
 * dbl - lokalni server
 */
function send_dochazka() {

    $db = dibi::getConnection("mlocal");
    $lst = $db->query("select *, DATE_FORMAT(cas, '%Y-%m-%d %T') s_cas from view_dochazka where sent=0")->fetchAll();

    $db2 = dibi::getConnection("mssql");

    foreach($lst as $l){

        $a = [
            'ServerTime'=>date('Y-m-d H:i:s'),
            'EventTime'=>$l['s_cas'],
            'HardwareID'=>33,
            'EventTypeID'=>1,
            'IsAuthorized'=>1,
            'IsAttnEvent'=>1,
            'InOutType'=> intval($l['io']),
            'SensorNumber'=>intval($l['sensortype']),
            'MediumType'=>2,
            'TouchCodeLo'=>$l['code_lo'],
            'TouchCodeHi'=>$l['code_hi'],
            'PersonID'=>$l['personid'],
            'DenoteID'=>$l['pwk_id']
        ];

        try
        {
            $db2->query('insert into [pwk].[AccessEvent] %v', $a);
            $db->query("update dochazka set sent=1 where id=%i", $l['id']);

            echo json_encode(['code'=>0, 'msg'=>'Úspěšně zapsáno', 'data'=>null]);
        }
        catch(Dibi\Exception $e){
            echo json_encode(['code'=>1, 'msg'=>$e->getMessage(), 'data'=>null]);
        }



    }

}
