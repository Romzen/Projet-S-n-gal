<?php

function actionListDispensation($twig, $db) {

    $protocole = new protocole($db);
    $listePro = $protocole->selectAll();

    $profil_serologique = new profil_serologique($db);
    $listePS = $profil_serologique->selectAll();

    $type_protocole = new Type_protocole($db);
    $listeTypeProto = $type_protocole->selectAll();

    $ligne = new ligne($db);
    $listeL = $ligne->selectAll();

    $patient = new patient($db);
    $listeP = $patient->selectAll();

    $nb = count($listeP);

    $formatDate = "d/m/Y";

    echo $twig->render('listedispensation.html.twig', array("listeP" => $listeP, "listeL" => $listeL, "listePS" => $listePS, "listePro" => $listePro, "listeTypeProto" => $listeTypeProto));
}

function actionAjouterDispensation($twig, $db) {
    $patient = new Patient($db);
    $listeId = array();
    $listeT = array();
    $unPatient = array();

    if (isset($_POST['btPatient'])) {
        $num_id_national = $_POST['num_id_national'];
        $listeT = $patient->selectOne3($num_id_national);
        if (!empty($listeT)) {
              $unPatient['trouveoui'] = true;
        } else {
            $unPatient['trouvernon'] = true;
        }
    }
    $listePatients = $patient->selectAll();

    echo $twig->render('ajoutdispensation.html.twig', array("listePatients" => $listePatients, "listeId" => $listeId, "unPatient" => $unPatient, 'listeT'=>$listeT));
}

function actionAjouterDispensationPourLePatient($twig, $db) {
    $form = array();

    if (isset($_POST['btAjoutDisp'])) {
        $id_patient = $_GET['id_patient'];
        $etat_dispensation = $_POST['etat_dispensation'];
        $date_dispensation = $_POST['date_dispensation'];
        $date_debut_traitement = $_POST['date_debut_traitement'];
        $nb_jours_traitement = $_POST['nb_jours_traitement'];
        $date_fin_traitement = $_POST['date_fin_traitement'];
        $rdv = $_POST['rdv'];
        $poids = $_POST['poids'];
        $observations = $_POST['observations'];

        $dispensations = new dispensation($db);

        $exce = $dispensations->insertAll($id_patient, $etat_dispensation, $date_dispensation, $date_debut_traitement, $nb_jours_traitement, $date_fin_traitement, $rdv, $poids, $observations);

        if ($exce == false) {
            $form['erreur'] = true;
        } else {
            $form['ajout'] = true;
        }
    }

    $id_patient = $_GET['id_patient'];

    $patients = new Patient($db);
    $lePatient = $patients->selectId($id_patient);

    $etats = new Etat_dispensation($db);
    $lesEtats = $etats->selectAll();


    echo $twig->render('ajoutdispensationpourlepatient.html.twig', array("form" => $form, 'lesEtats' => $lesEtats, 'lePatient' => $lePatient));
}

function actionSuiviDispensation($twig, $db) {
    $patient = new Patient($db);
    $dispensation = new dispensation($db);
    $etat_dispensation = new Etat_dispensation($db);
    $suivi = new Suivi($db);
    $lesPatients = $patient->selectAll();
    $lePatient = array();

    if (isset($_GET['annee'])) {
        $date['annee'] = $_GET['annee'];
    } else {
        $date['annee'] = date("Y");
    }

    $date['anneeNow'] = date("Y");

    for ($x = 0; $x < count($lesPatients); $x++) {

        $lesPatients[$x]['dispensation'] = $dispensation->selectIdYear($lesPatients[$x]['id_patient'], $date['annee']);
        $lesPatients[$x]['derniereDispensation'] = $dispensation->selectDerniereDispen($lesPatients[$x]['id_patient']);

        if (count($lesPatients[$x]['dispensation']) > 0) {
            $lePatient[$x]['id_patient'] = $lesPatients[$x]['id_patient'];
            $lePatient[$x]['lesDispensation'] = array();
            $lePatient[$x]['etat'] = $lesPatients[$x]['etat_patient'];

            $explode = explode("-", $lesPatients[$x]['derniereDispensation']['rdv']);

            if ($explode[0] == $date['annee']) {
                $dateRdv = $lesPatients[$x]['derniereDispensation']['rdv'];
                $moisRdv = $explode[1];
                $jourRdv = $explode[2];
            }

            for ($y = 0; $y < count($lesPatients[$x]['dispensation']); $y++) {

                $explode = explode("-", $lesPatients[$x]['dispensation'][$y]['date_dispensation']);
                $mois = $explode[1];

                $repet = 1;
                for ($z = 1; $z <= 12; $z++) {
                    if ((int) $mois == $z) {
//                      $lePatient[$x]['lesDispensation']['mois'.$z] =  $lesPatients[$x]['dispensation'][$y]['date_dispensation'];

                        $etatLaDispentation = $lesPatients[$x]['dispensation'][$y]['etat_dispensation'];

                        $dateDisp = explode("-", date('m-y', strtotime($lesPatients[$x]['dispensation'][$y]['date_dispensation'])));
                        $result = 'N';

                        $vJtraitement = ($lesPatients[$x]['dispensation'][$y]['nb_jours_traitement'] / 30) - 1;

                        if ($etatLaDispentation == 1) {
                            $result = 'X ' . $explode[2];
                            if ($vJtraitement == 1) {
                                if ($z + 1 <= 12) {
                                    $lePatient[$x]['lesDispensation'][$z + 1] = 'X';
                                    $lePatient[$x]['lesEtats'][$z + 1] = 1;
                                }
                            }
                            if ($vJtraitement == 2) {

                                if ($z + 1 <= 12) {
                                    $lePatient[$x]['lesDispensation'][$z + 1] = 'X';
                                    $lePatient[$x]['lesEtats'][$z + 1] = 1;
                                }
                                if ($z + 2 <= 12) {
                                    $lePatient[$x]['lesDispensation'][$z + 2] = 'X';
                                    $lePatient[$x]['lesEtats'][$z + 2] = 1;
                                }
                            }
                        }

                        if ($etatLaDispentation == 2) {
                            $result = 'ABS ' . $explode[2];
                        }

                        if ($etatLaDispentation == 3) {
                            $result = 'DCD ' . $dateDisp[0] . "/" . $dateDisp[1];
                            $repet = 2;
                        }

                        if ($etatLaDispentation == 4) {
                            $result = 'ABAN ' . $dateDisp[0] . "/" . $dateDisp[1];
                            $repet = 2;
                        }

                        if ($etatLaDispentation == 5) {
                            $result = 'TSO ' . $dateDisp[0] . "/" . $dateDisp[1];
                            $repet = 2;
                        }

                        $lePatient[$x]['lesDispensation'][$z] = $result;
                        $lePatient[$x]['lesEtats'][$z] = $etatLaDispentation;
                    } else if (!empty($lePatient[$x]['lesDispensation'][$z])) {
                        
                    } else {
                        if ($repet == 2) {
                            $lePatient[$x]['lesDispensation'][$z] = $result;
                            $lePatient[$x]['lesEtats'][$z] = $etatLaDispentation;
                        } else {
                            $lePatient[$x]['lesDispensation'][$z] = '';
                            $lePatient[$x]['lesEtats'][$z] = '';
                        }
                    }

                    if ($moisRdv == $z) {
                        if ($lePatient[$x]['etat'] == 1) {
                            $lePatient[$x]['lesDispensation'][$z] = $jourRdv;
                            $lePatient[$x]['lesEtats'][$z] = 7;
                        }
                    }
                }
            }
            ksort($lePatient[$x]['lesDispensation'], SORT_NATURAL);
            ksort($lePatient[$x]['lesEtats'], SORT_NATURAL);
        }
    }


//    echo '<pre>';
//    print_r($lesPatients);
//    echo'</pre>';
////    
//    echo '<pre>';
//    print_r($lesPatients[0]['derniereDispensation']);
//    echo'</pre>';
//////    
////    
//   echo '<pre>';
//   print_r($lePatient);
//    echo'</pre>';
////    


    echo $twig->render('suividispensation.html.twig', array('lesPatients' => $lesPatients, 'date' => $date, 'lePatient' => $lePatient));
}
