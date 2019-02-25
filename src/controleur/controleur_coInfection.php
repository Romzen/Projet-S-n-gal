<?php

function actionCo_infection($twig, $db) {
    $form = array();
    $coInfection = new Co_infection($db);

    if (isset($_POST['btAjouter'])) {
        $nom_co_infection = $_POST['nom_infection'];
        $description = $_POST['desc'];
        $exec = $coInfection->insert($nom_co_infection, $description);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = "Impossible d'ajouter l'infection";
        } else {
            $form['valide'] = true;
            $form['message'] = "Ajout réussi";
        }
    }   
    if(isset($_POST['btSupprimer'])){
        $id_co_infection = $_POST['btSupprimer'];
        $delete = $coInfection->delete($id_co_infection);
        
        if (!$delete) {
            $form['valide'] = false;
            $form['message'] = "Impossible de supprimer l'infection";
        } else {
            $form['valide'] = true;
            $form['message'] = "Suppression réussie";
        }
    }
    $listeCo = $coInfection->selectAll();

    echo $twig->render('listeCo_infection.html.twig', array('form' => $form, 'listeCo' => $listeCo));
}
