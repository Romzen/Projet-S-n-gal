<?php

function actionGraphe($twig, $db) {

    $graphe = new Graphe($db);
    $form = array();
    $date = array();
    $liste = array();
    $listeDispensation = array();

    if (!isset($_GET['annee'])) {
        $year = date('Y');
    } else {
        $year = $_GET['annee'];
    }

    $id_patient = $_GET['id_patient'];
    $listeAnnee = $graphe->selectAnnee();
    $listeDispensation = $graphe->selectGraphe($id_patient, $year);

    if (!empty($listeDispensation)) {
        for ($i = 0; $i < count($listeDispensation); $i++) {
            $jour = $listeDispensation[$i]['jour'];
            $mois = $listeDispensation[$i]['mois'] - 1;
            $annee = $listeDispensation[$i]['annee'];

            $date = $annee . ',' . $mois . ',' . $jour;

            $liste[$i]['date'] = $date;
            $liste[$i]['poids'] = $listeDispensation[$i]['poids'];
            $liste[$i]['num_id_national'] = $listeDispensation[$i]['num_id_national'];
            $num_id_national = $liste[$i]['num_id_national'];
        }
        $form['valide'] = true;
    } else {
        $form['valide'] = false;
        $form['message'] = 'Pas de dispensation disponible';
    }

    echo $twig->render('graphe.html.twig', array('form' => $form, 'listeDispensation' => $listeDispensation, 'listeAnnee' => $listeAnnee, 'date' => $date, 'liste' => $liste, 'num_id_national' => $num_id_national, 'id_patient' => $id_patient));
}
