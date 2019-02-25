<?php

$db = NEW PDO('mysql:host=localhost; dbname=BDsenegal3', 'root', 'btsinfo');

$query1 = $db->query("SELECT p.* FROM PATIENT p");

$listePatient = $query1->fetchAll();

$datedujour = date("d-m-Y");

for ($i = 0; $i < count($listePatient); $i++) {
    $query2[$i] = $db->query("SELECT p.id_patient,p.etatPtesence,max(d.date_dispensation) as 'date_dispensation' 
                              FROM PATIENT p inner join DISPENSATION d ON p.id_patient = d.id_patient 
                              WHERE p.id_patient = " . $listePatient[$i]['id_patient']."");

    $listeDispensation[$i] = $query2[$i]->fetch();

    $anciennedate = $listeDispensation[$i]['date_dispensation'];
    $jmoins = ((strtotime($datedujour)) - strtotime($anciennedate));
    $jmoins = round($jmoins / (60 * 60 * 24));

    if ($jmoins > 100) {
        $query3 = $db->query("UPDATE PATIENT SET etatPresence = 2 where id_patient =".$listeDispensation[$i]['id_patient']."");
    } else if($jmoins < 100 &&  $listeDispensation[$i]['etatPresence'] = 2) {
        $query4 = $db->query("UPDATE PATIENT SET etatPresence = 3 where id_patient =".$listeDispensation[$i]['id_patient']."");
    }
}
?>