<?php

function actionAjouterPatient($twig, $db) {
    $form = array();

    $suiviPresence = new Patient($db);
    $listeProfilsSerologiques = new profil_serologique($db);
    $lesProfilsSérologiques = $listeProfilsSerologiques->selectAll();

    $coInf = new co_infection($db);
    $listeCoInf = $coInf->selectAll();

    $listeProtocoles = new Protocole($db);
    $lesProtocoles = $listeProtocoles->selectAll();

    $listeLignes = new ligne($db);
    $lesLignes = $listeLignes->selectAll();

    //Si on appuie sur le bouton pour ajouter un patient
    if (isset($_POST['btPatient'])) {
        //On récupère les informations du patient et on instancie un booléen qui autorisera l'insertion d'un nouveau patient
        $impossible = 0;
        $num_id_national = $_POST['num_id_national'];
        $num_inclusion = $_POST['num_inclusion'];
        $profil_serologique = $_POST['profil_serologique'];
        $sexe = $_POST['sexe'];
        $date_de_naissance = $_POST['date_de_naissance'];
        $protocole = $_POST['protocole'];
        $ligne = $_POST['ligne'];
        $date_inclusion = $_POST['date_inclusion'];
        $inclusion = $_POST['inclusion'];
        $today = date('Y-m-d');
        $poids = $_POST['poids'];


        $patient = new Patient($db);
        $listePatient = $patient->selectAll();

        foreach ($listePatient as $unPatient) {
            if ($unPatient['num_id_national'] == $num_id_national) {
                if ($unPatient['num_inclusion'] == $num_inclusion) {
                    $impossible = 1;
                    $form['patientExiste'] = True;
                }
            }
        }

        $nb = $patient->insertAll($num_id_national, $num_inclusion, 1, $profil_serologique, $sexe, $date_de_naissance, $protocole, $poids, $ligne, NULL, $date_inclusion, $inclusion);
        $id_patient = $db->lastInsertId();
        $suivi = new Suivi($db);
        $suivi->insertAll($id_patient, $today, $protocole);
    }

    echo $twig->render('ajoutpatient.html.twig', (array('form' => $form, 'lesProfilsSérologiques' => $lesProfilsSérologiques, 'listeCoInf' => $listeCoInf, 'lesProtocoles' => $lesProtocoles, 'lesLignes' => $lesLignes)));
}

function actionListePatient($twig, $db) {
    $patient = new Patient($db);
    $lesPatients = $patient->selectAll();

    $etat = new Etat_dispensation($db);
    $profil_serologique = new Profil_serologique($db);
    $protocole = new Protocole($db);
    $ligne = new Ligne($db);
    $co_infection = new Co_infection($db);
    $type_inclusion = new Inclusion($db);
    $dispensation = new Dispensation($db);


    for ($x = 0; $x < count($lesPatients); $x++) {
        $etatPatient = $etat->selectOne($lesPatients[$x]['etat_patient']);
        $lesPatients[$x]['nom_etat_dispen'] = $etatPatient['nom_etat_dispen'];

        $profilpatient = $profil_serologique->selectOne($lesPatients[$x]['profil_serologique']);
        $lesPatients[$x]['nom_profil'] = $profilpatient['nom_profil'];

        $protocolePatient = $protocole->selectOne($lesPatients[$x]['protocole']);
        $lesPatients[$x]['nom_proto'] = $protocolePatient['nom_proto'];

        $lignePatient = $ligne->selectOne($lesPatients[$x]['ligne']);
        $lesPatients[$x]['nom_ligne'] = $lignePatient['nom_ligne'];

        $co_infectionPatient = $co_infection->selectId($lesPatients[$x]['co_infections']);
        $lesPatients[$x]['nom_co_infection'] = $co_infectionPatient['nom_co_infection'];

        $type_inclusionPatient = $type_inclusion->selectOne($lesPatients[$x]['inclusion']);
        $lesPatients[$x]['type_inclusion'] = $type_inclusionPatient['type_inclusion'];

        $dispensationPatient = $dispensation->selectDerniereDispen($lesPatients[$x]['id_patient']);
        $lesPatients[$x]['date_dispensation'] = $dispensationPatient['derniereDisp'];
        $lesPatients[$x]['rdv'] = $dispensationPatient['rdv'];
    }

    echo $twig->render('patients.html.twig', array('lesPatients' => $lesPatients));
}

function actionModifPatient($twig, $db) {
    $patient = new Patient($db);
    $suiviPresence = new Patient($db);
    $form = array();
    $id_patient = $_GET['id_patient'];

    if (isset($_POST['btSupprimer'])) {
        $id_patient = $_POST['id_patient'];

        $nb = $patient->deleteOne($id_patient);
        if ($nb != 1) {
            $form['suppno'] = true;
        } else {
            $form['suppyes'] = true;
        }
    }
    if (isset($_POST['btModifier'])) {
        $id_patient = $_POST['id_patient'];
        $num_id_national = $_POST['num_id_national'];
        $num_inclusion = $_POST['num_inclusion'];
        $profil_serologique = $_POST['profil_serologique'];
        $sexe = $_POST['sexe'];
        $date_de_naissance = $_POST['date_de_naissance'];
        $protocole = $_POST['protocole'];
        $poids = $_POST['poids'];
        $ligne = $_POST['ligne'];
        $date_inclusion = $_POST['date_inclusion'];
        $co_infections = $_POST['co_infection'];
        $inclusion = $_POST['inclusion'];
        $today = date('Y-m-d');

        $exec = $patient->updateAll($id_patient, $num_inclusion, $num_id_national, $profil_serologique, $sexe, $date_de_naissance, $protocole, $poids, $ligne, $co_infections, $date_inclusion, $inclusion);
        if ($exec == 1) {
            $form['modifyes'] = true;
        } else {
            $form['modifno'] = true;
        }

        $exec2 = $suiviPresence->insert2($id_patient, $today, $protocole, $poids,$ligne,$profil_serologique);
        if ($exec2 == 1) {
            $form['modifyes'] = true;
        } else {
            $form['modifno'] = true;
        }
    }

    $unPatient = $patient->selectId($id_patient);

    $profil = new profil_serologique($db);
    $listeProfil = $profil->selectAll();

    $proto = new protocole($db);
    $listeProto = $proto->selectAll();

    $coInf = new co_infection($db);
    $listeCoInf = $coInf->selectAll();

    $ligne = new ligne($db);
    $listeLigne = $ligne->selectAll();

    $inclusion = new inclusion($db);
    $listeInclusion = $inclusion->selectAll();

    echo $twig->render('modifpatient.html.twig', array('unPatient' => $unPatient, 'listeProto' => $listeProto, 'listeCoInf' => $listeCoInf, 'listeProfil' => $listeProfil, 'form' => $form, 'listeLigne' => $listeLigne, 'listeInclusion' => $listeInclusion));
}

function actionIdPatient($twig, $db) {
    $form = array();
    $legende = array();
    $listMois = array(" ", "de Janvier", "de Février", "de Mars", "d'Avril", "de Mai", "de Juin", "de Juillet", "d'Aout", "de Septembre", "d'Octobre", "de Novembre", "de Décembre");

    $patient = new Patient($db);
    $dispensation = new Dispensation($db);
    $etat_dispensation = new Etat_dispensation($db);
    $listeAnnee = array();
    $listeAnnee = $patient->selectAnnee();
    $listePatient = $patient->selectAll();

    if (isset($_GET['annee'])) {

        $annee = $_GET['annee'];
    } else {
        $annee = date('Y');
    }

    if (isset($_GET['id_patient'])) {

        $id_patient = $_GET['id_patient'];
        $unPatient = $patient->selectId($id_patient); //selectId renvoie la valeur false si il n'a pas trouver de patient

        if ($unPatient != false) {

            for ($mois = 1; $mois <= 12; $mois++) {
                $dispensationPatient[$mois] = $dispensation->selectOneYearMonth($unPatient['id_patient'], $mois, $annee);
                $etat_dispen = $etat_dispensation->selectOne($dispensationPatient[$mois]['etat_dispensation']);
                $dispensationPatient[$mois]['nom_etat_dispen'] = $etat_dispen['nom_etat_dispen'];
//                echo "<pre>";
//                var_dump($mois);
//                echo "</pre>";
            }

            $legende[0] = 'Statut';
            $legende[1] = 'Date de la dispensation';
            $legende[2] = 'Date début traitement';
            $legende[3] = 'Nombre de jours de traitement dispensés';
            $legende[4] = 'Date de fin de traitement';
            $legende[5] = 'Date du prochain rdv';
            $legende[6] = 'Poids';
            $legende[7] = 'Observations';
            $legende[8] = ' ';


            if (isset($_GET['btModifDisp'])) {
                
                $uneDispensation = $dispensation->selectUneDisp($_GET['id_dispensation']);

                if ($unProtocole != null) {
            $form['protocole'] = $unProtocole;
            $protocole = new Protocole($db);
        } else {
            $form['valide1'] = false;
            $form['message1'] = 'Protocole incorrect';
        }
    } else {
                
                $id_dispensation = $_POST['id_dispensation'];
                $etat_dispensation = $_POST['etat_dispensation'];
                $date_dispensation = $_POST['date_dispensation'];
                $nb_jours_traitement = $_POST['nb_jours_traitement'];
                $date_fin_traitement = $_POST['date_fin_traitement'];

                $modifdispensation = new dispensation($db);

                $nb = $modifdispensation->updateAll($id_dispensation, $etat_dispensation, $date_dispensation, $nb_jours_traitement, $date_fin_traitement);

                if ($nb != 1) {
                    $form['modifno'] = true;
                } else {
                    $form['modifyes'] = true;
                }
            }
        } else {
            $form['falseID'] = true;
        }
    }
    $date['annee'] = date("Y");

    echo $twig->render('idpatient.html.twig', array('legende' => $legende, 'date' => $date, 'dispensationPatient' => $dispensationPatient, 'form' => $form, 'unPatient' => $unPatient, "listeAnnee" => $listeAnnee, "annee" => $annee, "listMois" => $listMois, "mois" => $mois, "listePatient" => $listePatient));
}

function actionFichePatient($twig, $db) {
    $patient = new Patient($db);
    $etatdisp = new Etat_dispensation($db);

    if (isset($_GET['id_patient'])) {
        $id_patient = $_GET['id_patient'];
        $lePatient = $patient->selectID($id_patient);
    }

    if (isset($_POST['btModif'])) {
        $lePatient = $patient->selectId($_POST['id_patient']);


        $lEtat = $_POST['etat_patient'];
        echo'<pre>';
        print_r($lEtat);
        echo'</pre>';
        $id_patient = $_POST['id_patient'];
        $num_id_national = $lePatient['num_id_national'];
        $etat_patient = $_POST['etat_patient'];


        $update = $patient->updateEtat($id_patient, $etat_patient);

        if ($lEtat == 1) {
            $suivi = new Suivi($db);
            $today = date('Y-m-d');
            $protocole = $lePatient['protocole'];
            $suivi->insertAll($id_patient, $today, $protocole);
        }
    }



    $date_inclusion = new inclusion($db);
    $DateInc = $date_inclusion->selectOne($lePatient['inclusion']);
    $lePatient['type_inclusion'] = $DateInc['type_inclusion'];

    $dispensation = new dispensation($db);
    $derniereDispen = $dispensation->selectDerniereDispen($id_patient);
    $lePatient['derniereDisp'] = $derniereDispen['derniereDisp'];

    $DateDerniereDisp = $dispensation->selectDateDisp($derniereDispen['derniereDisp']);
    $lePatient['rdv'] = $DateDerniereDisp['rdv'];

    $etat_dispensation = new Etat_dispensation($db);
    $sonEtat = $etat_dispensation->selectOne($lePatient['etat_patient']);
    $lePatient['nom_etat_dispen'] = $sonEtat['nom_etat_dispen'];

    $listeDispensation = $etat_dispensation->selectAll();

    $ligne = new ligne($db);
    $nomLigne = $ligne->selectOne($lePatient['ligne']);
    $lePatient['nom_ligne'] = $nomLigne['nom_ligne'];

    $profil_serologique = new profil_serologique($db);
    $nomProfil = $profil_serologique->selectOne($lePatient['profil_serologique']);
    $lePatient['nom_profil'] = $nomProfil['nom_profil'];

    $protocole = new protocole($db);
    $unProtocole = $protocole->selectOne($lePatient['protocole']);
    $lePatient['nom_proto'] = $unProtocole['nom_proto'];

    $co_infection = new co_infection($db);
    $unCo_infection = $co_infection->selectId($lePatient['co_infections']);
    $lePatient['nom_co_infection'] = $unCo_infection['nom_co_infection'];

    echo $twig->render('fichepatient.html.twig', array('lePatient' => $lePatient, 'listeDispensation' => $listeDispensation));
}

?>
