<?php

function actionRepartitionEnfant($twig, $db) {
    $repartitions = new repartition_enfant($db);
    $listeAtyp = array();
    $liste = array();
    $listeTotal = array();

    if (!isset($_GET['annee'])) {
        $annee = date('Y');
    } else {
        $annee = $_GET['annee'];
    }

    $lesRepartitions = $repartitions->selectAll();
    $lesRepartitionsAtyp = $repartitions->selectAll2($annee);
    $listeAnnee = $repartitions->selectAnnee();

    for ($x = 0; $x < count($lesRepartitions); $x++) {
        $liste[$x]['idproto'] = $lesRepartitions[$x]['id_proto'];
        $liste[$x]['nomproto'] = $lesRepartitions[$x]['nom_proto'];

        for ($y = 1; $y <= 12; $y++) {

            $id_proto = $lesRepartitions[$x]['id_proto'];

            $z = $repartitions->selectByMois($y, $id_proto, $annee);
            $compteur = $z['compteur'];
            if (!isset($listeTotal['nonAtypique'][$y])) {
                $listeTotal['nonAtypique'][$y] = 0;
            }
            if (!isset($listeTotal['total'][$y])) {
                $listeTotal['total'][$y] = 0;
            }
            if (empty($compteur)) {
                $liste[$x]['listecompteur'][$y] = '0';
            } else {
                $liste[$x]['listecompteur'][$y] = $compteur;
                $listeTotal['nonAtypique'][$y] = $listeTotal['nonAtypique'][$y] + $compteur;
                $listeTotal['total'][$y] = $listeTotal['total'][$y] + $compteur;
            }
        }
    }

    for ($x = 0; $x < count($lesRepartitionsAtyp); $x++) {
        $listeAtyp[$x]['idproto'] = $lesRepartitionsAtyp[$x]['id_proto'];
        $listeAtyp[$x]['nomproto'] = $lesRepartitionsAtyp[$x]['nom_proto'];
        for ($y = 1; $y <= 12; $y++) {
            $id_proto = $lesRepartitionsAtyp[$x]['id_proto'];

            $z = $repartitions->selectByMoisAtyp($y, $id_proto, $annee);
            $compteur = $z['compteur'];
            if (!isset($listeTotal['Atypique'][$y])) {
                $listeTotal['Atypique'][$y] = 0;
            }
            if (!isset($listeTotal['total'][$y])) {
                $listeTotal['total'][$y] = 0;
            }

            if (empty($compteur)) {
                $listeAtyp[$x]['listecompteur'][$y] = '0';
            } else {
                $listeAtyp[$x]['listecompteur'][$y] = $compteur;
                $listeTotal['Atypique'][$y] = $listeTotal['Atypique'][$y] + $compteur;
                $listeTotal['total'][$y] = $listeTotal['total'][$y] + $compteur;
            }
        }
    }
    echo $twig->render('repartition_enfant.html.twig', array('liste' => $liste, 'listeAtyp' => $listeAtyp, 'listeTotal' => $listeTotal, 'listeAnnee' => $listeAnnee));
}

function actionRepartitionAdulte($twig, $db) {
    $repartitions = new repartition_adulte($db);
    $listeAtyp = array();
    $liste = array();
    $listeTotal = array();

    if (!isset($_GET['annee'])) {
        $annee = date('Y');
    } else {
        $annee = $_GET['annee'];
    }

    $lesRepartitions = $repartitions->selectAll();
    $lesRepartitionsAtyp = $repartitions->selectAll2();
    $listeAnnee = $repartitions->selectAnnee();

    for ($x = 0; $x < count($lesRepartitions); $x++) {
        $liste[$x]['idproto'] = $lesRepartitions[$x]['id_proto'];
        $liste[$x]['nomproto'] = $lesRepartitions[$x]['nom_proto'];
        for ($y = 1; $y <= 12; $y++) {

            $id_proto = $lesRepartitions[$x]['id_proto'];

            $z = $repartitions->selectByMois($y, $id_proto, $annee);
            $compteur = $z['compteur'];
            if (!isset($listeTotal['nonAtypique'][$y])) {
                $listeTotal['nonAtypique'][$y] = 0;
            }
            if (!isset($listeTotal['total'][$y])) {
                $listeTotal['total'][$y] = 0;
            }
            if (empty($compteur)) {
                $liste[$x]['listecompteur'][$y] = '0';
            } else {
                $liste[$x]['listecompteur'][$y] = $compteur;
                $listeTotal['nonAtypique'][$y] = $listeTotal['nonAtypique'][$y] + $compteur;
                $listeTotal['total'][$y] = $listeTotal['total'][$y] + $compteur;
            }
        }
    }

    for ($x = 0; $x < count($lesRepartitionsAtyp); $x++) {
        $listeAtyp[$x]['idproto'] = $lesRepartitionsAtyp[$x]['id_proto'];
        $listeAtyp[$x]['nomproto'] = $lesRepartitionsAtyp[$x]['nom_proto'];

        for ($y = 1; $y <= 12; $y++) {
            $id_proto = $lesRepartitionsAtyp[$x]['id_proto'];

            $z = $repartitions->selectByMoisAtyp($y, $id_proto, $annee);
            $compteur = $z['compteur'];
            if (!isset($listeTotal['Atypique'][$y])) {
                $listeTotal['Atypique'][$y] = 0;
            }
            if (!isset($listeTotal['total'][$y])) {
                $listeTotal['total'][$y] = 0;
            }

            if (empty($compteur)) {
                $listeAtyp[$x]['listecompteur'][$y] = '0';
            } else {
                $listeAtyp[$x]['listecompteur'][$y] = $compteur;
                $listeTotal['Atypique'][$y] = $listeTotal['Atypique'][$y] + $compteur;
                $listeTotal['total'][$y] = $listeTotal['total'][$y] + $compteur;
            }
        }
    }
    echo $twig->render('repartition_adulte.html.twig', array('liste' => $liste, 'listeAtyp' => $listeAtyp, 'listeTotal' => $listeTotal, 'listeAnnee' => $listeAnnee));
}

function actionRepartitionPoids($twig, $db) {

//    $repartitition = new Repartition_Poids($db);
//    $protocoleDDD = new Protocole($db);
//
//
//    $listeTest = $repartitition->selectTest();
//    $listePro = $protocoleDDD->selectAll();
//
//    for ($i = 0; $i < count($listePro); $i++) {
//        if($listePro[$i]['id_proto'] == $listeTest[$i]['protocole']){
//            echo'ça marche';
//        }
//        print_r($listePro[$i]['id_proto']);
//    }
//
// 
//
//
//

    $repartition = new Repartition_Poids($db);
    $protocole = $repartition->selectProto();
    $listePoids = array();
    $compteur = 0;

    for ($x = 0; $x < count($protocole); $x++) {

        $listeP[$x]['protocole'] = $protocole[$x]['nom_proto'];
        $listeP[$x]['idProto'] = $protocole[$x]['id_proto'];
        $idProto = $listeP[$x]['idProto'];

        $listePoids = $repartition->selectAll($idProto);

        $listeP[$x]['tablePoids'] = $listePoids;

        $compteur1 = 0;
        $compteur2 = 0;
        $compteur3 = 0;
        $compteur4 = 0;
        $compteur5 = 0;

        $total1 = 0;


        for ($y = 0; $y < count($listePoids); $y++) {
            if ($listeP[$x]['tablePoids'][$y]['poids'] < 15) {
                $compteur1 += $listeP[$x]['tablePoids'][$y]['compteur'];
            } else if ($listeP[$x]['tablePoids'][$y]['poids'] > 15 && $listeP[$x]['tablePoids'][$y]['poids'] < 30) {
                $compteur2 += $listeP[$x]['tablePoids'][$y]['compteur'];
            } else if ($listeP[$x]['tablePoids'][$y]['poids'] > 30 && $listeP[$x]['tablePoids'][$y]['poids'] < 45) {
                $compteur3 += $listeP[$x]['tablePoids'][$y]['compteur'];
            } else if ($listeP[$x]['tablePoids'][$y]['poids'] > 45 && $listeP[$x]['tablePoids'][$y]['poids'] < 60) {
                $compteur4 += $listeP[$x]['tablePoids'][$y]['compteur'];
            } else if ($listeP[$x]['tablePoids'][$y]['poids'] > 60) {
                $compteur5 += $listeP[$x]['tablePoids'][$y]['compteur'];
            }
        }

        $listeP[$x]['nombre1'] = $compteur1;
        $listeP[$x]['nombre2'] = $compteur2;
        $listeP[$x]['nombre3'] = $compteur3;
        $listeP[$x]['nombre4'] = $compteur4;
        $listeP[$x]['nombre5'] = $compteur5;

        $listeP[$x]['total1'] = 0;
    }

    $nb = count($listeP) + 1;
    $listeP[$nb]['nombre1'] = 0;
    $listeP[$nb]['nombre2'] = 0;
    $listeP[$nb]['nombre3'] = 0;
    $listeP[$nb]['nombre4'] = 0;
    $listeP[$nb]['nombre5'] = 0;
    for ($t = 0; $t < count($listeP) - 1; $t++) {
        $listeP[$nb]['nombre1'] += $listeP[$t]['nombre1'];
        $listeP[$nb]['nombre2'] += $listeP[$t]['nombre2'];
        $listeP[$nb]['nombre3'] += $listeP[$t]['nombre3'];
        $listeP[$nb]['nombre4'] += $listeP[$t]['nombre4'];
        $listeP[$nb]['nombre5'] += $listeP[$t]['nombre5'];
        $listeP[$nb]['protocole'] = "Total";
    }


    echo $twig->render('repartition_poids.html.twig', array('listeP' => $listeP));
}

?>