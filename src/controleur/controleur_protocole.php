<?php

function actionProtocole($twig, $db) {

    $form = array();
    $protocole = new Protocole($db);
    $type_protocole = new Type_protocole($db);
    $form['valide1'] = true;
    $form['valide2'] = true;
    $form['valide3'] = true;

    //Si on appuie sur le bouton pour ajouter un médicament
    if (isset($_POST['btProtocole'])) {

        //On récupère les variables nécessaires à l'ajout d'un nouvel acronyme
        $nom_proto = $_POST['nom_proto'];
        $type_proto = $_POST['type_protocole'];
        $remarque = $_POST['remarque'];

        $liste = $protocole->selectAll();
        //Pour chaque medicament se trouvant dans la liste des medicaments
        foreach ($liste as $proto) {

            //Si l'acronyme du medicament entré par l'utilisateur correspond à l'acronyme d'un médicament de la liste
            if ($nom_proto == $proto['nom_proto']) {
                //On affiche un message d'erreur
                $form['valide1'] = false;
                $form['message1'] = 'Le protocole existe déja !!!';
            }
        }
        if ($form['valide1'] == true) {

            if (isset($_POST['cocherAdulte'])) {
                $cocherAdulte = $_POST['cocherAdulte'];
                foreach ($cocherAdulte as $id_proto) {
                    $cocherA = "Oui";
                }
            } else {
                $cocherA = "Non";
            }

            if (isset($_POST['cocherEnfant'])) {
                $cocherEnfant = $_POST['cocherEnfant'];
                foreach ($cocherEnfant as $id_proto) {
                    $cocherE = "Oui";
                }
            } else {
                $cocherE = "Non";
            }

            $exec = $protocole->insertAll($nom_proto, $type_proto, $cocherA, $cocherE, $remarque);

            //Si le nom et l'acronyme entrés ne sont pas déjà présents dans la liste
            if ($exec != false) {
                $form['valide'] = true;
                $form['valide3'] = false;
                $form['message'] = 'Vous avez ajouté un protocole';
            }
            //Sinon, on affiche
            else {
                $form['valide'] = false;
                $form['valide3'] = false;
                $form['message'] = 'Impossible d\'ajouter un protocole';
            }
        }
    }

    if (isset($_POST['btSupprimer'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $id) {
            $exec = $protocole->deleteOne($id);
            if (!$exec) {
                $form['valide2'] = false;
                $form['message'] = 'Problème de suppression dans la table protocole';
            } else {
                $form['valide'] = true;
                $form['valide3'] = false;
                $form['message'] = 'Protocole supprimé avec succès';
            }
        }
    }

    if (isset($_GET['id_proto'])) {
        $exec = $protocole->deleteOne($_GET['id_proto']);
        if (!$exec) {
            $form['valide2'] = false;
            $form['message'] = 'Problème de suppression dans la table protocole';
        } else {
            $form['valide'] = true;
            $form['valide3'] = false;
            $form['message'] = 'Protocole supprimé avec succès';
        }
    }

    $liste_type_protocole = $type_protocole->selectAll();
    $listeProtocole = $protocole->selectAll();

    echo $twig->render('protocole.html.twig', array('form' => $form, 'listeProtocole' => $listeProtocole, 'liste_type_protocole' => $liste_type_protocole));
}

function actionModifProtocole($twig, $db) {
    $form = array();
    $protocole = new Protocole($db);
    $type_protocole = new Type_protocole($db);
    $liste = $type_protocole->selectAll();

    if (isset($_GET['id_proto'])) {

        $unProtocole = $protocole->selectOne($_GET['id_proto']);

        if ($unProtocole != null) {
            $form['protocole'] = $unProtocole;
            $protocole = new Protocole($db);
        } else {
            $form['valide1'] = false;
            $form['message1'] = 'Protocole incorrect';
        }
    } else {
        if (isset($_POST['btModifier'])) {


            $id_proto = $_POST['id_proto'];
            $type_protocole = $_POST['type_protocole'];
            $nom_proto = $_POST['nom_proto'];
            $adulte = $_POST['adulte'];
            $enfant = $_POST['enfant'];
            $remarque = $_POST['remarque'];


            $exec = $protocole->updateAll($id_proto, $type_protocole, $nom_proto, $adulte, $enfant, $remarque);


            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';
            }
        }
    }
    
    //$form["type_protocole"] = $type_protocole->selectAll();
    echo $twig->render('modifprotocole.html.twig', array('form' => $form, 'liste' => $liste ));
}

?>