<?php

function actionListeDroit($twig, $db) {

    $grade = new Grade($db);
    $lesGrades = $grade->selectAll();

    $droit = new Droit($db);
    $lesDroit = $droit->listeDroit();

    for ($x = 0; $x < count($lesGrades); $x++) {
        for ($y = 0; $y < count($lesDroit); $y++) {
            if ($lesGrades[$x]['nom_grade'] == $lesDroit[$y]['nom_grade']) {
                $lesGrades[$x]['droit'][$y] = $lesDroit[$y]['remarque'];
            }
        }
    }

//echo '<pre>';
//print_r($lesGrades);    
//echo '</pre>';

    echo $twig->render('droit.html.twig', array("lesGrades" => $lesGrades));
}

?>