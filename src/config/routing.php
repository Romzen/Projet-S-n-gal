<?php

function getPage($db) {
    //LISTE DES PAGES DU SITE
    $LesPages['accueil'] = "actionAccueil;0";
    $LesPages['maintenance'] = "actionMaintenance;0";
    $LesPages['connexion'] = "actionConnexion;0";
    $LesPages['deconnexion'] = "actionDeconnexion;0";
    $LesPages['protocole'] = "actionProtocole;1";
    $LesPages['modifProtocole'] = "actionModifProtocole;1";
    $LesPages['patient'] = "actionListePatient;1";
    $LesPages['modifPatient'] = "actionModifPatient;1";
    $LesPages['ajouter_patient'] = "actionAjouterPatient;1";
    $LesPages['Ajouter_protocole'] = "actionAjouterProtocole;1";
    $LesPages['fichePatient'] = "actionFichePatient;1";
    $LesPages['detail'] = "actionIdPatient;1";
    $LesPages['fichePatientDisp'] = "actionFichePatientDisp";
    $LesPages['dispensation'] = "actionListDispensation;1";
    $LesPages['ajout_dispensation'] = "actionAjouterDispensation;1";
    $LesPages['droit'] = "actionListeDroit;1";
    $LesPages['utilisateur'] = "actionListeUtilisateur;1";
    $LesPages['ajout_utilisateur'] = "actionAjoutUtilisateur;1";
    $LesPages['modifUtilisateur'] = "actionModifUtilisateur;1";
    $LesPages['medicament'] = "actionMedicament;1";
    $LesPages['modifMedicament'] = "actionModifMedicament;1";
    $LesPages['repartition_adulte'] = "actionRepartitionAdulte;1";
    $LesPages['repartition_enfant'] = "actionRepartitionEnfant;1";
    $LesPages['repartition_poids'] = "actionRepartitionPoids;1";
    $LesPages['listeRDV'] = "actionListeRDV;1";
    $LesPages['rdvlistepdf'] = "actionRdvlistepdf;1";
    $LesPages['listeRDVJour'] = "actionListeRDVJour;1";
    $LesPages['listeRDVPatient'] = "actionListeRDVPatient;1";
    $LesPages['listeAbsent'] = "actionListeAbsent;1";
    $LesPages['rapport'] = "actionRapport;1";
    $LesPages['rapport_ligne'] = "actionRapportLigne;1";
    $LesPages['rapport_ligne_pdf'] = "actionRapportLignePdf;1";
    $LesPages['graphique'] = "actionGraph;1";
    $LesPages['ajouterDispensationPourLePatient'] = "actionAjouterDispensationPourLePatient;1";
    $LesPages['savoir'] = "actionSavoir;1";
    $LesPages['suiviDispensation'] = "actionSuiviDispensation;1";
    $LesPages['listerdvpdf'] = "actionListeRDVPdf;1";
    $LesPages['graphe'] = "actionGraphe;1";
    $LesPages['autoABS'] = "actionAutoABS;1";
    $LesPages['rapport_VIH'] = "actionRapport_VIH;1";


    if ($db != null) {
        if (isset($_GET['page'])) {
        //Si la variable page est définie dans la barre URL
            $page = $_GET['page'];
            //On récupère a valeur de la page
        } else {
            if (isset($_SESSION['login'])) {
                $page = 'accueil'; //Sinon on renvoie à la page d'accueil
            } else {
                $page = 'connexion';
            }
        }

        if (!isset($LesPages[$page])) {
        //Si la clef dont la valeur est celle de la variable page, n'existe pas dans le tableau
            if (isset($_SESSION['login'])) {
                $page = 'accueil'; //Sinon on renvoie à la page d'accueil
            } else {
                $page = 'connexion';
            }
        }

        //On récupère le role de la page
        $explose = explode(";", $LesPages[$page]);
        $role = $explose[1];

        //Si le rôle est 0
        if ($role == 0) {
            $contenu = $explose[0];
            //On affiche le contenu directement
        } else {
            if (isset($_SESSION['login'])) {
            //Si la personne est connectée
                $rolePersonne = $_SESSION['role'];
                //On récupère son role
                if ($rolePersonne >= $role) {
                //Si le role de la personne est le même que celui requis pour la page
                    $contenu = $explose[0];
                    //On affiche le contenu
                } else {
                    $contenu = "accueil";
                    //Sinon, si la personne n'a pas les droits requis, on affiche l'accueil
                }
            } else {
                $contenu = "connexion";
                //Sinon si la personne n'est pas connectée, alors on affiche l'accueil
            }
        }
    }
    return $contenu;
    //On retourne la valeur du contenu
}

?>
