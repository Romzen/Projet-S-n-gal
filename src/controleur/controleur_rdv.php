<?php

//////////////////////////////////////////////////// LISTE DES RDV///////////////////////////////////////////////////////////////////////

function actionListeRDV($twig, $db) {

    $form = array();
    $listeP = array();
    $dateJour = New RdvJour();
    $dispensation = new Dispensation($db);
    $dateJour->dateJour();
    $listeRDV = $dispensation->selectRDV();
    $listeRDV2 = $dispensation->selectRDV2();
    $listeAbs = $dispensation->selectAbs();
    $form['nombreAbs'] = count($listeAbs);

    foreach ($listeRDV as $rdv) {

        if ($dateJour->dateJour2() == $rdv[0] && $rdv["etatRdv"] == 0) {

            $listeP = $dispensation->selectPatient($rdv[0]);
            $form['nombreRDV'] = count($listeP);
            $form['valide'] = true;
        }

        if (empty($listeP)) {

            $form['valide'] = false;
            $form['message'] = 'Il n y a pas de rendez-vous aujourd\'hui';
        }
    }

    echo $twig->render('rdv.html.twig', array('form' => $form, 'listeP' => $listeP, 'listeRDV' => $listeRDV, 'listeAbs' => $listeAbs, 'listeRDV2' => $listeRDV2));
}

//////////////////////////////////////////////////// LISTE DES RDV DU JOUR///////////////////////////////////////////////////////////////////////

function actionListeRDVJour($twig, $db) {

    //Code pour la validation des absents ou des présents
    $form = array();
    $dispensation = new Dispensation($db);
    $form['valide1'] = true;
    $form['valide2'] = false;

    if (isset($_POST['btValider'])) {


        if (isset($_POST['cocher'])) {

            $cocher = $_POST['cocher'];

            foreach ($cocher as $id_dispensation) {

                $etatRdv = 1;
                $exec = $dispensation->updatePresence($id_dispensation, $etatRdv);
            }
            if (!$exec) {
                $form['valide1'] = false;
                $form['message1'] = 'Echec de la modification';
            } else {
                $form['valide2'] = true;
                $form['message2'] = 'Modification réussie';
            }
        }

        if (isset($_POST['cocherAbs'])) {

            $cocherAbs = $_POST['cocherAbs'];

            foreach ($cocherAbs as $id_dispensation) {

                $etatRdv = 2;
                $exec = $dispensation->updatePresence($id_dispensation, $etatRdv);
            }
            if (!$exec) {
                $form['valide1'] = false;
                $form['message1'] = 'Echec de la modification';
            } else {
                $form['valide2'] = true;
                $form['message2'] = 'Modification réussie';
            }
        }
    }

    //Code pour parcourir les rendez-vous du jours et les afficher

    
    $dateJour = New RdvJour();
    $dispensation = new Dispensation($db);
    $dateJour->dateJour();
    //$dateVeille = $dateJour->jourPrecedent();
    $listeRDV = $dispensation->selectRDV();

    foreach ($listeRDV as $rdv) {

        if ($dateJour->dateJour2() == $rdv[0] && $rdv["etatRdv"] == 0) {

            $listeP = $dispensation->selectPatient($rdv[0]);
            $form['nombreRDV'] = count($listeP);
        }
    }


    echo $twig->render('rdvJour.html.twig', array('form' => $form, 'listeP' => $listeP, 'listeRDV' => $listeRDV));
}

//////////////////////////////////////////////////// LISTE DES ABSENTS///////////////////////////////////////////////////////////////////////

function actionListeAbsent($twig, $db) {
    $form = array();

    //Code pour la validation des absents ou des présents

    $dispensation = new Dispensation($db);
    $form['valide1'] = true;
    $form['valide2'] = false;

    if (isset($_POST['btValider'])) {

        if (isset($_POST['cocherAbsT'])) {

            $cocherAbsT = $_POST['cocherAbsT'];

            foreach ($cocherAbsT as $id_dispensation) {

                $etatRdv = 3;
                $exec = $dispensation->updateAbsence($id_dispensation, $etatRdv);
            }

            if (!$exec) {
                $form['valide1'] = false;
                $form['message1'] = 'Problème de modification dans la table rdv';
            } else {
                $form['valide2'] = true;
                $form['message2'] = 'Modification réussie';
            }
        }


        if (isset($_POST['cocherP'])) {

            $cocherP = $_POST['cocherP'];

            foreach ($cocherP as $id_dispensation) {

                $etatRdv = 1;
                $exec = $dispensation->updateAbsence($id_dispensation, $etatRdv);
            }

            if (!$exec) {
                $form['valide1'] = false;
                $form['message1'] = 'Problème de modification dans la table rdv';
            } else {
                $form['valide2'] = true;
                $form['message2'] = 'Modification réussie';
            }
        }
    }

    //Code pour parcourir les rendez-vous du jours et les afficher

    $listeRDV = $dispensation->selectAbs();

    $form['nombreAbs'] = count($listeRDV);

    echo $twig->render('listeAbsent.html.twig', array('form' => $form, 'listeRDV' => $listeRDV));
}

//////////////////////////////////////////////////// PDF DES RDV///////////////////////////////////////////////////////////////////////

function actionListeRDVPdf($twig, $db) {
    $dispensation = new Dispensation($db);
    $patient = new Patient($db);
    $listeP = $patient->listePatient();
    $listeRDV = $dispensation->selectAll();
    $html = $twig->render('rdv-liste-pdf.html.twig', array('listeP' => $listeP, 'listeRDV' => $listeRDV));

    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');

        $html2pdf->writeHTML($html);
        $html2pdf->output('listedesrdv.pdf');
    } catch (Html2PdfException $e) {
        echo 'erreur ' . $e;
    }
}

//////////////////////////////////////////////////// LISTE DES RDV PAR PATIENT///////////////////////////////////////////////////////////////////////

function actionListeRDVPatient($twig, $db) {

    $form = array();
    //$listeP = array();
    $dispensation = new Dispensation($db);
    $listeRDV = $dispensation->selectRDVPatient($_GET['id_patient']);
    $form['nombreRDV'] = count($listeRDV);

    echo $twig->render('listeRDVPatient.html.twig', array('form' => $form, 'listeRDV' => $listeRDV));
}
