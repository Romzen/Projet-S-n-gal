<?php

function actionAutoABS($twig, $db) {

//    $dateJour = New RdvJour();
//    $dispensation = new Dispensation($db);
//    $dateJour->dateJour();
//    $dateVeille = $dateJour->jourPrecedent();
//    $listeRDV = $dispensation->selectRDV();
//
//    foreach ($listeRDV as $rdv) {
//
//        if ($rdv[0] < $dateJour->dateJour2() && $rdv["etatRdv"] == 0) {
//            
//            $id_dispensation = $_POST['id_dispensation'];
//            $etatRdv = 2;
//            $exec = $dispensation->updatePresence($id_dispensation, $etatRdv);
//        }
//    }
    
    
    echo'bonjour';
    $ligne = New Ligne($db);
    $nom_ligne = "test122655";
    $exec = $ligne->insertMabite($nom_ligne); 
    
    //echo $twig->render('rdv.html.twig');
}

?>