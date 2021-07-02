<?php

require_once __DIR__."/../lib.php";

/***
 * dbr - dochazka server
 * dbl - lokalni server
 */
function sync_osoby() {

    $q = "select p.personid, p.personalnum, p.firstname , p.surname, t.touchcodehi, t.touchcodelo 
                from pwk.Person p inner join pwk.Person_TouchOwner pto
                on p.PersonID  = pto.PersonID inner join pwk.Touch_Owner ow on pto.TouchOwnerID  = ow.TouchOwnerID 
                inner JOIN pwk.Touch t on ow.TouchID=t.TouchID 
                where p.DeletedID =0 and ow.DeletedID = 0 and t.TouchCodeHi > 0 order BY p.Surname asc";

    $db = dibi::getConnection("mssql");
    $dt = $db->query($q)->fetchAll();
    

    $db2 = dibi::getConnection("mlocal");
    $mds = [];

    foreach ($dt as $d) {                

        $md = md5($d['personid'].":".$d['personalnum'].":".$d['touchcodehi'].":".$d['touchcodelo']);
        $mds[] = $md;

        $r = $db2->query("select * from osoby where md5=%s", $md)->fetch();

        if(!$r){

            $c = convToNew($d['touchcodehi'], $d['touchcodelo']);

            $a = [
                'personid'=>$d['personid'],
                'prijmeni'=>$d['surname'],
                'jmeno'=>$d['firstname'] == null ? "" : $d['firstname'],
                'personalnum'=>$d['personalnum'],
                'code_lo'=>intval($d['touchcodelo']),
                'code_hi'=>intval($d['touchcodehi']),
                'old_chip'=> $c->orig,
                'new_chip'=> $c->chip,
                'md5'=> $md
            ];

            try{
                echo "\e[33m[Insert]: \e[0m", $d['surname'] , " ", $d['firstname'], " \e[/ ", $d['personid'], "\n";
                $db2->query('insert into osoby %v', $a);
            }
            catch(Dibi\Exception $e){
                echo $e->getMessage();
            }
        }
    }


    try{
        
        $db2->query("delete from osoby where md5 not in (%s)", $mds);

    }
    catch(Dibi\Exception $e){
        echo $e->getMessage();
    }

}