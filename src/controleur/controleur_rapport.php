<?php

function actionRapport($twig, $db) {
    $rapport = new Rapport($db);
    $listeAnnee = array();
    $listeAnnee = $rapport->selectAnnee();

    if (isset($_GET['annee'])) {

        $annee = $_GET['annee'];
    } else {
        $annee = date('Y');
    }

    echo $twig->render('rapport.html.twig', array("listeAnnee" => $listeAnnee, "annee" => $annee));
}

function actionRapportLigne($twig, $db) {
    $rapport = new Rapport($db);
    $patient = new Patient($db);

    $annee = $_GET['annee'];
    $mois = $_GET['mois'];


    for ($i = 1; $i < 4; $i++) {

        $M_VIH1_0 = 0;
        $M_VIH2_0 = 0;
        $M_VIH3_0 = 0;
        $M_VIH1_1 = 0;
        $M_VIH2_1 = 0;
        $M_VIH3_1 = 0;
        $F_VIH1_0 = 0;
        $F_VIH2_0 = 0;
        $F_VIH3_0 = 0;
        $F_VIH1_1 = 0;
        $F_VIH2_1 = 0;
        $F_VIH3_1 = 0;


        $T[$i]['M_VIH1_0'] = $M_VIH1_0;
        $T[$i]['M_VIH2_0'] = $M_VIH2_0;
        $T[$i]['M_VIH3_0'] = $M_VIH3_0;
        $T[$i]['M_VIH1_1'] = $M_VIH1_1;
        $T[$i]['M_VIH2_1'] = $M_VIH2_1;
        $T[$i]['M_VIH3_1'] = $M_VIH3_1;
        $T[$i]['F_VIH1_0'] = $F_VIH1_0;
        $T[$i]['F_VIH2_0'] = $F_VIH2_0;
        $T[$i]['F_VIH3_0'] = $F_VIH3_0;
        $T[$i]['F_VIH1_1'] = $F_VIH1_1;
        $T[$i]['F_VIH2_1'] = $F_VIH2_1;
        $T[$i]['F_VIH3_1'] = $F_VIH3_1;

        $listePatient = $rapport->selectAllPatient($annee, $mois);

        foreach ($listePatient as $unPatient) {

            $AgePatient = $patient->selectAge($unPatient['id_patient']);
            $age = $AgePatient['Age'];

            if ($unPatient['ligne'] == $i) {

                if ($age >= 0 && $age <= 14) {

                    if ($unPatient['sexe'] == 'M') {

                        if ($unPatient['profil_serologique'] == 1) {

                            $T[$i]['M_VIH1_0'] = $T[$i]['M_VIH1_0'] + 1;
                        } elseif ($unPatient['profil_serologique'] == 2) {

                            $T[$i]['M_VIH2_0'] = $T[$i]['M_VIH2_0'] + 1;
                        } else {

                            $T[$i]['M_VIH3_0'] = $T[$i]['M_VIH3_0'] + 1;
                        }
                    } else {

                        if ($unPatient['profil_serologique'] == 1) {

                            $T[$i]['F_VIH1_0'] = $T[$i]['F_VIH1_0'] + 1;
                        } elseif ($unPatient['profil_serologique'] == 2) {

                            $T[$i]['F_VIH2_0'] = $T[$i]['F_VIH2_0'] + 1;
                        } else {

                            $T[$i]['F_VIH3_0'] = $T[$i]['F_VIH3_0'] + 1;
                        }
                    }
                } elseif ($age > 14) {

                    if ($unPatient['sexe'] == 'M') {

                        if ($unPatient['profil_serologique'] == 1) {

                            $T[$i]['M_VIH1_1'] = $T[$i]['M_VIH1_1'] + 1;
                        } elseif ($unPatient['profil_serologique'] == 2) {

                            $T[$i]['M_VIH2_1'] = $T[$i]['M_VIH2_1'] + 1;
                        } else {

                            $T[$i]['M_VIH3_1'] = $T[$i]['M_VIH3_1'] + 1;
                        }
                    } else {

                        if ($unPatient['profil_serologique'] == 1) {

                            $T[$i]['F_VIH1_1'] = $T[$i]['F_VIH1_1'] + 1;
                        } elseif ($unPatient['profil_serologique'] == 2) {

                            $T[$i]['F_VIH2_1'] = $T[$i]['F_VIH2_1'] + 1;
                        } else {

                            $T[$i]['F_VIH3_1'] = $T[$i]['F_VIH3_1'] + 1;
                        }
                    }
                }
            }
        }
    }

    echo $twig->render('rapport_ligne.html.twig', array('tab' => $T));
}

function actionRapportLignePdf($twig, $db) {
    $rapport = new Rapport($db);
    $patient = new Patient($db);

    $T = array();
    $listePatient = array();
    $leMois = "";
    $annee = $_GET['annee'];
    $mois = $_GET['mois'];


    $listeMois = array('', 'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');

    for ($z = 0; $z < count($listeMois); $z++) {

        if ($mois == $z) {
            $leMois = $listeMois[$z];
        }
    }

    $listePatient = $rapport->selectAllPatient($annee, $mois);

    for ($i = 1; $i < 4; $i++) {

        $M_VIH1_0 = 0;
        $M_VIH2_0 = 0;
        $M_VIH3_0 = 0;
        $M_VIH1_1 = 0;
        $M_VIH2_1 = 0;
        $M_VIH3_1 = 0;
        $F_VIH1_0 = 0;
        $F_VIH2_0 = 0;
        $F_VIH3_0 = 0;
        $F_VIH1_1 = 0;
        $F_VIH2_1 = 0;
        $F_VIH3_1 = 0;


        $T[$i]['M_VIH1_0'] = $M_VIH1_0;
        $T[$i]['M_VIH2_0'] = $M_VIH2_0;
        $T[$i]['M_VIH3_0'] = $M_VIH3_0;
        $T[$i]['M_VIH1_1'] = $M_VIH1_1;
        $T[$i]['M_VIH2_1'] = $M_VIH2_1;
        $T[$i]['M_VIH3_1'] = $M_VIH3_1;
        $T[$i]['F_VIH1_0'] = $F_VIH1_0;
        $T[$i]['F_VIH2_0'] = $F_VIH2_0;
        $T[$i]['F_VIH3_0'] = $F_VIH3_0;
        $T[$i]['F_VIH1_1'] = $F_VIH1_1;
        $T[$i]['F_VIH2_1'] = $F_VIH2_1;
        $T[$i]['F_VIH3_1'] = $F_VIH3_1;


        foreach ($listePatient as $unPatient) {

            $AgePatient = $patient->selectAge($unPatient['id_patient']);
            $age = $AgePatient['Age'];

            if ($unPatient['ligne'] == $i) {

                if ($age >= 0 && $age <= 14) {

                    if ($unPatient['sexe'] == 'M') {

                        if ($unPatient['profil_serologique'] == 1) {

                            $T[$i]['M_VIH1_0'] = $T[$i]['M_VIH1_0'] + 1;
                        } elseif ($unPatient['profil_serologique'] == 2) {

                            $T[$i]['M_VIH2_0'] = $T[$i]['M_VIH2_0'] + 1;
                        } else {

                            $T[$i]['M_VIH3_0'] = $T[$i]['M_VIH3_0'] + 1;
                        }
                    } else {

                        if ($unPatient['profil_serologique'] == 1) {

                            $T[$i]['F_VIH1_0'] = $T[$i]['F_VIH1_0'] + 1;
                        } elseif ($unPatient['profil_serologique'] == 2) {

                            $T[$i]['F_VIH2_0'] = $T[$i]['F_VIH2_0'] + 1;
                        } else {

                            $T[$i]['F_VIH3_0'] = $T[$i]['F_VIH3_0'] + 1;
                        }
                    }
                } elseif ($age > 14) {

                    if ($unPatient['sexe'] == 'M') {

                        if ($unPatient['profil_serologique'] == 1) {

                            $T[$i]['M_VIH1_1'] = $T[$i]['M_VIH1_1'] + 1;
                        } elseif ($unPatient['profil_serologique'] == 2) {

                            $T[$i]['M_VIH2_1'] = $T[$i]['M_VIH2_1'] + 1;
                        } else {

                            $T[$i]['M_VIH3_1'] = $T[$i]['M_VIH3_1'] + 1;
                        }
                    } else {

                        if ($unPatient['profil_serologique'] == 1) {

                            $T[$i]['F_VIH1_1'] = $T[$i]['F_VIH1_1'] + 1;
                        } elseif ($unPatient['profil_serologique'] == 2) {

                            $T[$i]['F_VIH2_1'] = $T[$i]['F_VIH2_1'] + 1;
                        } else {

                            $T[$i]['F_VIH3_1'] = $T[$i]['F_VIH3_1'] + 1;
                        }
                    }
                }
            }
        }
    }

    $html = $twig->render('rapport_ligne_pdf.html.twig', array('tab' => $T, 'annee' => $annee, 'leMois' => $leMois));

    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('L', 'A4', 'fr');

        $html2pdf->writeHTML($html);
        $html2pdf->output('rapport_ligne_pdf.pdf');
    } catch (Html2PdfException $e) {
        echo 'erreur ' . $e;
    }
}

function actionRapport_VIH($twig,$db){
    $form=array();
    
    $rapport = new Rapport($db);
    $annee = $_GET['annee'];
    $mois = $_GET['mois'];
    $jour = 01;
    
    $date =  $annee .'-'. $mois .'-'. $jour;
    
    $nbrJour=$rapport->selectJdeMois($date);
        
    $nbrInscritAvant = $rapport->selectInscritAvant($date);
    $nbrInscritSuivis = $rapport->selectPatientSuvis($date);
    $nbrInscritDuMois = $rapport->selectInscritCeMois($date);
    
    $compteur1 = array();
    $compteur2 = array();
    
    $compteur1['femme'] = 0;
    $compteur1['homme'] = 0;
    $compteur1['fille'] = 0;
    $compteur1['garçon'] = 0;
    $compteur1['total'] = 0;
    
    $compteur2['femme'] = 0;
    $compteur2['homme'] = 0;
    $compteur2['fille'] = 0;
    $compteur2['garçon'] = 0;
    $compteur2['total'] = 0;
    
    $compteur3['femme'] = 0;
    $compteur3['homme'] = 0;
    $compteur3['fille'] = 0;
    $compteur3['garçon'] = 0;
    $compteur3['total'] = 0;
    
    foreach($nbrInscritAvant as $inscritAvant){
        if($inscritAvant['sexe'] == 'M'){
            if($inscritAvant['age'] < 14 ){
                $compteur1['garçon'] += 1;
                $compteur1['total'] += 1;
            }else{
                $compteur1['homme'] += 1;
                $compteur1['total'] += 1;
            }
        }else{
            if($inscritAvant['age'] < 14 ){
                $compteur1['fille'] += 1;
                $compteur1['total'] += 1;
            }else{
                $compteur1['femme'] += 1;
                $compteur1['total'] += 1;
            }
        }
    }
    
    foreach($nbrInscritSuivis as $inscritsSuivis){
        if($inscritsSuivis['sexe'] == 'M'){
            if($inscritsSuivis['age'] < 14 ){
                $compteur2['garçon'] += 1;
                $compteur2['total'] += 1;
            }else{
                $compteur2['homme'] += 1;
                $compteur2['total'] += 1;
            }
        }else{
            if($inscritsSuivis['age'] < 14 ){
                $compteur2['fille'] += 1;
                $compteur2['total'] += 1;
            }else{
                $compteur2['femme'] += 1;
                $compteur2['total'] += 1;
            }
        }
    }
    
    foreach($nbrInscritDuMois as $nouveau){
        if($nouveau['sexe'] == 'M'){
            if($nouveau['age'] < 14 ){
                $compteur3['garçon'] += 1;
                $compteur3['total'] += 1;
            }else{
                $compteur3['homme'] += 1;
                $compteur3['total'] += 1;
            }
        }else{
            if($nouveau['age'] < 14 ){
                $compteur3['fille'] += 1;
                $compteur3['total'] += 1;
            }else{
                $compteur3['femme'] += 1;
                $compteur3['total'] += 1;
            }
        }
    }
    
    echo $twig->render('rapport_Vih.html.twig', array('form'=>$form,'compteur1'=>$compteur1,'compteur2'=>$compteur2,'compteur3'=>$compteur3));   
}
