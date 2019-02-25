<?php

function actionAccueil($twig, $db) {

    $form = array();
    $listeP = array();
    $dateJour = New RdvJour();
    $dispensation = new Dispensation($db);
    $dateJour->dateJour();
    $listeRDV = $dispensation->selectRDV();
    //$form['nombreRDV'] = count($rdvDuJour);


    foreach ($listeRDV as $rdv) {

        if ($dateJour->dateJour2() == $rdv[0] && $rdv["etatRdv"] == 0) {

            $listeP = $dispensation->selectPatient($rdv[0]);
            $form['nombreRDV'] = count($listeP);
            //echo $twig->render('accueil.html.twig',array('form'=>$form,'listeP'=>$listeP,'listeRDV'=>$listeRDV));
        }
        if (empty($listeP)) {

            $form['valide'] = false;
            $form['message'] = 'Il n y a pas de rendez-vous aujourd\'hui';
        }
    }
    
    echo $twig->render('accueil.html.twig', array('form' => $form, 'listeP' => $listeP, 'listeRDV' => $listeRDV));
}

function actionConnexion($twig, $db) {
    $form = array();

    if (isset($_POST['btConnexion'])) {
    //Si on appuie sur le bouton de connexion
        //On récupère le login et le mot de passe renseigné
        $login = $_POST['login'];
        $mdp = $_POST['mdp'];

        //On appelle la classe Utilisateur
        $utilisateur = new utilisateur($db);
        //On utilise la RQ permettant de vérifier la connexion
        $unUtilisateur = $utilisateur->selectConnexion($login, $mdp);

        //On appelle la classe grade
        $grade = new grade($db);
        //On récupère la liste de tous le grades
        $listeGrade = $grade->selectAll();

        //Si les identifiants de connexion correspondent à ceux d'un utilisateur
        if ($unUtilisateur != null) {
            //Enregistrement du login dans la superglobale $_SESSION
            $_SESSION['login'] = $unUtilisateur['login'];

            //Pour chaque grade se trouvant dans la liste des grades
            foreach ($listeGrade as $unGrade) {
                //Si le grade de l'utilisateur correspond à celui de la liste
                if ($unUtilisateur['grade'] == $unGrade['id_grade']) {
                    //On enregistre l'ID et le nom du grade dans $_SESSION
                    $_SESSION['role'] = $unGrade['id_grade'];
                    $_SESSION['grade'] = $unGrade['nom_grade'];

                    //On appelle la classe qui permet de donner des droits selon le grade
                    $ass_grade_droit = new ass_grade_droit($db);
                    //Récupération des droits selon le grade
                    $liste_ass_grade_droit = $ass_grade_droit->selectOneGrade($unGrade['id_grade']);

                    //Pour chaque droit se trouvant dans la liste de droits, récupérées selon le grade
                    foreach ($liste_ass_grade_droit as $uneLigne) {
                        $droits[$uneLigne['id_droit']] = $uneLigne['id_droit'];
                        //Réupération des droits dans un tableau
                    }

                    $_SESSION['droits'] = $droits;
                    //Stockage du tableau dans la superglobale $_SESSION
                }
            }
            header('Location: index.php?page=accueil');
        } else {
            $form['message'] = 'Login ou mot de passe incorrect';
        }
    }
    echo $twig->render('connexion.html.twig', array('form' => $form));
}

function actionDeconnexion($twig) {
    session_unset();
    session_destroy();
    echo $twig->render('connexion.html.twig');
}

function actionMaintenance($twig) {
    echo $twig->render('maintenance.html.twig');
}

function actionSavoir($twig) {
    echo $twig->render('savoir.html.twig');
}

?>