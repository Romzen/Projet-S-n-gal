<?php

function actionListeUtilisateur($twig, $db) {
    $form = array();
    $utilisateur = new Utilisateur($db);
    $grade = new Grade($db);
    

    if (isset($_GET['id_utilisateur'])) {
        $exec = $utilisateur->deleteOne($_GET['id_utilisateur']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table produit';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Produit supprimé avec succès';
        }
    }
    
    $liste = $utilisateur->selectAll();
    $listeGrade = $grade->selectAll();

    echo $twig->render('utilisateur.html.twig', array('form' => $form, 'liste' => $liste, 'listeGrade' => $listeGrade));
}

function actionAjoutUtilisateur($twig, $db) {

    $form = array();
    $form['valide'] = true;
    $grade = new Grade($db);
    $listeGrade = $grade->selectAll();

    if (isset($_POST['btUtilisateur'])) {

        $nom_utilisateur = $_POST['nom_utilisateur'];
        $prenom_utilisateur = $_POST['prenom_utilisateur'];
        $login = $_POST['login'];
        $mdp = $_POST['mdp'];
        $grade = $_POST['grade'];
        $mdp2 = $_POST['mdp2'];
        $utilisateur = new utilisateur($db);
        $nb = $utilisateur->insertAll($nom_utilisateur, $prenom_utilisateur, $login, $mdp, $grade, 1);
        if ($mdp != $mdp2) {
            echo '<br><div class="well center alert alert-danger" role="alert">Vous avez mal confirmé votre mot de passe, réessayez !</div><script>$(".well").fadeTo(5000, 200).slideUp(500);</script>';
        } else {
            echo '<br><div class="well center alert alert-success" role="alert">Vous avez ajouté un utilisateur !</div><script>$(".well").fadeTo(5000, 200).slideUp(500);</script>';
        }
    }
    echo $twig->render('ajoututilisateur.html.twig', array('form' => $form, 'listeGrade' => $listeGrade));
}

function actionModifUtilisateur($twig, $db) {

    $form = array();
    $utilisateur = new Utilisateur($db);
    $grade = new Grade($db);
    $liste = $grade->selectAll();

    if (isset($_GET['id_utilisateur'])) {

        $unUtilisateur = $utilisateur->selectOne($_GET['id_utilisateur']);

        if ($unUtilisateur != null) {
            $form['utilisateur'] = $unUtilisateur;
            $utilisateur = new Utilisateur($db);
        } else {
            $form['valide1'] = false;
            $form['message1'] = 'Utilisateur incorrect';
        }
    } else {
        if (isset($_POST['btModifier'])) {


            $id_utilisateur = $_POST['id_utilisateur'];
            $nom_utilisateur = $_POST['nom_utilisateur'];
            $prenom_utilisateur = $_POST['prenom_utilisateur'];
            $login = $_POST['login'];
            $mdp = $_POST['mdp'];
            $grade = $_POST['grade'];


            $exec = $utilisateur->updateAll($id_utilisateur, $nom_utilisateur, $prenom_utilisateur, $login, $mdp, $grade);


            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';
            }
        }
    }


    //$form["grade"] = $grade->selectAll();

    echo $twig->render('modifutilisateur.html.twig', array('form' => $form, 'liste' => $liste));

}
